<?php

require_once("response/Response.inc.php");

class ClientBot
{
    protected string $name;
    protected string $errorMessage;
    protected array $responses;

    public function __construct()
    {
        require_once(dirname(__DIR__) ."/PQCMSClientBot.inc.php");
        require_once("response/Response.inc.php");
        $bot = new PQCMSClientBot();
        $config = $bot->getConfig();

        $this->name = $config["bot_name"];
        $this->errorMessage = $config["error_message"];
        $this->responses = [];

        foreach($config["responses"] as $resp)
            $this->responses[] = new Response($resp);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}