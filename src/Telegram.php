<?php

namespace telegramBot;

class Telegram {

    private static $_telegramServer;
    private static $url;
    private static $proxy;

    private function __construct () {}
    private function __clone() {}

    /**
     * @return TelegramServer
     */
    public static function getTelegramServer()
    {
        self::$_telegramServer = self::$_telegramServer ?? new TelegramServer(self::$url, self::$proxy);
        return self::$_telegramServer;
    }

    public static function setTelegramSettings($token, $proxy = null)
    {
        self::$url  .= $token.'/';
        self::$proxy = $proxy;
    }

}