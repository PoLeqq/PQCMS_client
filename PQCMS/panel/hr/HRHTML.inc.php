<?php

class HRHTML
{
    private ?array $userPerms;

    public function __construct()
    {
        @session_start();
        require_once(dirname(__DIR__,2)."/Communicator.inc.php");
        $response = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => [
            "pqcms.hr.user.add",
            "pqcms.hr.rank.add",
        ]]);

        if($response["suc"] == 0)
            $this->userPerms = null;
        else
            $this->userPerms = $response["perms"];
    }

    public function getUsers(): void
    {
        $usersResponse = Communicator::communicate(CommunicateURL::GET_USER,["admin" => 0]);

        $addUserHtml = (!is_null($this->userPerms) && $this->userPerms["pqcms.hr.user.add"]) ?
            '<img class="overlayLink" data-overlayPath="./overlays/adders/users/" src="images/plus.svg" alt="plus">' : "";

        echo<<<HTML
<div class="d-flex">
    <div class="col-10 d-flex align-items-center">
        <h3>
            Użytkownicy
        </h3>
    </div>
    <div class="col-2 addImage img-fluid">
        ${addUserHtml}
    </div>
</div>
HTML;

        if($usersResponse["suc"] == 0)
        {
            echo<<<HTML
<span style="color: red">
    Wystąpił błąd podczas pobierania użytkowników: ${$usersResponse["desc"]}
</span>
HTML;
            return;
        }
        else if(count($usersResponse["resp"]) == 0)
        {
            echo<<<HTML
<span>
    Nie ma żadnych danych do wyświetlania<br/> 
    <i>(czy masz uprawnienia na przeglądanie użytkowników?)</i>
</span>

<table class="px-4 my-3 col-12 data-table" id="users-table">
    <thead>
    <tr>
        <th>Login</th>
        <th>Nazwa</th>
        <th>E-Mail</th>
        <th>Stan</th>
        <th>Sesja</th>
        <th>Usuń</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
HTML;

            return;
        }

        $rowsHtml = $this->getUsersRows($usersResponse);
        echo<<<HTML
<p style="text-decoration: underline">
    Kliknij na wiersz użytkownika (nie licząc komórki "usuń" [i "sesja" - w przypadku, gdy posiada aktywną sesję]), aby go edytować!
</p>
<i>Stan - czy konto jest włączone (można się zalogować)</i><br/>
<i>
    Sesja - czy sesja konta jest aktywna (użytkownik jest zalogowany). 
    <span style="text-decoration: underline">Kliknięcie komórki, gdy jej wartość jest "Wł." unieważnia sesję.</span>
</i><br/>
<i style="text-decoration: underline">Usuń - gdy klikniesz komórkę, użytkownik zostanie usunięty</i>
<table class="px-4 my-3 col-12 data-table" id="users-table">
    <thead>
    <tr>
        <th>Login</th>
        <th>Nazwa</th>
        <th>E-Mail</th>
        <th>Stan</th>
        <th>Sesja</th>
        <th>Usuń</th>
    </tr>
    </thead>
    <tbody>        
        ${rowsHtml}
    </tbody>
</table>
HTML;
    }

    private function getUsersRows(array $usersResponse): string
    {
        $rowsHtml = "";
        foreach($usersResponse["resp"] as $user)
        {

            if($user["username"] === $_SESSION["pqcms"]["panel"]["username"])
                continue;
            $enabledClass = $user["disabled"] ? "enabled-no" : "enabled-yes";
            $enabledText = $user["disabled"] ? "Wył" : "Wł";
            $sessionClass = isset($user["active_session"]) ? "enabled-yes" : "enabled-no";
            $sessionText = isset($user["active_session"]) ? "<abbr title=\"Sesja wygasa: ${user["active_session"]}\">Wł</abbr>" : "Wył";

            $urlencodedPerms = urlencode(json_encode($user["perms"]));
            $rowsHtml .= <<<HTML
<tr class="overlayLink" data-overlayPath="./overlays/editors/users/index.php?username=${user["username"]}&nickname=${user["nickname"]}&email=${user["email"]}&disabled=${user["disabled"]}&perms=$urlencodedPerms">
    <td data-username>${user["username"]}</td>
    <td data-nickname>${user["nickname"]}</td>
    <td data-email>${user["email"]}</td>
    <td data-enabled class="$enabledClass">$enabledText</td>
    <td data-session-close class="$sessionClass user-session-close" data-username="${user["username"]}">$sessionText</td>
    <td data-delete class="user-delete" data-username="${user["username"]}">Usuń</td>
</tr>
HTML;
        }
        return $rowsHtml;
    }

    public function getRanks(): void
    {
        $ranksResponse = Communicator::communicate(CommunicateURL::GET_RANK);

        $addRankHtml = (!is_null($this->userPerms) && $this->userPerms["pqcms.hr.rank.add"]) ?
            '<img class="overlayLink" data-overlayPath="./overlays/adders/ranks/" src="images/plus.svg" alt="plus">' : "";

        echo<<<HTML
<div class="d-flex">
    <div class="col-10 d-flex align-items-center">
        <h3>
            Rangi
        </h3>
    </div>
    <div class="col-2 addImage img-fluid">
        $addRankHtml
    </div>
</div>
HTML;

        if($ranksResponse["suc"] == 0)
        {
            echo<<<HTML
<span style="color: red">
    Wystąpił błąd podczas pobierania użytkowników: ${ranksResponse["desc"]}
</span>
HTML;
            return;
        }
        else if(count($ranksResponse["resp"]) == 0)
        {
            echo<<<HTML
<span>
    Nie ma żadnych danych do wyświetlania<br/> 
    <i>(czy masz uprawnienia na przeglądanie rang?)</i>
</span>

<table class="px-4 my-3 col-12 data-table" id="ranks-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>P</th>
<!--            <th>Rodzic</th>-->
            <th>Usuń</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
HTML;
            return;
        }

        $rowsHtml = $this->getRankRows($ranksResponse);
        echo<<<HTML
<i>ID - identyfikator</i><br/>
<i>Nazwa - wyświetlana nazwa</i><br/>
<i>P - priorytet</i><br/>
<!--<i>Rodzic - ID rodzica</i><br/>-->
<i style="text-decoration: underline">Usuń - gdy klikniesz komórkę, ranga zostanie usunięta</i>
<table class="px-4 my-3 col-12 data-table" id="ranks-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>P</th>
<!--            <th>Rodzic</th>-->
            <th>Usuń</th>
        </tr>
    </thead>
    <tbody>
        ${rowsHtml}
    </tbody>
</table>
HTML;
    }

    private function getRankRows(array $ranksResponse): string
    {
        $rowsHtml = "";
        foreach($ranksResponse["resp"] as $rank)
        {
            $urlencodedPerms = urlencode(json_encode($rank["perms"]));
            $rank["parent"] = empty($rank["parent"]) ? "-" : $rank["parent"];
            $rowsHtml .= <<<HTML
<tr class="overlayLink" data-overlayPath="./overlays/editors/ranks/index.php?name=${rank["name"]}&display_name=${rank["display_name"]}&priority=${rank["priority"]}&parent=${rank["parent"]}&perms=$urlencodedPerms">
    <td data-name>${rank["name"]}</td>
    <td data-display_name>${rank["display_name"]}</td>
    <td data-priority>${rank["priority"]}</td>
<!--    <td>{rank["parent"]}</td> -->
    <td class="rank-delete" data-name="${rank["name"]}">Usuń</td>
</tr>
HTML;
        }
        return $rowsHtml;
    }
}