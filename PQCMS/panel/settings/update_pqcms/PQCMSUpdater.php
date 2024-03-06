<?php

require_once(dirname(__DIR__,3)."/Communicator.inc.php");
class PQCMSUpdater
{
    private array $versionTypes;

    public function __construct(array $versionTypes = ["r","p"])
    {
        $this->versionTypes = $versionTypes;
    }

    private function getNewUpdate(): array|string|null
    {
        $newVersion = Communicator::communicate(CommunicateURL::GET_NEWER_VERSION,["types" => $this->versionTypes]);
        if($newVersion["suc"] == 0)
            return $newVersion["desc"];
        if(empty($newVersion["version"]))
            return null;
        return $newVersion["version"];
    }

    private function getFullHTML($body): string
    {
        require_once(dirname(__DIR__,3)."/config/data/JSONPQCMS.php");
        $pqcms = new JSONPQCMS();
        $version = $pqcms->getVersion();
        $versionDate = $pqcms->getVersionDate();

        return <<<HTML
<div id="pqcms-update-section" class="my-5  d-flex flex-column gap-1">
    <h4>Aktualizacja systemu</h4>
    <div>
        <b>Wersja:</b> $version ($versionDate)
    </div>
    $body
</div>
HTML;
    }

    private function getUpdateDescriptionHTML(array $version): string
    {
        $versionName = "${version["type_name"]}-${version["major"]}.${version["minor"]}.${version["patch"]}";
        return<<<HTML
<div id="pqcms-update-description" class="d-flex flex-column mb-2">
    <span>
        <b>Wersja:</b> $versionName
    </span>
    <span>
        <b>Data wersji:</b> ${version["date"]}
    </span>
    <span>
        <b>Rozmiar:</b> ${version["size"]}
    </span>
    <span>
        <b>Opis:</b> ${version["description"]}
    </span>
</div>
HTML;
    }

    public function getNewUpdateHTML():string
    {
        $update = $this->getNewUpdate();

        if(is_null($update))
            return $this->getFullHTML(<<<HTML
<b style="color: limegreen">
    Posiadasz najnowszą wersję klienta PQCMS!
</b>
HTML);
        if(is_string($update))
            return<<<HTML
<div style="color: red">
    $update
</div>
HTML;
        else
        {
            $info = $this->getUpdateDescriptionHTML($update);
            return $this->getFullHTML(<<<HTML
<div class="mt-4">
<h5 style="color: #008300">Nowa aktualizacja!</h5>
$info
<div>
    <button class="btn-primary btn rounded-0" id="pqcms-update-button">
        Aktualizuj!
    </button>
</div>
</div>
HTML
            );
        }
    }

    public function update(): bool
    {
        @session_start();
        require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");
        $notificationManagerError = new NotificationManager("settings-updateDatabase","Ustawienia - Baza danych");
        require_once(dirname(__DIR__,3)."/utils/PQCMSToken.inc.php");
        PQCMSToken::verifyToken($notificationManagerError,$_SESSION["pqcms"]["panel"]["settings"]["systemupdate"]["token"], $_POST["token"]);

        $notTitle = "Aktualizacja systemu";

        $update = $this->getNewUpdate();
        if(is_null($update))
            return false;

        $versionStringified = "${update["major"]}.${update["minor"]}.${update["patch"]}";
        $filename = "${update["type_name"]}-$versionStringified.";
        $path = "https://poleq.pl/server/updates/${update["type_name"]}/$filename";

        $zipContent = file_get_contents($path."zip");
        if($zipContent === false)
        {
            NotificationManager::addNewNotification("pqcms-update", $notTitle, "e", "Nie udało się pobrać aktualizacji! (${path}zip)");
            return false;
        }

        if(file_put_contents(__DIR__."/${filename}zip", $zipContent) === false)
        {
            NotificationManager::addNewNotification("pqcms-update",$notTitle,"e","Nie udało się zapisać aktualizacji! Plik najprawdopodobniej nie ma wystarczających uprawnień.");
            return false;
        }

        $zipArchive = new ZipArchive();
        $result = $zipArchive->open("${filename}zip");
        if ($result === false)
        {
            NotificationManager::addNewNotification("pqcms-update", $notTitle, "e", "Błąd podczas przetwarzania aktualizacji!");
            return false;
        }

        if($zipArchive->extractTo(dirname(__DIR__,3)))
            NotificationManager::addNewNotification("pqcms-update",$notTitle,"s","Pomyślnie zaaktualizowano system!");
        else
            NotificationManager::addNewNotification("pqcms-update",$notTitle,"e","Nie można wypakować aktualizacji. System nie ma wsytarczających uprawnień!");
        $zipArchive->close();

        require_once(dirname(__DIR__,3)."/config/data/JSONPQCMS.php");
        $pqcms = new JSONPQCMS();
        $pqcms->setVersion("${update["type_name"]}-$versionStringified",$update["date"]);
        $pqcms->saveData();

        if(!file_exists("${filename}zip") || !unlink("${filename}zip"))
            NotificationManager::addNewNotification("pqcms-update-delete-file", $notTitle, "w", "Błąd podczas usuwania zbędnych plików aktualizacji (zip)!");

        $jsonContent = file_get_contents($path."json");
        if($jsonContent === false)
        {
            NotificationManager::addNewNotification("pqcms-update", $notTitle, "w", "Nie udało się pobrać informacji o zbędnych plikach!");
            return false;
        }

        $removableFiles = json_decode($jsonContent,true);
        if(is_null($removableFiles))
        {
            NotificationManager::addNewNotification("pqcms-update",$notTitle,"w","Błędny format zbędnych plików na serwerze!");
            return false;
        }

        $removed = true;
        foreach($removableFiles as $file)
        {
            if(!unlink(dirname(__DIR__,3)."/$file"))
            {
                $removed = false;
                break;
            }
        }

        if(!$removed)
        {
            NotificationManager::addNewNotification("pqcms-update", $notTitle, "w", "Niepowodzenie podczas czyszczenia plików po aktualizacji!");
            return false;
        }

        return true;
    }
}