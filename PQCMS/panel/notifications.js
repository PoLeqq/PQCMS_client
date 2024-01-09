const notificationIds = [];

function closeNotification(notification)
{
    if(notification.hasAttribute("data-deleted"))
        return;

    notification.style.animation = "notification-close";
    notification.style.animationDuration = ".5s";
    notification.style.animationFillMode = "forwards";

    setTimeout(() => {
        notification.remove();
    },500);

    setTimeout(() => {
        const indexToRemove = notificationIds.indexOf(notification.getAttribute("data-id"));
        if(indexToRemove !== -1)
            notificationIds.splice(indexToRemove, 1);
    },1000);

    const id = notification.getAttribute("data-id");
    fetch("scripts/notifications/NotificationManager.php?name="+id+"&action=d")
        .then(function (response) {
            if(!response.ok)
                throw new Error('Nie połączono z NotificationManager');
        })
        .catch(function(res){
            console.error(res)
        });
}


// Show notifications
document.addEventListener("load",() => {
    setTimeout(updateNotifications,0);
    setInterval(updateNotifications,3000);
})

function updateNotifications()
{
    fetch("scripts/notifications/GetNotifications.php")
        .then(function (response) {
            if (!response.ok)
                throw new Error('Nie połączono z GetNotifications');
            return response.json();
        })
        .then((json) => {
            const notifications  = document.querySelector("#pqcms-notifications");

            for(let notification in json)
            {
                if(notificationIds.includes(notification))
                    continue;

                if(json.hasOwnProperty(notification))
                {
                    const notificationElement = getNotificationElement(notification,json[notification]["title"],json[notification]["type"],json[notification]["text"]);
                    notificationElement.addEventListener("click",() => {closeNotification(notificationElement)});
                    notifications.appendChild(notificationElement);
                    notificationIds.push(notification);
                }
            }
        })
        .catch(function (res) {
            console.error(res)
        });
}

function getNotificationElement(id,title,type,text)
{
    let notificationClass;
    if(type === "e")
        notificationClass = "error";
    else if(type === "w")
        notificationClass = "warning";
    else if(type === "s")
        notificationClass = "success";
    else
        notificationClass = "info";

    const notification = document.createElement("div");
    notification.classList.add("pqcms-notification");
    notification.classList.add("pqcms-notification-"+notificationClass);
    notification.setAttribute("data-id",id);

    // Obrazek
    {
        const imageSection = document.createElement("div");
        imageSection.classList.add("pqcms-notification-image");

        const image = document.createElement("img");
        image.src = "images/"+notificationClass+".svg";
        image.alt = notificationClass;

        imageSection.appendChild(image);
        notification.appendChild(imageSection);
    }

    // Tekst
    {
        const textSection = document.createElement("div");
        textSection.classList.add("pqcms-notification-text");

        const titleElement = document.createElement("span");
        titleElement.classList.add("pqcms-notification-title");
        titleElement.innerText = title;

        textSection.appendChild(titleElement);
        textSection.append(text);

        notification.appendChild(textSection);
    }

    // "X"
    {
        const closeSection = document.createElement("div");
        closeSection.classList.add("pqcms-notification-close");
        closeSection.innerText = "x";

        notification.appendChild(closeSection);
    }

    return notification;
}