<?php

namespace telegramBot\models;

class ReplyKeyboardMarkup
{
    /**
     * @var KeyboardButton[]|string[] Array of button rows, each represented by an Array of KeyboardButton objects
     */
    public $keyboard;
    /**
     * @var boolean True, if this user is a bot
     */
    public $is_bot;
    /**
     * @var string User‘s or bot’s first name
     */
    public $first_name;
    /**
     * @var string Optional. User‘s or bot’s last name
     */
    public $last_name;
    /**
     * @var string Optional. User‘s or bot’s username
     */
    public $username;
    /**
     * @var string Optional. IETF language tag of the user's language
     */
    public $language_code;
}