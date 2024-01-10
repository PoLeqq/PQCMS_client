import { Overlay } from './Overlay.js';

let overlay = new Overlay();

document.querySelectorAll('.overlayLink').forEach(e => {
    e.addEventListener('click',function() {
        overlay.showOverlay(e.getAttribute("data-overlayPath"))
    })
})

document.querySelectorAll(".user-session-close").forEach((e) => {
    e.addEventListener("click",(event) => {
        event.stopPropagation();
        const username = e.getAttribute("data-username");

    })
});