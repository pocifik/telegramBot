<?php

namespace telegramBot\models;

class CallbackQuery
{
    /**
     * @var integer Unique identifier for this chat
     */
    public $id;
    /**
     * @var User Sender
     */
    public $from;
    /**
     * @var Message Optional. Message with the callback button that originated the query. Note that message content and message date will not be available if the message is too old
     */
    public $message;
    /**
     * @var string Optional. Identifier of the message sent via the bot in inline mode, that originated the query.
     */
    public $inline_message_id;
    /**
     * @var string Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent. Useful for high scores in games.
     */
    public $chat_instance;
    /**
     * @var string Optional. Data associated with the callback button. Be aware that a bad client can send arbitrary data in this field.
     */
    public $data;
    /**
     * @var string Optional. Short name of a Game to be returned, serves as the unique identifier for the game
     */
    public $game_short_name;
}