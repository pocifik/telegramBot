<?php

namespace telegramBot\models;

class User
{
    /**
     * @var integer Unique identifier for this user or bot
     */
    public $id;
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