<?php

class PQCMSClientBotStartMessageManager
{
    private array $startMessages;

    public function __construct(array $startMessages)
    {
        $this->startMessages = $startMessages;
    }

    public function getEditableTable(): string
    {
        $html = "<ul>";

        for($i=0; $i<count($this->startMessages); $i++)
        {
            $message = $this->startMessages[$i];

            if(is_string($message))
                $html .= <<<HTML
    <span class="text-danger">Wszystkie wartości powinny być tablicami, znaleziono napis! (<code>$message</code>)</span>
HTML;
            else if(is_array($message))
            {
                $count = count($message);
                $html .= <<<HTML
    <li data-index="$i">
        <details class="d-flex flex-column">
            <summary>Losowe ($count)</summary>
            <div class="text-inputs">
HTML;
                foreach($message as $msgOption)
                {
                    $html .= <<<HTML
                <div>
                    <input name="startmessages[$i][]" value="$msgOption"/>
                    <input type="button" class="text-remove" value="x">
                </div>
HTML;

                }
                $html .= <<<HTML
            </div>
            <input type="button" class="add-text-btn" value="Dodaj tekst"/>
        </details>
    </li>
HTML;
            }

        }
        $html .= <<<HTML
    <li><input type="button" class="add-text-btn" value="Dodaj tekst"/></li>
HTML;


        $html .= "<ul>";
        return $html;
    }

    public function getStartMessages(): array
    {
        return $this->startMessages;
    }
}