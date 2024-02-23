let checkboxes = [];
checkboxes[true] = {};
checkboxes[false] = {};

document.querySelectorAll(".checkbox-perm-true").forEach((e) => {
    if(e.hasAttribute("data-perm"))
    {
        checkboxes[true][e.getAttribute("data-perm")] = e;
        e.addEventListener("click", (event) => {
            if(e.checked)
            {
                const oppositeCheckbox = checkboxes[false][e.getAttribute("data-perm")];
                if(oppositeCheckbox.checked)
                {
                    oppositeCheckbox.checked = false;
                    oppositeCheckbox.parentElement.classList.remove("perm-disabled");
                }
                e.parentElement.classList.add("perm-enabled");
            }
            else
                e.parentElement.classList.remove("perm-enabled");
            event.stopPropagation();
        });
    }
});

document.querySelectorAll(".checkbox-perm-false").forEach((e) => {
    if(e.hasAttribute("data-perm"))
    {
        checkboxes[false][e.getAttribute("data-perm")] = e;
        e.addEventListener("click", (event) => {
            event.stopPropagation();
            if(e.checked)
            {
                const oppositeCheckbox = checkboxes[true][e.getAttribute("data-perm")];
                if(oppositeCheckbox.checked)
                {
                    oppositeCheckbox.checked = false;
                    oppositeCheckbox.parentElement.classList.remove("perm-enabled");
                }
                e.parentElement.classList.add("perm-disabled");
            }
            else
                e.parentElement.classList.remove("perm-disabled");
        });
    }
});

document.querySelectorAll(".checkbox-perm-true").forEach((e) => {
    if(e.checked)
    {
        const oppositeCheckbox = checkboxes[false][e.getAttribute("data-perm")];
        if(oppositeCheckbox.checked)
        {
            oppositeCheckbox.checked = false;
            oppositeCheckbox.parentElement.classList.remove("perm-disabled");
        }
        e.parentElement.classList.add("perm-enabled");
    }
    else
        e.parentElement.classList.remove("perm-enabled");
})

document.querySelectorAll(".checkbox-perm-false").forEach((e) => {
    if(e.checked)
    {
        const oppositeCheckbox = checkboxes[true][e.getAttribute("data-perm")];
        if(oppositeCheckbox.checked)
        {
            oppositeCheckbox.checked = false;
            oppositeCheckbox.parentElement.classList.remove("perm-enabled");
        }
        e.parentElement.classList.add("perm-disabled");
    }
    else
        e.parentElement.classList.remove("perm-disabled");
})

document.querySelectorAll("#permission-table-editable > tbody > tr > td:nth-last-child(2), #permission-table-editable > tbody > tr > td:last-child").forEach(function(e) {
    e.addEventListener("click", function() {
        e.querySelector("input").click();
    });
});