const main = document.querySelector("#pqcms-starsystem-container");
const reason = document.querySelector("#pqcms-starsystem-reason-container");
let reasonShown = false;

function pqcms_starsystem_show()
{
    main.style.display = "flex";
    main.style.animation = "showRateOverlay .3s forwards";
}

function pqcms_starsystem_hide()
{
    main.style.animation = "hideRateOverlay .3s forwards";
    setTimeout(() => {
        main.style.display = "none";
    },310);
}

function pqcms_starsystem_reason_show()
{
    if(reasonShown)
        return;

    reasonShown = true;
    reason.style.display = "flex";
    reason.style.animation = "showReasonOverlay .3s forwards";
}

function pqcms_starsystem_reason_hide()
{
    if(!reasonShown)
        return;

    reasonShown = false;
    reason.style.animation = "hideReasonOverlay .3s forwards";
    setTimeout(() => {
        reason.style.display = "none";
    },310);
}


const allowedMethods = {"pqcms_starsystem_show": pqcms_starsystem_show};
window.addEventListener("PqcmsClientBotFunctionCall", (event) => {
    const data = event.detail;

    for(let key in allowedMethods)
    {
        if(key === data.function)
        {
            console.log(`Ran "${key}()"`);
            allowedMethods[key]();
            break;
        }
    }
});