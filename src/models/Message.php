<?php

namespace telegramBot\models;

use telegramBot\enums\MessageType;
use telegramBot\Telegram;

class Message
{
    /**
     * @var integer Unique message identifier inside this chat
     */
    public $message_id;
    /**
     * @var User Optional. Sender, empty for messages sent to channels
     */
    public $from;
    /**
     * @var integer Date the message was sent in Unix time
     */
    public $date;
    /**
     * @var Chat Conversation the message belongs to
     */
    public $chat;
    /**
     * @var string Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters
     */
    public $text;
    /**
     * @var Contact Optional. Message is a shared contact, information about the contact
     */
    public $contact;

    /**
     * @var MessageType Type of Message.
     */
    public $message_type;

    /**
     * @param $text string
     * @param $parse_mode string
     */
    public function sendMessage($text = null, $parse_mode = null)
    {
        $this->text = $text ?? $this->text;

        $telegramServer = Telegram::getTelegramServer();

        $content['chat_id'] = $this->chat->id ?? $telegramServer->getChat()->id;
        $content['text']    = $this->text;
        if (!is_null($parse_mode))
            $content['parse_mod'] = $parse_mode;
        $telegramServer->sendRequest('sendMessage', $content);
    }

    /**
     * @param $reply_markup ReplyKeyboardMarkup
     * @param $text string string
     * @param $parse_mode string
     */
    public function sendKeyboard($reply_markup, $text = null, $parse_mode = null)
    {
        $this->text = $text ?? $this->text;

        $telegramServer = Telegram::getTelegramServer();

        $content['chat_id']      = $this->chat->id ?? $telegramServer->getChat()->id;
        $content['text']         = $this->text;
        $content['reply_markup'] = json_encode($reply_markup, true);
        if (!is_null($parse_mode))
            $content['parse_mod'] = $parse_mode;

        $telegramServer->sendRequest('sendMessage', $content);
    }
}