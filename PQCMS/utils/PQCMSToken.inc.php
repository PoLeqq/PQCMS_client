<?php

require_once(dirname(__DIR__)."/panel/scripts/notifications/NotificationManager.inc.php");
require_once("PQCMSLocalSettings.inc.php");
$localSettings = new PQCMSLocalSettings();

class PQCMSToken
{
    public static function generateToken(): array
    {
        global $localSettings;
        return [
            "value" => bin2hex(random_bytes(64)),
            "expire" => time() + $localSettings->getTokenExpireTime()
        ];
    }

    public static function verifyToken(NotificationManager $notificationManager, ?array &$generatedToken, string $postToken): void
    {
        if(empty($generatedToken))
            self::endScript($notificationManager,"e","Nie wygenerowano tokenu! Czy próbujesz przesłać dane nie przez formularz PQCMS?");

        if(empty($postToken) || $postToken != $generatedToken["value"])
            self::endScript($notificationManager,"e","Walidacja tokenu nie powiodła się.");

        if(time() >= $generatedToken["expire"])
            self::endScript($notificationManager,"e","Token jest przestarzały. Przeładuj stronę!");

        $generatedToken = null;
    }

    private static function endScript(NotificationManager $notificationManager, string $notificationType, string $message): void
    {
        $notificationManager->addNotification($notificationType,$message);
        header("location: ../");
        die("$message Niepoprawne przekierowanie.");
    }
}