<?php

require_once("AnswerAction.inc.php");

class Answer
{
    protected string $text;
    protected string $redirect;
    protected string $userText;
    protected array $actions;

    /**
     * @param string $text
     * @param array $actions
     */
    public function __construct(string $text, string $redirect, string $userText, array $actions)
    {
        $this->text = $text;
        $this->redirect = $redirect;
        $this->userText = $userText;
        $this->actions = $actions;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getRedirect(): string
    {
        return $this->redirect;
    }

    public function getUserText(): string
    {
        return $this->userText;
    }

    public function getActions(): array
    {
        require_once("AnswerAction.inc.php");
        $actions = [];
        foreach ($this->actions as $action)
            $actions[] = new AnswerAction($action);
        return $actions;
    }

    public function getActionsData(): array {
//        $data = [];
//
//        foreach($this->getActions() as $action)
//            $data[] = $action->getData();
//
//        return $data;
        return $this->actions;
    }
}