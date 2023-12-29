<?php

/**
 * Klasa generująca html z PQCode (BBCode, jednak na niestandardowych zasadach)
 */
class PQCode
{
    protected $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    private static array $codes = [
        "[b]" => "<b>",
        "[/b]" => "</b>",
        "[i]" => "<i>",
        "[/i]" => "</i>",
        "[u]" => "<u>",
        "[/u]" => "</u>",
        "[s]" => "<s>",
        "[/s]" => "</s>",
//        "[link]" => "<a>",
//        "[/link]" => "</a>",
        "[br]" => "</br>",
        "\n" => "</br>",
        "[p]" => "<p>",
        "[/p]" => "</p>",
        "[quote]" => "<quote>",
        "[/quote]" => "</quote>",
    ];

    public static function getCodes(): array
    {
        return self::$codes;
    }

    public function getHTML(): string
    {
        return strtr($this->code, self::$codes);
    }
}