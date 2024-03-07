<?php

final class Response
{
    private string $id;
    private string $name;
    private array $botResp;
    private array $userResp;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->botResp = $data["bot_resp"];
        $this->userResp = [];

        require_once("answer/Answer.inc.php");
        require_once("answer/AnswerAction.inc.php");
        foreach($data["user_resp"] as $userResp)
            $this->userResp[] = new Answer($userResp["text"],$userResp["redirect"], $userResp["user_text"], $userResp["actions"]);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBotResp(): array
    {
        return $this->botResp;
    }

    public function getUserResp(): array
    {
        return $this->userResp;
    }

    public function getRandomBotResponse(): string {
        return $this->botResp[array_rand($this->botResp)];
    }

    public function getUserRespData(): array {
        $data = [];
        /** @var Answer $resp */
        foreach ($this->userResp as $resp) {
            $data[] = [
                "text" => $resp->getText(),
                "redirect" => $resp->getRedirect(),
                "user_text" => $resp->getUserText(),
                "actions" => $resp->getActionsData()
//                    [

//                    "type" => $resp->getAction()->getType(),
//                    "value" => $resp->getAction()->getValue(),
//                    ""
//                ]
            ];
        }
        return $data;
    }
}