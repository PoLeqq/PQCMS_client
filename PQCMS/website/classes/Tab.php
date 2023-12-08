<?php

abstract class Tab
{
    protected string $name;
    protected string $path;
//    protected array $texts;

    /**
     * @param string $name
     * @param string $path
//     * @param array $texts
     */
    public function __construct(string $name, string $path/*, ...$texts*/)
    {
        $this->name = $name;
        $this->path = $path;

//        foreach ($texts as $text)
//            $this->texts[] = $text;
    }

    public abstract function generateHtml(bool $editable): string;
}