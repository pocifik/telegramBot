<?php

namespace telegramBot\models;

use telegramBot\enums\ChatType;

class Chat
{
    /**
     * @var integer Unique identifier for this chat
     */
    public $id;

    /**
     * @var ChatType Type of chat
     */
    public $type;
}