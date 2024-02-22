<?php

class LogsHTML
{
    private array $links;

    public function __construct()
    {
        $this->links = [
            "logs" => "Logi",
            "stats" => "Statystyki"
        ];
    }

    public function getLogsButtons(): string
    {
        $html = "";
        foreach($this->links as $link => $text)
        {
            $html .= <<<HTML
<div class="col-3 p-5" id="pqcms-log-type-button-$link">
    <h3>${text}</h3>
</div>
HTML;
        }
        return $html;
    }

    public function getLogsScripts(): string
    {
        $js = "<script defer>";
        foreach($this->links as $link => $text)
        {
            $js .= <<<JS
document.querySelector("#pqcms-log-type-button-$link").addEventListener("click",() => {
    window.location.href = "$link/";
});
JS;
        }
        $js .= "
</script>";
        return $js;
    }
}