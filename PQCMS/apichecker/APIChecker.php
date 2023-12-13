<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>APIChecker - PQCMS</title>

    <style>
        form {
            display: flex;
            flex-direction: column;
            /*width: 50vw;*/

        }

        #apiName {
            width: 300px;
        }

    </style>
</head>
<body>
    <form>
        <h1>Sprawdź odpowiedzi sam!</h1>
        <label for="apiName">
            API:

            <select id="apiName" name="apiName">
                <optgroup label="system"></optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;version">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;GetClientVersion</option>
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;GetServerVersion</option>
                </optgroup>
                <optgroup label="website"></optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;auth">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;IsValidAuthKey</option>
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;LoginUser</option>
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;LogoutUser</option>
                </optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;hr"></optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;admin">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DoesAdminExists</option>
                </optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;user">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AddUser</option>
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GetUser</option>
                </optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;license">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;VerifyLicense</option>
                </optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;perms">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;HasPermission</option>
                </optgroup>
                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;settings">
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;GetSettings</option>
                    <option>&nbsp;&nbsp;&nbsp;&nbsp;UpdateSettings</option>
                </optgroup>
            </select>
        </label>
        <div id="inputs" style="display:flex; flex-direction: column">

        </div>
        <div>
            <label for="newInputName">
                Nowy "name" input'a
                <input id="newInputName"/>
            </label>
            <input type="button" id="addInput" value="Dodaj">
        </div>



        <input type="submit" value="Strzel API'kiem :D"/>
    </form>

    <form method="post" action="https://poleq.pl/server/api/website/perms/HasPermission.php">
        <h3>website/perms/HasPermission</h3>

<!--        <input name=""/>-->
        <input type="submit" value="Shoot!"/>
    </form>

    <script>
        let inputs = [];

        document.querySelector("#addInput").addEventListener("click", () => {
            const inputName = document.querySelector("#newInputName").value;

            if(inputName === "" || inputs.includes(inputName))
                return;
            inputs.push(inputName);

            const label = document.createElement("label");
            label.for = inputName;

            const input = document.createElement("input");
            input.name = inputName;

            label.textContent = inputName+": ";
            label.appendChild(input);

            document.querySelector("#inputs").appendChild(label);
        });
    </script>
</body>
</html>