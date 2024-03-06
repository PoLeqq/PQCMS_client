document.querySelectorAll(".addon-toggler").forEach(e => {
    const addon = e.getAttribute("data-addon");
    let data = {
        addon: addon
    }

    const checkbox = e.querySelector("input[type=checkbox]");
    if(checkbox == null)
    {
        console.error(`Input type=checkbox not found in addon element (${addon})`,e);
        return;
    }

    checkbox.addEventListener("click",(event) => {
        if(e.hasAttribute("data-addon"))
            data.token = e.getAttribute("data-token");
        data.enabled = checkbox.checked;

        if(!e.hasAttribute("data-addon"))
        {
            console.error("Element has not \"data-addon\" attribute!");
            return;
        }

        // fetch("../scripts/addons/SetAddonActivation.php", {
        fetch("../scripts/addons/SetAddonActivation.php", {
            method: "POST",
            body: JSON.stringify({
                data
            }),
            headers: {
                "Content-type": "application/json; charset=UTF-8"
            }
        }).then(r => {
            r.json().then(json => {
                console.log(e);
                if(json["suc"] === 0)
                {
                    setTimeout(() => {
                        checkbox.checked = !checkbox.checked;
                    },250);
                }
                else
                    e.setAttribute("data-token",json["token"]);
            }).catch(ex => {
                console.error(ex);
            })
        }).catch(ex => {
            console.error(ex);
        })
    })
})