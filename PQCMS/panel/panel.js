let internalLinks = document.querySelectorAll('.internalLink')
let main = document.querySelector("#panelMain");

// Aktualizacja bloku panelu po załądowaniu strony
document.addEventListener('DOMContentLoaded', function() {
    if(!getCookie("main"))
        updateMain("home")
    else
        updateMain(getCookie("main"));
})

// Funkcjonalność linków - podmiana bloku panelu
internalLinks.forEach(e => {
    e.addEventListener('click',function() {
        updateMain(e.getAttribute("internalLink"))
    })
    e.addEventListener('keypress',function(event) {
        if(event.keyCode === 13 || event.key === "Enter")
            updateMain(e.getAttribute("internalLink"))
    })
})


const iframeOverlay = document.querySelector("#mainIframeOverlay");
const iframe = document.querySelector("#panelMain");

/**
 * Funkcja aktualizująca główny blok panelu
 * @param {string} path ścieżka pliku, która będzie wyświetlana w panelu
 */
function updateMain(path)
{
    // if(main.src !== "")
    // {
    //     const pathURL = new URL(path, window.location);
    //     const currentURL = new URL(main.src);
    //
    //     if(currentURL.href === pathURL.href)
    //         return;
    // }

    main.src = path;
    iframeOverlay.style.visibility = "visible";
    iframeOverlay.style.opacity = "1";
    setCookie("main",path,0,0,30);
}

iframe.addEventListener("load",() => {
    const loginURL = window.location.protocol+"//"+window.location.hostname+"/pqcms/login/";
    let iframeURL = null;
    try {
        iframeURL = iframe.contentWindow.location.href;
    } catch(ignore) {}

    if(iframeURL === loginURL)
        // window.location.href = `./scripts/InvalidateSession.php?outdated=${data["outdated"]}&invalidated=${data["invalidated"]}&not_secure=${data["not_secure"]}`;
        window.location.href = loginURL;
    else
    //     iframeOverlay.style.opacity = "0";
        setTimeout(() => {
            iframeOverlay.style.visibility = "hidden";
        },510);
})


// COOKIES

/**
 * Funkcja zapisująca ciasteczko
 * @param {string} cname nazwa
 * @param {string} cvalue wartość
 * @param {int} exdays wygasa w dniach
 * @param {int} exhours wygasa w godzinach
 * @param {int} exminutes wygasa w minutach
 */
function setCookie(cname, cvalue, exdays, exhours, exminutes) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000) + (exhours * 60 * 60 * 1000) + (exminutes * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  
/**
 * Zwraca wartość ciasteczka (lub null)
 * @param {string} cname nazwa ciastka
 * @returns string|null ciasteczko
 */
function getCookie(cname) {
    let name = cname + "="
    let ca = document.cookie.split(';')
    for(let i = 0; i < ca.length; i++) 
    {
      let c = ca[i];
      while (c.charAt(0) === ' ')
        c = c.substring(1)
      if (c.indexOf(name) === 0)
        return c.substring(name.length, c.length)
    }
    return null;
}

window.addEventListener("load",() => {
    setTimeout(validateSession,0);
})

export async function validateSession() {
    fetch(window.location.origin + "/pqcms/panel/scripts/IsValidUserSession.php").then(resp =>
    {
        if(resp.status === 404)
        {
            console.error("Błąd 404. Nie można sprawdzić poprawności sesji użytkownika! Skontaktuj się z administratorem PQCMS. (tester: ignore)");
            return;
        }

        resp.json().then((response) =>
        {
            if(response["suc"] === 0)
            {
                fetch(`scripts/notifications/NotificationManager.php?action=a
                &id=paneljs
                &type=e
                &title=Weryfikacja sesji
                &text=${response["desc"]}`)
                    .then(function (response) {
                        if(!response.ok)
                            throw new Error('Nie połączono z NotificationManager');
                    })
                    .catch(function(res){
                        console.error(res)
                    });
            }
            else if(response["resp"]["valid"] == 0)
            {
                let data = response["resp"];
                if(data["outdated"] == 0 && data["invalidated"] == 0)
                {
                    fetch(`scripts/notifications/NotificationManager.php?action=a
                        &id=paneljsip
                        &type=e
                        &title=Weryfikacja sesji
                        &text=Wykryto zmianę IP! Wyłącz VPN (jeśli przed chwilą został włączony)! Inaczej utracisz dostęp do tej sesji`)
                        .then(function (response) {
                            if(!response.ok)
                                throw new Error('Nie połączono z NotificationManager');
                        })
                        .catch(function(res){
                            console.error(res)
                        });
                    console.log("Nie unieważniono sesji, ponieważ wszystkie dane są prawidłowe!");
                    setTimeout(validateSession,5000);
                    return;
                }

                setTimeout(validateSession,5000);
                window.location.replace(`scripts/InvalidateSession.php?outdated=${data["outdated"]}&invalidated=${data["invalidated"]}&not_secure=${data["not_secure"]}`);
            }
            else
                setTimeout(validateSession,5000);
        }).catch(error => {
            console.error(error);
            setTimeout(validateSession,5000);
        })
    }).catch(error =>
    {
        console.error(error);
        setTimeout(validateSession,5000);
    })
}