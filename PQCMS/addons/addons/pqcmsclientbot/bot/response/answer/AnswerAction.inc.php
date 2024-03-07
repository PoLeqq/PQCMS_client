<?php

class AnswerAction
{
    protected array $data;

    public function __construct(array $data)
    {
        if(!in_array($data["type"],self::getAllowedTypes()))
            throw new Exception("Type \"${data["type"]}\" is not expected value!");

        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public static function getAllowedTypes(): array
    {
        return [
            "question",
            "hidebot",
            "redirect"
        ];
    }
}