let internalLinks = document.querySelectorAll('.internalLink')
let main = document.querySelector("#panelMain");

// Aktualizacja bloku panelu po załądowaniu strony
document.addEventListener('DOMContentLoaded', function() {
  if(!getCookie("main"))
    updateMain("home")
  else
    updateMain(getCookie("main"))
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
    const pathURL = new URL(path, window.location);
    const currentURL = new URL(main.src);

    if(currentURL.href === pathURL.href)
        return;

    main.src = path;
    iframeOverlay.style.visibility = "visible";
    iframeOverlay.style.opacity = "1";
    setCookie("main",path,0,0,30);
}

iframe.addEventListener("load",() => {
    iframeOverlay.style.opacity = "0";

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
 * @returns string|null|null|null ciasteczko
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

function checkAuthKeyValidity()
{
    return new Promise((resolve, reject) =>
    {
        let xmlHttp = new XMLHttpRequest();
        // todo do zmiany gdy wejdzie na prod (usunięcie "/pqcmsclinet")
        xmlHttp.open("GET", window.location.origin + "/pqcmsclient/pqcms/panel/scripts/IsValidUserSession.php", true);

        xmlHttp.onreadystatechange = function()
        {
            if(xmlHttp.readyState === 4)
            {
                if (xmlHttp.status === 200) resolve(JSON.parse(xmlHttp.responseText));
                else reject(xmlHttp.statusText);
            }
        };

        xmlHttp.send(null);
    });
}

async function invalidateSession(data)
{
    return new Promise((resolve, reject) =>
    {
        fetch(`./scripts/InvalidateSession.php?outdated=${data["outdated"]}&invalidated=${data["invalidated"]}`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            }).then((resp) => {
                resolve(resp.text());
            })
            .catch((error) => {
                reject(error);
            })
    });
}


setTimeout(sessionValidator,0);
setInterval(() =>
{
    sessionValidator();
},10000);

function sessionValidator() {
    checkAuthKeyValidity().then(response =>
    {
        if(response["suc"] === 0) window.location.href = `./scripts/InvalidateSession.php`;
        else if(response["resp"]["valid"] == 0)
        {
            let data = response["resp"];
            window.location.href = `./scripts/InvalidateSession.php?outdated=${data["outdated"]}&invalidated=${data["invalidated"]}&not_secure=${data["not_secure"]}`;
        }
    }).catch(error =>
    {
        console.error(error);
    })
}