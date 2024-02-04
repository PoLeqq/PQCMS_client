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
        $postData["client_ip"] = $_SERVER["REMOTE_ADDR"];

        if($path == CommunicateURL::VERIFY_LICENSE)
        {
            require_once("config/data/JSONPQCMS.php");
            $pqcms = new JSONPQCMS();
            $postData = array(
                'domain' => $pqcms->getDomain(),
                'login' => $pqcms->getLogin(),
                'license_key' => $pqcms->getLicenseKey(),
                'client_ip' => $postData["client_ip"] = $_SERVER["REMOTE_ADDR"]
            );
        }
        else if($path === CommunicateURL::LOGIN_USER && !empty($_SESSION["pqcms"]["panel"]["auth_key"]["value"]))
        {
            return ["suc" => 0, "desc" => "Komunikacja z serwerami PQCMS nie nastąpiła, ponieważ posiadasz już aktywną sesję!"];
        }
        else
        {
            @session_start();
            if(!in_array($path,CommunicateURL::getUnrequiredLoginSession()))
            {
                if(empty($_SESSION["pqcms"]["panel"]["auth_key"]["value"]))
                    return ["suc" => 0, "desc" => "Akcja niemożliwa do spełnienia. Nie posiadasz aktywnej sesji!"];
                else
                {
                    if(!isset($postData["auth_key"]))
                        $postData["auth_key"] = $_SESSION["pqcms"]["panel"]["auth_key"]["value"];
                }
            }

            $postData["domain"] = self::getDomain($_SERVER["SERVER_NAME"]);


            date_default_timezone_set("Europe/Warsaw");
            if(empty($_SESSION["pqcms"]["secure_key"]) ||
                empty($_SESSION["pqcms"]["secure_key"]["expiry_time"]) ||
                $_SESSION["pqcms"]["secure_key"]["expiry_time"] < time())
            {
                $key = Communicator::communicate(CommunicateURL::VERIFY_LICENSE);
                if($key["suc"] == 0)
                    return["suc" => 0, "desc" => "Błąd podczas generowania klucza zabezpieczającego: ".$key["desc"]];
                if(empty($key["secure_key"]))
                    return ["suc" => 0, "desc" => "Serwer PQCMS nie zwrócił klucza zabezpieczającego. Trzeba odczekać 🤷‍"];

                $_SESSION["pqcms"]["secure_key"]["value"] = $key["secure_key"]["value"];
                $_SESSION["pqcms"]["secure_key"]["expiry_time"] = $key["secure_key"]["expire_time"];
            }
            $postData["secure_key"] = $_SESSION["pqcms"]["secure_key"]["value"];
        }

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Referer: https://${_SERVER["SERVER_NAME"]}\r\n",
                'method'  => 'POST',
//                'content' => http_build_query($postData,'','&')
//                'content' => http_build_query($postData)
                'content' => http_build_query(array_map(function ($value)
                    {
                        if(is_array($value) && empty($value))
                            return '';
                        else
                            return $value;
                    }, $postData))
            )
        );

        $targetUrl = 'https://poleq.pl/server/api/'.$path;
//        $targetUrl = 'http://localhost/pqcms/server/api/'.$path;

        $context = stream_context_create($options);

        try { @$response = file_get_contents($targetUrl, false, $context); }
        catch(Exception) {
            return ["suc" => 0, "desc" => "Nieznany błąd podczas komunikacji z serwerami PQCMS. Skontaktuj się z administratorem PQCMS!"];
        }

        if($response === false)
            return ["suc" => 0, "desc" => "Błąd funkcji file_get_contents podczas komunikacji z serwerami PQCMS. Skontaktuj się z administratorem PQCMS!"];
        else
        {
            $ret = json_decode($response,true);
            if(is_null($ret))
                return ["suc" => 0, "desc" => "Otrzymano niepoprawną odpowiedź!", "todo_remove_debug_response" => $response];
            if($path === CommunicateURL::VERIFY_LICENSE && !empty($ret["secure_key"]))
            {
                $_SESSION["pqcms"]["secure_key"]["value"] = $ret["secure_key"]["value"];
                $_SESSION["pqcms"]["secure_key"]["expiry_time"] = $ret["secure_key"]["expire_time"];
            }
            else if($path === CommunicateURL::GET_PERMS && !empty($ret["resp"]))
            {
                require_once("utils/perms/LocalPermissions.inc.php");
                $ret["resp"] = array_merge($ret["resp"], LocalPermissions::getLocalWebsitePermissions());
                $perm = array_column($ret["resp"], 'perm');
                array_multisort($perm, SORT_ASC, $ret["resp"]);
            }
            return $ret;
        }
    }

    private static function getDomain(string $server_name): string
    {
        if(ip2long($_SERVER['HTTP_HOST']))
        {
            require_once("config/data/JSONPQCMS.php");
            $pqcms = new JSONPQCMS();
            return $pqcms->getDomain();
        }
        else
            return $server_name;
    }
}

class CommunicateURL
{
    public const VERIFY_LICENSE = "website/license/VerifyLicense.php";
    public const PLAIN_VERIFY_LICENSE = "website/license/VerifyLicense.php";
    public const GET_CLIENT_VERSION = "system/version/GetClientVersion.php";
    public const GET_SERVER_VERSION = "system/version/GetServerVersion.php";
    public const DOES_ADMIN_EXISTS = "website/hr/admin/DoesAdminExists.php";
    public const ADD_USER = "website/hr/user/AddUser.php";
    public const GET_USER = "website/hr/user/GetUser.php";
    public const EDIT_USER = "website/hr/user/EditUser.php";
    public const RESET_PASSWORD = "website/hr/user/ResetPassword.php";
    public const DELETE_USER = "website/hr/user/DeleteUser.php";
    public const INVALIDATE_SESSION = "website/hr/user/InvalidateSession.php";
    public const ADD_RANK = "website/hr/rank/AddRank.php";
    public const DELETE_RANK = "website/hr/rank/DeleteRank.php";
    public const GET_RANK = "website/hr/rank/GetRank.php";
    public const EDIT_RANK = "website/hr/rank/EditRank.php";
    public const LOGIN_USER = "website/auth/LoginUser.php";
    public const LOGOUT_USER = "website/auth/LogoutUser.php";
    public const GET_LICENSE_EXPIRATION = "website/data/GetLicenseExpiration.php";
    public const IS_VALID_AUTH_KEY = "website/auth/IsValidAuthKey.php";
    public const HAS_PERMISSION = "website/perms/HasPermission.php";
    public const IS_PERMISSION_SET = "website/perms/IsPermissionSet.php";
    public const PLAIN_GET_PERMS = "website/perms/GetPerms.php";
    public const GET_PERMS = "website/perms/GetPerms.php";
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