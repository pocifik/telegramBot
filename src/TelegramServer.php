<?php

namespace telegramBot;

use telegramBot\enums\MessageType;
use telegramBot\enums\RequestType;
use telegramBot\models\Chat;
use telegramBot\models\Contact;
use telegramBot\models\Message;
use telegramBot\models\User;

class TelegramServer
{
    protected $url;
    protected $proxy;

    protected $array_data;

    protected $chat;
    protected $message;

    protected $type;

    public function __construct($url, $proxy = null)
    {
        $this->url   = $url;
        $this->proxy = $proxy;
    }

    /**
     * @return Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return RequestType
     */
    public function getType()
    {
        return $this->type;
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

    public function getData()
    {
        if (empty($this->array_data)) {
            $data = file_get_contents('php://input');
            $this->array_data = json_decode($data, true);

            $this->parseArray();
        }
    }

    protected function parseArray()
    {
        if (key_exists('message', $this->array_data)) {
            $message_array = $this->array_data['message'];

            $chat = new Chat();
            $chat->id   = $message_array['chat']['id'];
            $chat->type = $message_array['chat']['type'];

            if (key_exists('from', $message_array)) {
                $from = new User();
                $from->id             = $message_array['from']['id'];
                $from->is_bot         = $message_array['from']['is_bot'];
                $from->first_name     = $message_array['from']['first_name'];
                $from->last_name      = $message_array['from']['last_name']     ?? null;
                $from->username       = $message_array['from']['username']      ?? null;
                $from->language_code  = $message_array['from']['language_code'] ?? null;
            }

            if (key_exists('contact', $message_array)) {
                $contact = new Contact();
                $contact->phone_number = $message_array['contact']['phone_number'];
                $contact->first_name   = $message_array['contact']['first_name'];
                $contact->last_name    = $message_array['contact']['last_name'] ?? null;
                $contact->user_id      = $message_array['contact']['user_id']   ?? null;
            }

            $message = new Message();
            $message->message_id = $message_array['message_id'];
            $message->date       = $message_array['date'];
            $message->chat       = $chat;
            if (isset($message_array['text'])) {
                $message->text = $message_array['text'];
                $message->message_type = MessageType::TEXT;
            }
            if (isset($from)) {
                $message->from = $from;
            }
            if (isset($contact)) {
                $message->contact = $contact;
                $message->message_type = MessageType::CONTACT;
            }

            $this->type    = RequestType::MESSAGE;
            $this->message = $message;
            $this->chat    = $chat;

            return;
        }
    }
}