const main = document.querySelector("#pqcms-clientbot");
//
const expander = document.querySelector("#pqcms-clientbot-expander");
const conversation = document.querySelector("#pqcms-clientbot-bot-conversation");
//
expander.addEventListener("click",() => {
    if(main.getAttribute("data-expand") == "false")
        showBot();
    else
        hideBot();
});

function showBot()
{
    main.style.bottom = "0";
    main.setAttribute("data-expand","true");
}

function hideBot()
{
    main.style.bottom = "";
    main.setAttribute("data-expand","false");
}

function addMessageBox(type, error, msgText)
{
    if(msgText === null)
        return;

    const message = document.createElement("div");
    message.classList.add(`pqcms-clientbot-bot-message-${type}`);
    if(error)
        message.classList.add("pqcms-clientbot-bot-message-error");

    const icon = document.createElement("div");
    icon.classList.add(`pqcms-clientbot-bot-message-${type}-icon`);

    const content = document.createElement("div");
    content.classList.add(`pqcms-clientbot-bot-message-${type}-content`);
    content.innerText = msgText;

    message.appendChild(icon);
    message.appendChild(content);

    conversation.appendChild(message);
}

function getAnswerResponseBox(text) {
    const elem = document.createElement("div");
    elem.classList.add("pqcms-clientbot-option");
    elem.innerText = text;

    return elem;
}

function onAnswerResponseBotClick(responseData) {
    getResponse(responseData.redirect);
    addMessageBox("user",false,responseData["user_text"]);

    responseData.actions.forEach(e => {
        if(e.type === "redirect")
            window.open(e.value, '_blank');
    })
}

function disableAllResponses() {
    document.querySelectorAll(".pqcms-clientbot-option").forEach(e => {
        e.setAttribute("disabled","");
    })
}

function enableAllResponses() {
    document.querySelectorAll(".pqcms-clientbot-option").forEach(e => {
        e.removeAttribute("disabled");
    })
}