<?php

/**
 * Reprezentuje komunikator client server - cms server
 */
class Communicator
{
    /**
     *
     * @param string $path ścieżka linku do API (Najlepiej skorzystać z CommunicateURL)
     * @param array $postData dane, które zostaną przesłane metodą POST. (podczas VERIFY_LICENSE przesłać pustą)
     * @return mixed zwraca return (json) z danego APIka (lub array z kluczami "suc" i "desc", gdy połączenie nie powiedzie się)
     */
    public static function communicate(string $path, array $postData = []): array
    {
//        $ch = curl_init();
//
//        curl_setopt($ch,CURLOPT_URL,$url);
//        curl_setopt($ch,CURLOPT_POST,count($fields));
//        curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//
//        $result = curl_exec($ch);
//
//        curl_close($ch);
//
//        echo $result;
        if($path == CommunicateURL::VERIFY_LICENSE)
        {
            require_once("config/data/JSONPQCMS.php");
            $pqcms = new JSONPQCMS();
            $postData = array(
                'domain' => $pqcms->getDomain(),
                'login' => $pqcms->getLogin(),
                'license_key' => $pqcms->getLicenseKey(),
                'generate_secure_key' => $postData["generate_secure_key"] ?? null
            );
        }
        else
        {
            if(!in_array($path,CommunicateURL::getUnrequiredLoginSession()))
            {
                @session_start();
                if(empty($_SESSION["pqcms-panel-auth_key"]))
                    return ["suc" => 0, "desc" => "Akcja niemożliwa do spełnienia. Nie posiadasz aktywnej sesji!"];
                else $postData["auth_key"] = $_SESSION["pqcms-panel-auth_key"];
            }

            $postData["domain"] = $_SERVER["SERVER_NAME"];

            $key = Communicator::communicate(CommunicateURL::VERIFY_LICENSE,["generate_secure_key" => true]);
            if($key["suc"] == 0)
                return["suc" => 0, "desc" => "Błąd podczas generowania klucza zabezpieczającego: ".$key["desc"]];
            $postData["secure_key"] = $key["secure_key"];
        }

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Referer: https://${_SERVER["SERVER_NAME"]}\r\n",
                'method'  => 'POST',
                'content' => http_build_query($postData)
            )
        );

        $targetUrl = 'https://poleq.pl/server/api/'.$path;

        $context = stream_context_create($options);

        try { @$response = file_get_contents($targetUrl, false, $context); }
        catch(Exception)
        {
            return ["suc" => 0, "desc" => "Nieznany błąd podczas komunikacji z serwerami PQCMS. Skontaktuj się z administratorem PQCMS!"];
        }

//        var_dump($response);

        if($response === false)
            return ["suc" => 0, "desc" => "Błąd funkcji file_get_contents podczas komunikacji z serwerami PQCMS. Skontaktuj się z administratorem PQCMS!"];
        else
            return json_decode($response,true);
    }
}

class CommunicateURL
{
    public const VERIFY_LICENSE = "website/license/VerifyLicense.php";
    public const GET_CLIENT_VERSION = "system/version/GetClientVersion.php";
    public const GET_SERVER_VERSION = "system/version/GetServerVersion.php";
    public const DOES_ADMIN_EXISTS = "website/hr/admin/DoesAdminExists.php";
    public const ADD_USER = "website/hr/user/AddUser.php";
    public const GET_USER = "website/hr/user/GetUser.php";
    public const LOGIN_USER = "website/auth/LoginUser.php";
    public const LOGOUT_USER = "website/auth/LogoutUser.php";
    public const IS_VALID_AUTH_KEY = "website/auth/IsValidAuthKey.php";
    public const HAS_PERMISSION = "website/perms/HasPermission.php";
    public const GET_SETTINGS = "website/settings/GetSettings.php";
    public const UPDATE_SETTINGS = "website/settings/UpdateSettings.php";

    /**
     * Funkcja zwracająca listę APIków, które dozwolone są do wykonania bez logowania do panelu
     * @return array ścieżki APIków
     */
    public static function getUnrequiredLoginSession(): array {
        return [self::VERIFY_LICENSE,self::DOES_ADMIN_EXISTS,self::LOGIN_USER];
    }
}