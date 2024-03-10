let rate = 0;

const dir = document.querySelector("#pqcms-starsystem-data-dir").value;
const redirect_url = document.querySelector("#pqcms-starsystem-data-redirect_url").value;
const redirect_min = document.querySelector("#pqcms-starsystem-data-redirect_min").value;
const redirect_new = (document.querySelector("#pqcms-starsystem-data-redirect_new").value === "1");

document.querySelector("#pqcms-starsystem-close").addEventListener("click",() => {
    pqcms_starsystem_hide();
})

document.querySelector("#pqcms-starsystem-reason-close").addEventListener("click",() => {
    pqcms_starsystem_reason_hide();
})

const pqcms_starsystem_stars = Array.from(document.querySelector("#pqcms-starsystem-stars").children);

pqcms_starsystem_stars.forEach((e) => {
    e.addEventListener("mouseover",() => {
        pqcms_starsystem_stars.forEach((star) => {
            if(star.getAttribute("data-star") <= e.getAttribute("data-star"))
                star.src = dir+"/images/star_full.svg";
            else
                star.src = dir+"/images/star_empty.svg";
        })
    })

    e.addEventListener("click", () => {
        rate = e.getAttribute("data-star");
        if(redirect_min > e.getAttribute("data-star"))
        {
            pqcms_starsystem_reason_show();
            pqcms_starsystem_hide();

            return;
        }

        fetch(`${dir}/scripts/AddRate.php`, {
            headers: {
                'Content-Type': 'application/json'
            },
            method: "POST",
            body: JSON.stringify(
                {
                    rate: rate,
                    email: "",
                    description: ""
                }
            )
        }).then((resp) => {
            resp.json().then(console.log).catch(console.error);
        }).catch(console.error);

        if(redirect_new)
            window.open(redirect_url, '_blank').focus();
        else
            location.href = redirect_url;
        pqcms_starsystem_hide();
    })
});


document.querySelector("#pqcms-starsystem-reason-submit").addEventListener("click", () => {
    if(rate === 0)
        return;

    const email = document.querySelector("#pqcms-starsystem-reason-email").value;
    const desc = document.querySelector("#pqcms-starsystem-reason-desc").value;

    if(validateEmail(email) === null) {
        const errorElement = document.querySelector("#pqcms-starsystem-reason-email-error");
        errorElement.innerText = "Nieprawidłowy adres e-mail!";
        setTimeout(() => {
            errorElement.innerText = "";
        },5000);
        return;
    }

    fetch(`${dir}/scripts/AddRate.php`, {
        headers: {
            'Content-Type': 'application/json'
        },
        method: "POST",
        body: JSON.stringify(
            {
                rate: rate,
                email: email,
                description: desc
            }
        )
    }).then((resp) => {
        resp.json().then(console.log).catch(console.error);
    }).catch(console.error);

    pqcms_starsystem_reason_hide();
})

const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};