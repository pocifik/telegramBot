<?php

namespace telegramBot;

use telegramBot\enums\MessageType;
use telegramBot\enums\RequestType;
use telegramBot\models\CallbackQuery;
use telegramBot\models\Chat;
use telegramBot\models\Contact;
use telegramBot\models\InputMediaPhoto;
use telegramBot\models\Message;
use telegramBot\models\User;

class TelegramServer
{
    protected $url;
    protected $proxy;

    protected $array_data;

    protected $chat;
    protected $message;
    protected $callback_query;

    protected $type;

    public $to_id;

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
     * @return CallbackQuery
     */
    public function getCallBackQuery()
    {
        return $this->callback_query;
    }

    /**
     * @return RequestType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getArrayData()
    {
        return $this->array_data;
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

    /**
     * @param $mediaInputs InputMediaPhoto[]
     * @return array
     */
    public function sendMediaGroup($mediaInputs)
    {
        $content['chat_id'] = $this->to_id;
        $content['media'] = json_encode($mediaInputs, true);
        return $this->sendRequest('sendMediaGroup', $content);
    }

    /**
     * @param $photo string
     * @return array
     */
    public function sendPhoto($photo)
    {
        $content['chat_id'] = $this->to_id;
        $content['photo'] = json_encode($photo, true);
        return $this->sendRequest('sendPhoto', $content);
    }

    public function getData()
    {
        if (empty($this->array_data)) {
            $data = file_get_contents('php://input');
            if (empty($data))
                return false;
            $this->array_data = json_decode($data, true);
            $this->parseArray();
        }
        return true;
    }

    protected function parseArray()
    {
        if (!is_array($this->array_data)) {
            return;
        }
        if (key_exists('message', $this->array_data)) {
            $message = $this->parseMessage($this->array_data['message']);

            $this->type    = RequestType::MESSAGE;
            $this->message = $message;
            $this->chat    = $message->chat;
            $this->to_id   = $message->chat->id;
            return;
        }
        else if (key_exists('callback_query', $this->array_data)) {
            $callback_query = $this->parseCallbackQuery($this->array_data['callback_query']);

            $this->type           = RequestType::CALLBACK_QUERY;
            $this->callback_query = $callback_query;
            $this->to_id          = $callback_query->from->id;
            return;
        }
    }

    protected function parseCallbackQuery($callback_query_array)
    {
        if (key_exists('from', $callback_query_array)) {
            $from = $this->parseUser($callback_query_array['from']);
        }
        if (key_exists('message', $callback_query_array)) {
            $message = $this->parseMessage($callback_query_array['message']);
        }

        $callback_query = new CallbackQuery();
        $callback_query->id                = $callback_query_array['id'];
        $callback_query->chat_instance     = $callback_query_array['chat_instance'];
        $callback_query->inline_message_id = $callback_query_array['inline_message_id'] ?? null;
        $callback_query->data              = $callback_query_array['data']              ?? null;
        $callback_query->game_short_name   = $callback_query_array['game_short_name']   ?? null;
        if (isset($from)) {
            $callback_query->from = $from;
        }
        if (isset($message)) {
            $callback_query->message = $message;
        }

        return $callback_query;
    }

    protected function parseChat($chat_array)
    {
        $chat = new Chat();
        $chat->id   = $chat_array['id'];
        $chat->type = $chat_array['type'];
        return $chat;
    }

    protected function parseUser($user_array)
    {
        $user = new User();
        $user->id             = $user_array['id'];
        $user->is_bot         = $user_array['is_bot'];
        $user->first_name     = $user_array['first_name'];
        $user->last_name      = $user_array['last_name']     ?? null;
        $user->username       = $user_array['username']      ?? null;
        $user->language_code  = $user_array['language_code'] ?? null;
        return $user;
    }

    protected function parseContact($contact_array)
    {
        $contact = new Contact();
        $contact->phone_number = $contact_array['phone_number'];
        $contact->first_name   = $contact_array['first_name'];
        $contact->last_name    = $contact_array['last_name'] ?? null;
        $contact->user_id      = $contact_array['user_id']   ?? null;
        return $contact;
    }

    protected function parseMessage($message_array)
    {
        $chat = $this->parseChat($message_array['chat']);

        if (key_exists('from', $message_array)) {
            $from = $this->parseUser($message_array['from']);
        }

        if (key_exists('contact', $message_array)) {
            $contact = $this->parseContact($message_array['contact']);
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
        return $message;
    }
}