<?php

@session_start();
class SettingsHTML
{
    public static function getPQCMSForm(array $userPerms, SettingsValues $settingsValues): string
    {
        $pqcmsData = $settingsValues->getPQCMSData();
        $getLogin = $userPerms["pqcms.settings.pqcms.get.username"] ? $pqcmsData->getLogin() : " ";
        $getLicenseKey = $userPerms["pqcms.settings.pqcms.get.licensekey"] ? $pqcmsData->getLicenseKey() : " ";

        $setLogin = $userPerms["pqcms.settings.pqcms.set.username"] ? "" : "disabled";
        $setLicenseKey = $userPerms["pqcms.settings.pqcms.set.licensekey"] ? "" : "disabled";

        $disabledSubmit = ($setLogin && $setLicenseKey) ? "disabled" : "";

        return<<<HTML
<form method="POST" action="update/updatePQCMS.php" class="col-3 p-4">
    <fieldset class="d-flex flex-column justify-content-center align-items-start" >
        <legend class="">PQCMS</legend>

        <input type="hidden" name="token" value="{$_SESSION["pqcms"]["panel"]["settings"]["system"]["token"]["value"]}">

        <label class="mt-1" for="user">
            Użytkownik
            <input type="text" name="username" id="user" class="d-block my-2 rounded-0" value="${getLogin}" ${setLogin} />
        </label>

        <label class="mt-1">
            Klucz licencyjny
            <input type="text" name="licensekey" class="d-block my-2 rounded-0" value="${getLicenseKey}" ${setLicenseKey}/>
        </label>

        <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Aktualizuj" ${disabledSubmit}>

    </fieldset>
</form>
HTML;
    }

    public static function getDatabaseForm(array $userPerms, SettingsValues $settingsValues): string
    {
        $databaseData = $settingsValues->getDatabaseData();
        $getHost = $userPerms["pqcms.settings.database.get.host"] ? $databaseData->getHost() : " ";
        $getUser = $userPerms["pqcms.settings.database.get.username"] ? $databaseData->getUser() : " ";
        $getPass = $userPerms["pqcms.settings.database.get.password"] ? $databaseData->getPassword() : " ";
        $getName = $userPerms["pqcms.settings.database.get.name"] ? $databaseData->getName() : " ";

        $setHost = $userPerms["pqcms.settings.database.set.host"] ? "" : "disabled";
        $setUser = $userPerms["pqcms.settings.database.set.username"] ? "" : "disabled";
        $setPass = $userPerms["pqcms.settings.database.set.password"] ? "" : "disabled";
        $setName = $userPerms["pqcms.settings.database.set.name"] ? "" : "disabled";

        $disabledSubmit = ($setHost && $setUser && $setPass && $setName) ? "disabled" : "";

        return<<<HTML
<form method="POST" action="update/updateDatabase.php" class="col-3 p-4">
    <fieldset class="d-flex flex-column justify-content-center align-items-start" >
        <legend class="">Baza Danych</legend>

        <input type="hidden" name="token" value="{$_SESSION["pqcms"]["panel"]["settings"]["database"]["token"]["value"]}">

        <label class="mt-1">
            Host
            <input type="text" name="host" class="d-block my-2 rounded-0" value="${getHost}" ${setHost}/>
        </label>
        
        <label class="mt-1">
            Użytkownik
            <input type="text" name="username" class="d-block my-2 rounded-0" value="${getUser}" ${setUser}/>
        </label>
        
        <label class="mt-1">
            Hasło
            <input type="text" name="password" class="d-block my-2 rounded-0" value="${getPass}" ${setPass}/>
        </label>

        <label class="mt-1">
            Nazwa
            <input type="text" name="name" class="d-block my-2 rounded-0" value="${getName}" ${setName}/>
        </label>

        <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Aktualizuj" ${disabledSubmit}>

    </fieldset>
</form>
HTML;
    }

    public static function getSystemForm(array $userPerms, array $communicatorGetSettings): string
    {
        $html = self::getSystemHTML($userPerms, $communicatorGetSettings);
        return<<<HTML
<form method="POST" action="update/updateSystem.php" class="col-3 p-4">
    <fieldset class="d-flex flex-column justify-content-center align-items-start" >
        <legend class="">System</legend>
        ${html}       
    </fieldset>
</form>
HTML;
    }

    private static function getSystemHTML(array $userPerms, array $communicatorGetSettings): string
    {
        if($communicatorGetSettings["suc"] == 0)
            return <<<HTML
<div class="error">
    <p>
        Brak połączenia z serwerem. Czy na pewno wpisane dane systemowe (Ustawienia -> PQCMS) są dobre?
    </p>
    <p>
        Jeśli uważasz, że problem jest po naszej stronie, skontaktuj się z adminsitratorem PQCMS!
    </p>
    Opis: {$communicatorGetSettings["desc"]}
</div>
HTML;
        else
        {
            $systemData = $communicatorGetSettings["resp"];
            $getAttempts = $userPerms["pqcms.settings.system.get.loginattempts"] ? $systemData["login_attempts"] : " ";
            $getLoginSessionTime = $userPerms["pqcms.settings.system.get.loginsessiontime"] ? $systemData["login_session_time"] : " ";

            $setAttempts = $userPerms["pqcms.settings.system.set.loginattempts"] ? "" : "disabled";
            $setLoginSessionTime = $userPerms["pqcms.settings.system.set.loginsessiontime"] ? "" : "disabled";

            $disabledSubmit = ($setAttempts && $setLoginSessionTime) ? "disabled" : "";

            return<<<HTML
<input type="hidden" name="token" value="{$_SESSION["pqcms"]["panel"]["settings"]["settings"]["token"]["value"]}">

<label for="login_count" class="mt-1">Ilość logowań na dobę (z 1 IP)</label>
<input type="number" name="login_count" id="login_count" class="my-2 rounded-0" placeholder="czas w sekundach" value="${getAttempts}" ${setAttempts}/>

<label class="disableSelect">
    <input type="checkbox" name="login_count_reset" class="my-2 rounded-0" placeholder="czas w sekundach" value="" ${setAttempts}/>
    Reset
</label>

<label for="login_session_time" class="mt-1">Sesja użytkownika</label>
<i>Czas, przez jaki sesja użytkownika będzie ważna na serwerach PQCMS</i>
<input type="number" name="login_session_time" id="login_session_time" class="my-2 rounded-0" placeholder="czas w sekundach" value="${getLoginSessionTime}" ${setLoginSessionTime}/>

<label class="disableSelect">
    <input type="checkbox" name="login_session_time_reset" class="my-2 rounded-0" ${setLoginSessionTime}/>
    Reset
</label>

<input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Aktualizuj" ${disabledSubmit}>
HTML;
        }
    }
}