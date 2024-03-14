document.querySelectorAll(".text-input-name").forEach(e => {
    e.addEventListener("input",(event) => {
        e.parentElement.parentElement.querySelector("summary").innerText = e.value;
    })
})