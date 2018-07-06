<?php

namespace telegramBot;

class TelegramServer
{
    protected $url = 'https://api.telegram.org/bot';
    protected $proxy;

    protected $array_data;

    public function __construct($token, $proxy = null)
    {
        $this->url  .= $token.'/';
        $this->proxy = $proxy;

        $this->getData();
    }

    public function sendRequest($method, $content)
    {
        $url = $this->url.$method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        if (!empty($this->proxy)) {
            curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy["type"]);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy["auth"]);
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy["url"]);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
        curl_close($ch);

        return json_decode($result, true);
    }

    protected function getData()
    {
        if (empty($this->array_data)) {
            $data = file_get_contents('php://input');
            $this->array_data = json_decode($data, true);
        }
    }
}