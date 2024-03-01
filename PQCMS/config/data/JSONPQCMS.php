<?php

require_once(dirname(__DIR__)."/JSONObject.php");

/**
 * Przedstawia dział "pqcms" w pqcms/config/files/config.json - dane o systemie
 */
class JSONPQCMS extends JSONObject {
    function __construct() {
        parent::__construct("pqcms","/files/data.json");
    }

    function getDomain(): string
    {
        return $this->getObject("domain");
    }

    /**
     * Zwraca wersję systemu
     */
    function getVersion(): string
    {
        return $this->getObject("version");
    }

    function getVersionDate(): string
    {
        return $this->getObject("version_date");
    }

    /**
     * Zmienia wersję systemu
     */
    function setVersion(string $version): void
    {
        $this->setObject("version",$version);
    }

    function getComplexVersion(): array
    {
        $complexVersion = [];

        $versionString = $this->getVersion();

        $parts = explode('-', $versionString);
        $complexVersion["type"] = $parts[0];

        $versionNumbers = explode('.', $parts[1]);

        $complexVersion["major"] = $versionNumbers[0];
        $complexVersion["minor"] = $versionNumbers[1];
        $complexVersion["patch"] = $versionNumbers[2];

        return $complexVersion;
    }


    /**
     * Zwraca nazwę użytkownika (systemowego)
     */
    function getLogin(): string
    {
        return $this->getObject("login");
    }

    /**
     * Zmienia nazwę użytkownika (systemowego)
     * UWAGA!!! Aby zapisać do pliku, trzeba użyć funkcji saveData()
     */
    function setLogin(string $login): void
    {
        $this->setObject("login",$login);
    }

    /**
     * Zwraca klucz licencyjny (systemowy)
     */
    function getLicenseKey(): string
    {
        return $this->getObject("license_key");
    }

    /**
     * Sprawdza, czy podany napis jest prawidłowy (według schematu: xxxx-xxxx-xxxx-xxxx, gdzie x - liczba lub duża litera)
     *
     * @param  string $licenseKey klucz licencyjny
     * @return bool czy poprawny
     */
    function isProperLicenseKey(string $licenseKey): bool
    {
        $pattern = '/^[0-9A-Z]{5}-[0-9A-Z]{5}-[0-9A-Z]{5}-[0-9A-Z]{5}$/';
        return preg_match($pattern, $licenseKey, $sth);
    }

    /**
     * Zmienia klucz licencyjny (systemowy)
     * UWAGA!!! Aby zapisać do pliku, trzeba użyć funkcji saveData()
     *
     * @param  string $licenseKey klucz licencyjny
     * @return bool czy zmieniono
     */
    function setLicenseKey(string $licenseKey): bool
    {
        if(!$this->isProperLicenseKey($licenseKey))
            return false;
        $this->setObject("license_key",$licenseKey);
        return true;
    }
}