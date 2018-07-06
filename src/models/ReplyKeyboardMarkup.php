<?php

namespace telegramBot\models;

class ReplyKeyboardMarkup
{
    /**
     * @var KeyboardButton[]|string[] Array of button rows, each represented by an Array of KeyboardButton objects
     */
    public $keyboard;
}