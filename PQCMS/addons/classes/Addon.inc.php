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
    /**
     * @var AddonPanel panel dodatku
     */
    protected AddonPanel $panel;
    /**
     * @var ?AddonWebsite obiekt zmiany strony
     */
    protected ?AddonWebsite $websiteAddon;

    /**
     * @param string $id id (a-z; tylko małe!)
     * @throws Exception gdy id nie spełnia wymagań
     */
    public function __construct(string $id, AddonPanel $panel, ?AddonWebsite $websiteAddon)
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
            if(!file_put_contents($configPath, json_encode($addonData["default_config"],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)))
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
        $this->websiteAddon = $websiteAddon;
    }

    /**
     * Zwraca ścieżkę od otwartego panelu dodatku, która kieruje na ścieżkę folderu dodatku (do użytku zewnętrznego)
     * @return string
     */
    public function getRelativePathFromPanelToAddon(): string
    {
        return "../../addons/addons/".$this->id;
    }

    /**
     * Zwraca ścieżkę od folderu dodatku do panelu dodatku (do użytku zewnętrznego)
     * @return string
     */
    public function getRelativePathFromAddonToPanel(): string
    {
        return "../../../panel/addons/addonPanel.php?addon=".$this->id;
    }

    /**
     * Zwraca ścieżkę do głównego folderu dodatku (do użytku wewnętrznego)
     * @return string
     */
    public function getAddonPath(): string
    {
        return dirname(__DIR__)."/addons/".$this->id."/";
    }

    /**
     * Zwraca ścieżkę od strony (głównego katalogu, zazwyczaj public_html) do dodatku
     * @param int $folderDepth ilość folderów (np., gdy chcemy wykorzystać tę funkcję z zakładki "/a/" - 1; "/a/b/c/" - 3)
     * @return string
     */
    public function getRelativePathFromWebsiteToAddon(int $folderDepth): string {
        $path = str_repeat("../",$folderDepth);

        $id = $this->id;
        return "${path}pqcms/addons/addons/$id";
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

    /**
     * Pobiera dane z pliku konfiguracyjnego. Uwaga! Te dane nie są polem, lecz odczytywane bezpośrednio z pliku.
     * Zaleca się używanie tej funkcji na poziomie minimalnym, aby zaoszczędzić zasobów oraz czasu ładowania.
     * @throws Exception błąd odczytu pliku
     * @return array dane pliku konfiguracyjnego
     */
    public function getConfig(): array
    {
        $file = file_get_contents($this->configPath);
        if(!$file)
            throw new Exception("File \"config.json\" not found.");
        $config = json_decode($file,true);
        if(!$config)
            throw new Exception("Error while parsing \"config.json\".");
        return $config;
    }

    /**
     * Resetuje plik konfiguracyjny
     * @return bool czy reset przebiegł pomyślnie
     */
    public function resetConfig(): bool
    {
        return $this->setConfig($this->defaultConfig);
    }

    public function setConfig(array $config): bool
    {
        return file_put_contents($this->configPath, json_encode($config,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
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

    public function getWebsiteAddon(): ?AddonWebsite
    {
        return $this->websiteAddon;
    }

    public function getIcon(): ?string
    {
        if(file_exists($this->getAddonPath()."icon.png"))
            return $this->getAddonPath()."icon.png";
        return null;
    }

    /**
     * @param bool $enabled czy dodatek jest włączony
     * @param bool $hasPerms czy użytkownik ma dostęp do dodatku
     * @param string $token token, który blokuje XSS
     * @return string html, który pokazywany jest na stronie w liście dodatków
     */
    public final function getViewHTML(bool $enabled, bool $hasPerms, string $token): string
    {
        $checked = $enabled ? "checked" : "";

        $id = $this->getId();
        $name = $this->getName();
        $desc = $this->getDescription();
        $ver = $this->getVersion()->getFullVersion(true);
        $icon = is_null($this->getIcon()) ? <<<HTML
<img src="../../images/PQCMS.svg" alt='Ikona' style="width: clamp(50px, 6vw, 70px); height: clamp(50px, 6vw, 70px)">
HTML : <<<HTML
<img src="../../addons/addons/$id/icon.png" alt='Ikona' style="width: clamp(50px, 6vw, 70px); height: clamp(50px, 6vw, 70px)">
HTML;

        $panelLink = ($hasPerms) ? <<<HTML
<a href="addonPanel.php?addon=$id" class="btn btn-primary col-8">Panel</a>
HTML : <<<HTML
<div class="btn btn-primary col-8 addon-disabled">Panel</div>
HTML;

        $disabledAttr = !$hasPerms ? "disabled" : "";

        return<<<HTML
<div class="col-12 col-lg-6 col-xl-4 rounded-3 p-3">
    <div class="p-5 border border-2 addon d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex align-items-start justify-content-between">
                <header class="h2" style="margin-top: clamp(5px, 2vw, 20px)" >$name</header>
                <div class="border border-2 border-dark p-1" style="background-color: #f1f1f1">
                    $icon        
                </div>
            </div>
            <div class="my-3">
                $desc    
            </div>
            <div class="my-3">
                <b>Wersja:</b> $ver
            </div>        
        </div>
        <div class="d-flex align-items-center justify-content-between">
            $panelLink
            <div class="col-4 d-flex justify-content-end">
                <label class="addon-toggler switch" data-addon="$id" data-token="$token">
                  <input type="checkbox" $checked $disabledAttr>
                  <span class="addon-toggler-span slider round"></span>
                </label>
            </div>
        </div>      
    </div>
</div>
HTML;

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

    public static function getExecutedPhpFileContent(string $filePath): bool|string
    {
        ob_start();
        include $filePath;
        return ob_get_clean();
    }
}