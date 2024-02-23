export class IframeListener
{
    #data;
    #overlay;
    #rowListener;

    constructor(iframeData,overlay,rowListener) {
        this.#data = iframeData;
        this.#overlay = overlay;
        this.#rowListener = rowListener;
    }

    #getTd(innerText,classList,attributes)
    {
        const td = document.createElement("td");
        td.innerText = innerText;

        if(classList != null)
            classList.forEach((e) => {
                td.classList.add(e);
            })

        if(attributes != null)
            for(let key in attributes)
                td.setAttribute(key,attributes[key]);

        return td;
    }

    handleResponse()
    {
        if(this.#data["close_overlay"] === 1)
            this.#overlay.hideOverlay();

        if(!("iframe_name" in this.#data))
            return;

        if(this.#data["iframe_name"] === "AddUser")
        {
            if(this.#data["suc"] === 0)
            {
                addNotification(`hr-add-user`,"HR - Dodawanie użytkownika","e",this.#data["desc"]);
                return;
            }
            // addNotification(`hr-add-user${this.#data["user"]["username"]}`,"HR - Dodawanie użytkownika","s",`Dodano użytkownika ${this.#data["user"]["username"]}!`);
            addNotification(`hr-add-user${this.#data["user"]["username"]}`,"HR - Dodawanie użytkownika","s",`${this.#data["desc"]} (${this.#data["user"]["username"]})`);

            const user = this.#data["user"];

            let perms = encodeURIComponent(JSON.stringify(user["perms"]));
            if(perms === "")
                perms = "[]";
            const tr = document.createElement("tr");
            tr.classList.add("overlayLink");
            tr.setAttribute("data-overlayPath",`./overlays/editors/users/index.php?username=${user["username"]}&nickname=${user["nickname"]}&email=${user["email"]}&disabled=${user["disabled"]}&perms=${perms}`)

            tr.addEventListener("click",() => {this.#rowListener.openOverlay(tr);});
            tr.appendChild(this.#getTd(user["username"],null,{"data-username": ""}));
            tr.appendChild(this.#getTd(user["nickname"],null,{"data-nickname": ""}));
            tr.appendChild(this.#getTd(user["email"],null,{"data-email": ""}));
            let enabledText = user["disabled"] ? "Wył" : "Wł";
            let enabledClass = user["disabled"] ? "no" : "yes";
            tr.appendChild(this.#getTd(enabledText,[`enabled-${enabledClass}`],{"data-enabled": ""}));

            const closeUserSessionTd = this.#getTd("Wył",["enabled-no"],{"data-session-close": ""});
            closeUserSessionTd.addEventListener("click",(event) => {
                this.#rowListener.closeUserSession(closeUserSessionTd,event);
            });
            tr.appendChild(closeUserSessionTd);

            const deleteUserTd = this.#getTd("Usuń",["user-delete"],{"data-delete": "","data-username": user["username"]});
            deleteUserTd.addEventListener("click",(event) => {
                this.#rowListener.deleteUser(deleteUserTd,event);
            });
            tr.appendChild(deleteUserTd);

            document.querySelector("#users-table tbody").appendChild(tr);
        }
        else if(this.#data["iframe_name"] === "EditUser")
        {
            if(this.#data["suc"] === 0)
            {
                addNotification("hr-edit-user","HR - Edycja użytkownika","e",this.#data["desc"]);
                return;
            }
            document.querySelectorAll(".user-delete").forEach((e) => {
                const row = e.parentElement;
                const username = row.querySelector("td[data-username]");
                const user = this.#data["user"];

                if(username.innerText !== user["username"])
                    return;

                const perms = encodeURIComponent(JSON.stringify(user["perms"]));
                row.setAttribute("data-overlayPath",`./overlays/editors/users/index.php?username=${user["username"]}&nickname=${user["nickname"]}&email=${user["email"]}&perms=${perms}&disabled=${user["disabled"]}`);

                row.querySelector("td[data-nickname]").innerText = user["nickname"];
                row.querySelector("td[data-email]").innerText = user["email"];

                let disabled = user["disabled"] ? "Wył" : "Wł";
                let enabledRow = row.querySelector("td[data-enabled]");

                enabledRow.innerText = disabled;
                if(user["disabled"] === 1)
                {
                    enabledRow.classList.remove("enabled-yes");
                    enabledRow.classList.add("enabled-no");
                }
                else
                {
                    enabledRow.classList.remove("enabled-no");
                    enabledRow.classList.add("enabled-yes");
                }

                addNotification(`hr-edit-user${user["username"]}`,"HR - Edycja użytkownika","s",`${this.#data["desc"]} (${this.#data["user"]["username"]})`);
            })
        }
        else if(this.#data["iframe_name"] === "AddRank")
        {
            if(this.#data["suc"] === 0)
            {
                addNotification("hr-add-rank","HR - Dodawanie rangi","e",this.#data["desc"]);
                return;
            }
            // addNotification(`hr-add-user${this.#data["user"]["username"]}`,"HR - Dodawanie użytkownika","s",`Dodano użytkownika ${this.#data["user"]["username"]}!`);
            addNotification("hr-add-rank","HR - Dodawanie rangi","s",`${this.#data["desc"]} (${this.#data["rank"]["name"]})`);

            const rank = this.#data["rank"];

            let perms = encodeURIComponent(JSON.stringify(rank["perms"]));
            if(perms === "")
                perms = "[]";
            const tr = document.createElement("tr");
            tr.classList.add("overlayLink");
            tr.setAttribute("data-overlayPath",`./overlays/editors/ranks/index.php?name=${rank["name"]}&display_name=${rank["display_name"]}&priority=${rank["priority"]}&perms=${perms}`)

            tr.addEventListener("click",() => {this.#rowListener.openOverlay(tr);});
            tr.appendChild(this.#getTd(rank["name"],null,null));
            tr.appendChild(this.#getTd(rank["display_name"],null,null));
            tr.appendChild(this.#getTd(rank["priority"],null,null));

            const deleteRankTd = this.#getTd("Usuń",["rank-delete"],{"data-name": rank["name"]});
            deleteRankTd.addEventListener("click",(event) => {
                this.#rowListener.deleteRank(deleteRankTd,event);
            });
            tr.appendChild(deleteRankTd);

            document.querySelector("#ranks-table tbody").appendChild(tr);
        }
        else if(this.#data["iframe_name"] === "EditRank")
        {
            if(this.#data["suc"] === 0)
            {
                addNotification("hr-edit-rank","HR - Edycja rangi","e",this.#data["desc"]);
                return;
            }

            document.querySelectorAll(".rank-delete").forEach((e) => {
                const row = e.parentElement;
                const rankName = row.querySelector("td[data-name]");
                const rank = this.#data["rank"];

                if(rankName.innerText !== rank["name"])
                    return;

                const perms = encodeURIComponent(JSON.stringify(rank["perms"]));
                row.setAttribute("data-overlayPath",`./overlays/editors/ranks/index.php?name=${rank["name"]}&display_name=${rank["display_name"]}&priority=${rank["priority"]}&perms=${perms}`)

                row.querySelector("td[data-display_name]").innerText = rank["display_name"];
                row.querySelector("td[data-priority]").innerText = rank["priority"];

                addNotification(`hr-edit-rank${rank["name"]}`,"HR - Edycja użytkownika","s",`${this.#data["desc"]} (${rank["name"]})`);
            })
        }
        else
        {
            console.error("Otrzymano nieznany \"iframe_name\" w odpowiedzi: "+this.#data["iframeName"]);
            console.log(this.#data);
            console.log(this.#data);
        }

        return true;
    }
}
// <tr className="overlayLink" data-overlayPath="./overlays/editors/ranks/">
//     <td>${rank["name"]}</td>
//     <td>${rank["display_name"]}</td>
//     <td>${rank["priority"]}</td>
//     <td>${parent}</td>
// </tr>
async function addNotification(id,title,type,text) {
    const response = await fetch(`../scripts/notifications/NotificationManager.php?id=${id}&title=${title}&type=${type}&text=${text}&action=a`);
    if(!response.ok)
        console.error('Nie połączono z NotificationManager');
    else
        console.log(response.text());
}