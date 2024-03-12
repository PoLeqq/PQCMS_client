<?php

class PQCMSClientBotMessageManager
{
    private array $responses;

    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    public function getEditableTable(): string
    {
        $html = "<ul class='d-flex flex-column gap-2' id='bot-messages'>";

        for($i=0; $i<count($this->responses); $i++)
        {
            $message = $this->responses[$i];

            $id = $message["id"];
            $name = $message["name"];
            $botResp = $message["bot_resp"];
            $userResp = $message["user_resp"];

            if(is_array($message))
            {
//                $count = count($message);
                $html .= <<<HTML
    <li class="my-3">
        <details class="d-flex flex-column">
            <summary>
<!--                <input type="text" name="responses[$/id][id]" value="$/id"/>-->
                $name
            </summary>
            <div class="text-inputs">
HTML;
                $html .= <<<HTML
                <h4>
                    Dane:
                </h4>
                ID: <input type="text" name="messages[$id][id]" value="$id"/><br/>
                Nazwa: <input type="text" name="messages[$id][name]" value="$name"/>
                <h4>Odpowiedzi bota</h4>
                <ul>
HTML;
                foreach($botResp as $botR)
                {
                    $html .= <<<HTML
                    <li>
                        <input type="text" name="messages[$id][]" value="$botR"/>
                        <input type="button" class="text-remove" value="x">
                    </li>
HTML;
                }
                $html .= <<<HTML
                </ul>
HTML;

                $html .= <<<HTML
                <h4>Wybory użytkownika</h4>
                <ul>
HTML;
                foreach($userResp as $userR)
                {
                    $text = $userR["text"];
                    $redirect = $userR["redirect"];
                    $user_text = $userR["user_text"];
                    $actions = print_r($userR["actions"],true);

                    $html .= <<<HTML
                    <li class="d-flex flex-column gap-1 my-3">
                        Tekst: <input type="text" name="messages[$id][user_resp][text]" value="$text"/>
                        Pytanie: <input type="text" name="messages[$id][]" value="$redirect"/>
                        Odpowiedź użytkownika: <input type="text" name="messages[][$id][]" value="$user_text"/>
                        Akcje: $actions
                        <input type="button" class="text-remove" value="x">
                    </li>
HTML;
                }
                $html .= <<<HTML
                </ul>
HTML;

                $html .= <<<HTML
            </div>
            <input type="button" class="add-text-text" value="Dodaj tekst (losowy)"/>
        </details>
    </li>
HTML;
            }
            else
                $html .= <<<HTML
    <span class="text-danger">Niepoprawny plik konfiguracyjny! Wszystkie wartości powinny być tablicami! (<code>$message</code>)</span>
HTML;

        }

        $html .= "</ul>";
        return $html;
    }

    public function getStartMessages(): array
    {
        return $this->startMessages;
    }
}