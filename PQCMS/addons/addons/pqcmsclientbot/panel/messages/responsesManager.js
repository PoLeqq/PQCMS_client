document.querySelectorAll(".responses-add-bot").forEach(e => {
    e.addEventListener("click", () => {
        const id = e.parentElement.querySelector(".response-id").value;

        const li = document.createElement("li");
        const text = document.createElement("input");
        text.type = "text";
        text.name = `messages[${id}][bot_resp][]`;
        text.value = "Nowy tekst bota...";

        const button = document.createElement("input");
        button.type = "button";
        button.classList.add("text-remove");
        button.value = "x";
        addRemoveTextListener(button);

        li.append(text,button);
        e.parentElement.querySelector(".bot-resp").append(li);
    })
})

function addRemoveTextListener(element) {
    element.addEventListener("click", () => {
        element.parentElement.remove();
    });
}

document.querySelectorAll(".responses-add-user").forEach(e => {
    e.addEventListener("click", () => {
        e.parentElement.querySelector(".bot-resp").append()
    })
})
