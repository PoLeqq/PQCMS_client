document.querySelectorAll(".add-text-text").forEach(e => {
    e.addEventListener("click", () => {
        const li = e.parentElement.parentElement;
        const div = getStartMessageElement(li.getAttribute("data-index"));
        e.parentElement.querySelector(".text-inputs").appendChild(div);
    });
})

document.querySelectorAll(".text-remove").forEach(e => {
    addRemoveTextListener(e);
})

function addRemoveTextListener(element) {
    element.addEventListener("click", () => {
        element.parentElement.remove();
    });
}


function getStartMessageElement(index) {
    const div = document.createElement("div");

    const input = document.createElement("input");
    input.name = `startmessages[${index}][]`;
    input.value = "Nowy tekst bota...";

    const inputRemove = document.createElement("input");
    inputRemove.type = "button";
    inputRemove.value = "x";
    addRemoveTextListener(inputRemove);

    div.appendChild(input);
    div.appendChild(inputRemove);
    return div;
}