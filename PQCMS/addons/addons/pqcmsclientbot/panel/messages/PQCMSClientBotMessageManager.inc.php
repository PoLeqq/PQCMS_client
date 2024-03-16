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
        $html = "<ul class='d-flex flex-column bot-list' id='bot-messages'>";

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
    <li>
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
                <input type="hidden" name="messages[$i][old_id]" value="$id" class="my-1"/><br/>
                ID: <input type="text" name="messages[$i][id]" value="$id" class="my-1"/><br/>
                Nazwa: <input type="text" name="messages[$i][name]" value="$name" class="text-input-name my-1"/>
                <h4>Odpowiedzi bota</h4>
                <ul class="bot-resp">

HTML;
                foreach($botResp as $botR)
                {
                    $html .= <<<HTML
                    <li>
                        <input type="text" name="messages[$i][bot_resp][]" value="$botR"/>
                        <input type="button" class="text-remove" value="x">
                    </li>

HTML;
                }
                $html .= <<<HTML
                </ul>
                <input type="button" value="Dodaj tekst" class="responses-add-bot mt-2"/>

HTML;

                $html .= <<<HTML
                <h4>Wybory użytkownika</h4>
                <ul class="user-resp">

HTML;

                $j = 0;
                foreach($userResp as $userR)
                {
                    $text = $userR["text"];
                    $redirect = $userR["redirect"];
                    $user_text = $userR["user_text"];
                    $actions = $userR["actions"];

                    $actionsHtml = "";
                    foreach ($actions as $action)
                    {
                        $c = var_export($action,true);
                        $action = urlencode(json_encode($action));
                        $actionsHtml .= <<<HTML
                        <input type="hidden" name="messages[$i][user_resp][$j][actions][]" value="$action"/>
$c<br/>

HTML;
                    }

                    $html .= <<<HTML
                    <li>
                        Tekst: <input type="text" name="messages[$i][user_resp][$j][text]" value="$text"/>
                        Pytanie (przekierowanie): <input type="text" name="messages[$i][user_resp][$j][redirect]" value="$redirect"/>
                        Odpowiedź użytkownika (tekst użytkownika po kliknięciu): <input type="text" name="messages[$i][user_resp][$j][user_text]" value="$user_text"/>
<!--                        Akcje: $/actions-->
                        $actionsHtml
                        <input type="button" class="text-remove" value="x">
                    </li>
HTML;
                    $j++;
                }

                $html .= <<<HTML
                </ul>
                <input type="button" value="Dodaj wybór" class="responses-add-user"/>
HTML;

                $html .= <<<HTML
            </div>
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

    public function getResponses(): array
    {
        return $this->responses;
    }
}