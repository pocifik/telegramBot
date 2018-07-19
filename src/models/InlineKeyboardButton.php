<?php

namespace telegramBot\models;

class InlineKeyboardButton
{
    /**
     * @var string Text of the button. If none of the optional fields are used, it will be sent as a message when the button is pressed
     */
    public $text;
    /**
     * @var string Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
     */
    public $callback_data;
}