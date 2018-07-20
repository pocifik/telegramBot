<?php

namespace telegramBot\models;

class ReplyKeyboardRemove
{
    /**
     * @var true Requests clients to remove the custom keyboard
     */
    private $remove_keyboard = true;
    /**
     * @var boolean Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     */
    public $selective = false;
}