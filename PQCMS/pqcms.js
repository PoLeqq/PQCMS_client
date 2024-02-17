window.addEventListener("load",() => {
    let style = document.createElement("style");
    let styleCode = "";

    document.querySelectorAll(".pqcms-custom-color").forEach((e) => {
        if(!e.hasAttribute("data-pqcms-custom-color"))
        {
            console.error("Element below has not set \"data-pqcms-custom-color\" attribute. Therefore color of this " +
                "element cannot be changed.");
            console.error(e);
            return;
        }

        let color = e.getAttribute("data-pqcms-custom-color");
        let id = uniqid("pqcms-custom-color-");
        e.id = id;

        styleCode += `
#${id} {
    color: ${color};
}

#${id} * {
    color: ${color};
}

`;
    })
    style.innerHTML = styleCode;
    document.querySelector("head").appendChild(style);
});

function uniqid(prefix = "", random = false)
{
    const sec = Date.now() * 1000 + Math.random() * 1000;
    const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
    return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}`:""}`;
}