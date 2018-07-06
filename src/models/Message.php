<?php

namespace telegramBot\models;

use telegramBot\Telegram;

class Message
{
    /**
     * @var integer Unique message identifier inside this chat
     */
    public $message_id;
    /**
     * @var string Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters.
     */
    public $text;
    /**
     * @var Chat Conversation the message belongs to
     */
    public $chat;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function send()
    {
        $telegramServer = Telegram::getTelegramServer();
        $content = [
            'chat_id' => $this->chat->id,
            'text' => $this->text
        ];
        $telegramServer->sendRequest('sendMessage', $content);
    }
}