<?php

require_once(dirname(__DIR__,3)."/classes/AddonWebsite.inc.php");
class PQCMSStarSystemWebsite extends AddonWebsite
{
    public function onWebsiteHeadLoaded(int $folderDepth): ?string
    {
        $dir = $this->addon->getRelativePathFromWebsiteToAddon($folderDepth);
        return<<<HTML
<link rel="stylesheet" href="$dir/website/style.css">
<script src="$dir/website/export.js" defer></script>
<script src="$dir/website/script.js" type="module" defer></script>
HTML;
    }

    /**
     * Funkcja wywoływana na stronie widocznej dla klienta
     * @return ?string tekst, który będzie wyświetlony na stronie w sekcji <i>&lt;body&gt;</i> (np. HTML, JS)
     */
    public function onWebsiteBodyLoaded(int $folderDepth): ?string
    {
        $dir = $this->addon->getRelativePathFromWebsiteToAddon($folderDepth);
        $id = "pqcms-starsystem";

        $config = $this->addon->getConfig();

        return<<<HTML
<div id="$id-container" class="align-items-center justify-content-center" style="display: none;">
    <div id="$id-close"></div>
    <div id="$id-main" class="d-flex flex-column align-items-center gap-5">
        <h1>Oceń nas!</h1>
        <div id="$id-stars" class="col-12 d-flex align-items-center justify-content-center">
            <img data-star="1" class="img-fluid" src="$dir/images/star_full.svg" alt="Pusta gwiazdka"/>
            <img data-star="2" class="img-fluid" src="$dir/images/star_empty.svg" alt="Pusta gwiazdka"/>
            <img data-star="3" class="img-fluid" src="$dir/images/star_empty.svg" alt="Pusta gwiazdka"/>
            <img data-star="4" class="img-fluid" src="$dir/images/star_empty.svg" alt="Pusta gwiazdka"/>
            <img data-star="5" class="img-fluid" src="$dir/images/star_empty.svg" alt="Pusta gwiazdka"/>
        </div>
    </div>
</div>

<div id="$id-reason-container" class="align-items-center justify-content-center" style="display: none;">
    <div id="$id-reason-close"></div>
    <div id="$id-reason" class="d-flex flex-column align-items-start justify-content-center col-12 col-md-10 col-lg-8 col-xl-6 p-4 gap-4">
        <div>
            E-mail:
            <input type="email" name="email" id="pqcms-starsystem-reason-email"/>
            <div id="pqcms-starsystem-reason-email-error"></div>
        </div>
        <div>
            Opis
            <textarea name="desc" id="pqcms-starsystem-reason-desc" placeholder="Z czego nie jesteś zadowolony? Co możemy poprawić?"></textarea>
        </div>
        <div class="d-flex align-items-center">
            <input type="submit" id="pqcms-starsystem-reason-submit" value="Prześlij" class="col-12"/>        
        </div>
    </div>
</div>

<input type="hidden" id="$id-data-dir" value="$dir"/>
<input type="hidden" id="$id-data-redirect_url" value="${config["redirect-url"]}"/>
<input type="hidden" id="$id-data-redirect_min" value="${config["star-redirect-rate"]}"/>
<input type="hidden" id="$id-data-redirect_new" value="${config["redirect-new-page"]}"/>
HTML;
    }
}