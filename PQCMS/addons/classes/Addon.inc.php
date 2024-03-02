<?php

require("AddonVersion.inc.php");
require("AddonPanel.inc.php");

/**
 * Klasa reprezentująca dodatek
 */
abstract class Addon
{
    /**
     * @var string ścieżka do pliku konfiguracyjnego
     */
    private string $configPath;

    /**
     * @var string id dodatku (a-z; tylko małe!)
     */
    protected string $id;
    /**
     * @var string nazwa
     */
    protected string $name;
    /**
     * @var string opis
     */
    protected string $description;
    /**
     * @var AddonVersion wersja
     */
    protected AddonVersion $version;
    /**
     * @var string autor
     */
    protected string $author;
    /**
     * @var array dane konfiguracyjne
     */
    protected array $config;
    /**
     * @var array domyślne dane konfiguracyjne
     */
    protected array $defaultConfig;
    /**
     * @var array uprawnienia, które wykorzystuje dodatek (tylko końcówka, ponieważ początek jest zawsze "pqcms.addon.(nazwa).")
     */
    protected array $permissions;
    protected AddonPanel $panel;

    /**
     * @param string $id id (a-z; tylko małe!)
     * @throws Exception gdy id nie spełnia wymagań
     */
    public function __construct(string $id, AddonPanel $panel)
    {
        if(!preg_match('/^[a-z]+$/', $id))
            throw new Exception("Field \"id\" may only contains a-z letters (lowercase)!");

        $this->id = $id;

        $addonPath = $this->getAddonPath()."config/addon.json";
        if(!file_exists($addonPath))
            throw new Exception("File \"addon.json\" not found.");

        $addonData = self::validateAddon($addonPath);

        $configPath = $this->getAddonPath()."config/config.json";
        if(!file_exists($configPath))
        {
            if(!file_put_contents($configPath, json_encode($addonData["default_config"],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)))
                throw new Exception("Error while creating new \"config.json\" file.");
        }
        $this->configPath = $this->getAddonPath()."config/config.json";

        $this->name = $addonData["name"];
        $this->description = $addonData["description"];
        $this->version = AddonVersion::unserialize($addonData["version"]);
        $this->author = $addonData["author"];
        $this->defaultConfig = $addonData["default_config"];
        $this->permissions = $addonData["permissions"];
        $this->panel = $panel;
    }

    public function getAddonPath(): string
    {
        return dirname(__DIR__)."/addons/".$this->id."/";
    }

    public function getConfigPath(): string
    {
        return $this->configPath;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVersion(): AddonVersion
    {
        return $this->version;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getDefaultConfig(): array
    {
        return $this->defaultConfig;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getPanel(): AddonPanel
    {
        return $this->panel;
    }

    public function getIcon(): ?string
    {
        if(file_exists($this->getAddonPath()."icon.png"))
            return $this->getAddonPath()."icon.png";
        return null;
    }

    /**
     * @param array $fileContent zawartość pliku addon.json (jako tablica asocjacyjna)
     * @return array klucz "suc" ma wartość true dla poprawnego wyniku,
     * 0 dla niepoprawnego (dodatkowo klucz "desc" zawierający opis niepowodzenia)
     */
    private static function validateAddonFile(array $fileContent): array
    {
        $keys = [
            "id" => false,
            "name" => false,
            "main" => false,
            "description" => false,
            "version" => false,
            "author" => false,
            "default_config" => false,
            "permissions" => false
        ];

        foreach($fileContent as $key => $value)
            $keys[$key] = true;

        foreach($keys as $key => $value)
            if(!$value)
                return ["suc" => 0, "desc" => "Required key \"$key\" not found in addon.json"];

        return ["suc" => 1];
    }

    /**
     * Funkcja odczytuje plik addon.json
     * @throws Exception
     * @return array gdy walidacja pliku powiodła się, zwracane są dane dodatku
     */
    public static function validateAddon($addonFilePath): array
    {
        $addonDataContents = file_get_contents($addonFilePath);
        if(!$addonDataContents)
            throw new Exception("Error while reading \"addon.json\" file.");
        $addonData = json_decode($addonDataContents,true);
        if(is_null($addonData))
            throw new Exception("Error while parsing \"addon.json\" file.");

        $addonDataCheck = self::validateAddonFile($addonData);
        if($addonDataCheck["suc"] === 0)
            throw new Exception($addonDataCheck["desc"]);

        return $addonData;
    }

    /**
     * @throws Exception
     */
    public static function getAddonMainClassByAddonFile(string $addonFilePath): Addon
    {
        $data = self::validateAddon($addonFilePath);
        require_once(dirname(__DIR__)."/addons/${data["id"]}/${data["main"]}");
        return new (basename($data["main"],".inc.php"))();
    }
}