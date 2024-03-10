const main = document.querySelector("#pqcms-clientbot");
//
const expander = document.querySelector("#pqcms-clientbot-expander");
const conversation = document.querySelector("#pqcms-clientbot-bot-conversation");
const dir = document.querySelector("#pqcms-clientbot-data-dir").value;

let botShow = false;

function getResponse(value)
{
    disableAllResponses();

    fetch(dir+"/system/GetResponse.php", {
        method: "POST",
        headers: {
            "Content-Type" : "application/json",
        },
        body : JSON.stringify(value)
    }).then((resp) => {
        resp.json().then((json) => {
            addMessageBox("bot",!json["suc"],json["msg"]);

            if(json["suc"] === 1)
            {
                const options = document.querySelector("#pqcms-clientbot-options");
                options.innerHTML = "";

                json["resp"].forEach(e => {
                    const option = getAnswerResponseBox(e.text);
                    option.addEventListener("click", () => {
                        if(option.hasAttribute("disabled"))
                            return;
                        onAnswerResponseBotClick(e);
                    });

                    options.appendChild(option);
                })
            }
            else
                enableAllResponses();
        }).catch((err) => {
            console.error(err);
            enableAllResponses();
        });
    }).catch((err) => {
        console.error(err);
        enableAllResponses();
    });
}

getResponse("default");

expander.addEventListener("click",() => {
    if(main.getAttribute("data-expand") === "false")
        showBot();
    else
        hideBot();
});

window.addEventListener('click', function(e){
    if(!main.contains(e.target) && botShow)
        hideBot();
});

function showBot()
{
    botShow = true;
    main.style.bottom = "0";
    main.setAttribute("data-expand","true");
}

function hideBot()
{
    botShow = false;
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
    content.innerHTML = msgText;

    message.appendChild(icon);
    message.appendChild(content);

    conversation.appendChild(message);

    conversation.scrollTo(0, conversation.scrollHeight);
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
        if(e.type === "redirect") {
            setTimeout(() => {
                window.open(e.value, '_blank');
            },1000);
        }
        else if(e.type === "hidebot") {
            console.log("ClientBot: hide");
            setTimeout(() => {
                expander.click();
            },1000);
        }
        else if(e.type === "function") {
            console.log(`ClientBot: ran function ${e.value}()`);
            window.dispatchEvent(new CustomEvent("PqcmsClientBotFunctionCall", {
                detail: {
                    function: e.value
                }
            }));
        }
        else if(e.type === "message")
        {
            for(let i=0; i<e.value.length; i++)
            {
                const msg = e.value[i];

                if(msg.type === "bot")
                    addMessageBox(msg.type,false,msg.msg);
                else if(msg.type === "user")
                    addMessageBox(msg.type,false,msg.msg);
                else
                    console.error(`Wrong value in \"message\" section: (${msg})`)
                // },(i+1) * 1000);
            }
        }
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