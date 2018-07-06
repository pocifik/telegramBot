<?php

namespace telegramBot\models;

class KeyboardButton
{
    /**
     * @var string Text of the button. If none of the optional fields are used, it will be sent as a message when the button is pressed
     */
    public $text;
    /**
     * @var boolean Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
     */
    public $request_contact;
    /**
     * @var boolean Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
     */
    public $request_location;
}