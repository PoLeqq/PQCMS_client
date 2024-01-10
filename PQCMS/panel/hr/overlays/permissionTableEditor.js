let checkboxes = [];
checkboxes[true] = {};
checkboxes[false] = {};

document.querySelectorAll(".checkbox-perm-true").forEach((e) => {
    if(e.hasAttribute("data-perm"))
    {
        checkboxes[true][e.getAttribute("data-perm")] = e;
        e.addEventListener("click", () => {
            if(e.checked)
            {
                const oppositeCheckbox = checkboxes[false][e.getAttribute("data-perm")];
                if(oppositeCheckbox.checked)
                {
                    oppositeCheckbox.checked = false;
                    // oppositeCheckbox.parentElement.style.background = "rgba(0,0,0,0)";
                    oppositeCheckbox.parentElement.classList.remove("perm-disabled");
                }
                // e.parentElement.style.background = "rgba(0,255,0,.4)";
                e.parentElement.classList.add("perm-enabled");
            }
            else
                e.parentElement.classList.remove("perm-enabled");
                // e.parentElement.style.background = "rgba(0,0,0,0)";
                // e.parentElement.style.background = "rgba(0,0,0,0)";
        });

    }
});

document.querySelectorAll(".checkbox-perm-false").forEach((e) => {
    if(e.hasAttribute("data-perm"))
    {
        checkboxes[false][e.getAttribute("data-perm")] = e;
        e.addEventListener("click", () => {
            if(e.checked)
            {
                const oppositeCheckbox = checkboxes[true][e.getAttribute("data-perm")];
                if(oppositeCheckbox.checked)
                {
                    oppositeCheckbox.checked = false;
                    // oppositeCheckbox.parentElement.style.background = "rgba(0,0,0,0)";
                    oppositeCheckbox.parentElement.classList.remove("perm-enabled");
                }
                // e.parentElement.style.background = "rgba(255,0,0,0.4)";
                e.parentElement.classList.add("perm-disabled");
            }
            else
                e.parentElement.classList.remove("perm-disabled");
                // e.parentElement.style.background = "rgba(0,0,0,0)";
        });
    }
});

document.querySelectorAll("#permission-table-editable").forEach((e) => {

})