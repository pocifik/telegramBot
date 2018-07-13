<?php

namespace telegramBot\models;

class ReplyKeyboardMarkup
{
    /**
     * @var KeyboardButton[]|string[] Array of button rows, each represented by an Array of KeyboardButton objects
     */
    public $keyboard;
    /**
     * @var boolean Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons).
     */
    public $resize_keyboard= false;
    /**
     * @var boolean Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat – the user can press a special button in the input field to see the custom keyboard again. Defaults to false.
     */
    public $one_time_keyboard = false;
    /**
     * @var boolean Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     */
    public $selective = false;
}