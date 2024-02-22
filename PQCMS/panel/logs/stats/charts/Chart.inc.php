<?php

abstract class Chart
{
    protected string $chartId;

    public function __construct(string $chartId)
    {
        $this->chartId = $chartId;
    }

    public function generateHTML(): ?string {
        @session_start();
        if(empty($_SESSION["pqcms"]["panel"]["logs"]["charts"]) || $_SESSION["pqcms"]["panel"]["logs"]["charts"] !== true)
            return null;
        return $this->getHTML();
    }

    protected abstract function getHTML(): ?string;

    public function generateJS(): ?string {
        @session_start();
        if(empty($_SESSION["pqcms"]["panel"]["logs"]["charts"]) || $_SESSION["pqcms"]["panel"]["logs"]["charts"] !== true)
            return null;
        return $this->getJS();
    }

    protected abstract function getJS(): string;

    protected abstract function getData(): array;

    public static function getUniqueColors(): array {
        return [
            "#1f77b4",
            "#aec7e8",
            "#ff7f0e",
            "#ffbb78",
            "#2ca02c",
            "#98df8a",
            "#d62728",
            "#ff9896",
            "#9467bd",
            "#c5b0d5",
            "#8c564b",
            "#c49c94",
            "#e377c2",
            "#f7b6d2",
            "#7f7f7f",
            "#c7c7c7",
            "#bcbd22",
            "#dbdb8d",
            "#17becf",
            "#9edae5",
            "#1f77b4",
            "#aec7e8",
            "#ff7f0e",
            "#ffbb78",
            "#2ca02c",
            "#98df8a",
            "#d62728",
            "#ff9896",
            "#9467bd",
            "#c5b0d5",
            "#8c564b",
            "#c49c94",
            "#e377c2",
            "#f7b6d2",
            "#7f7f7f",
            "#c7c7c7",
            "#bcbd22",
            "#dbdb8d",
            "#17becf",
            "#9edae5",
            "#1f77b4",
            "#aec7e8",
            "#ff7f0e",
            "#ffbb78",
            "#2ca02c",
            "#98df8a",
            "#d62728",
            "#ff9896",
            "#9467bd",
            "#c5b0d5"
        ];
    }
}