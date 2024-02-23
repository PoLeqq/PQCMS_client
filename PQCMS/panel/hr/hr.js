import { Overlay } from './Overlay.js';
import {IframeListener} from './IframeListener.js';

let overlay = new Overlay();

document.querySelectorAll('.overlayLink').forEach(e => {
    e.addEventListener('click',function() {
        overlay.showOverlay(e.getAttribute("data-overlayPath"))
    })
})

class RowListener
{
    openOverlay(element) {
        overlay.showOverlay(element.getAttribute("data-overlayPath"))
    }

    deleteUser(element,event)
    {
        event.stopPropagation();

        const username = element.getAttribute("data-username");
        fetch("scripts/DeleteUser.php?username=" + username)
            .then(response  => {
                if(!response.ok)
                    console.error(`Wystąpił błąd podczas usuwania użytkownika ${username}! (response not ok)`);
                else
                {
                    response.json()
                        .then((json) => {
                            if(json["suc"] === 1)
                                element.parentElement.remove();
                        });
                }
            })
            .catch(() => {
                console.error(`Wystąpił błąd podczas usuwania użytkownika ${username}!`);
            })
    }

    deleteRank(element,event)
    {
        event.stopPropagation();

        const name = element.getAttribute("data-name");
        fetch("scripts/DeleteRank.php?name=" + name)
            .then(response  => {
                if(!response.ok)
                    console.error(`Wystąpił błąd podczas usuwania rangi ${name}! (response not ok)`);
                else
                {
                    response.json()
                        .then((json) => {
                            if(json["suc"] === 1)
                                element.parentElement.remove();
                        });
                }
            })
            .catch(() => {
                console.error(`Wystąpił błąd podczas usuwania rangi ${name}!`);
            })
    }

    closeUserSession(element,event)
    {
        if(element.innerText === "Wł")
        {
            event.stopPropagation();
            const username = element.getAttribute("data-username");
            fetch("scripts/InvalidateUserSession.php?username=" + username)
                .then(response  => {
                    if(!response.ok)
                        console.error(`Wystąpił błąd podczas unieważniania sesji ${username}! (response not ok)`);
                    else
                    {
                        response.json()
                            .then((json) => {
                                if(json["suc"] === 1)
                                    element.innerText = "Wył";
                                element.classList.remove("enabled-yes");
                                element.classList.add("enabled-no");
                            });
                    }
                })
                .catch(() => {
                    console.error(`Wystąpił błąd podczas unieważniania sesji ${username}!`);
                })
        }
    }
}

const rowListener = new RowListener();

document.querySelectorAll(".user-delete").forEach((e) => {
    e.addEventListener("click",(event) => {
        rowListener.deleteUser(e,event);
    })
});

document.querySelectorAll(".rank-delete").forEach((e) => {
    e.addEventListener("click",(event) => {
        rowListener.deleteRank(e,event);
    })
});

document.querySelectorAll(".user-session-close").forEach((e) => {
    e.addEventListener("click",(event) => {
        rowListener.closeUserSession(e,event);
    })
});

window.addEventListener('message', function(event) {
    // if (event.origin !== 'http://<?php //echo $pqcms->getDomain() ?>//' ||
    //    event.origin !== "https://<?php //echo $pqcms->getDomain() ?>//") {
    //    return;
    // }

    let iframeListener = new IframeListener(event.data,overlay,rowListener);
    iframeListener.handleResponse();
});