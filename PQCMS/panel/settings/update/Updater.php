<?php

class Updater
{
    public function isUpdateAvaliable(): bool
    {
//        Porównywanie wersji
        return false;
    }

    public function getUpdateSize(): int
    {
//        wynik w bajtach
        return -1;
    }

    public function update(): bool
    {
        $serverFiles = json_decode(file_get_contents('https://example.com/update/files.json'), true);

        if ($serverFiles !== false) {
            foreach ($serverFiles as $file) {
                $localFilePath = 'path/to/local/files/' . $file['name'];

                if (!file_exists($localFilePath) || version_compare($file['version'], file_get_contents($localFilePath . '.version')) > 0) {
                    // Pobierz plik, bo nie istnieje lokalnie lub jest nowsza wersja
                    $fileContent = file_get_contents('https://example.com/update/files/' . $file['name']);

                    if ($fileContent !== false) {
                        file_put_contents($localFilePath, $fileContent);
                        file_put_contents($localFilePath . '.version', $file['version']);
                        echo 'Pobrano plik: ' . $file['name'] . PHP_EOL;
                    } else {
                        echo 'Błąd pobierania pliku: ' . $file['name'] . PHP_EOL;
                    }
                } else {
                    echo 'Plik ' . $file['name'] . ' jest aktualny.' . PHP_EOL;
                }
            }
        } else {
            echo 'Błąd pobierania informacji o plikach.' . PHP_EOL;
            return false;
        }
        return true;
    }
}