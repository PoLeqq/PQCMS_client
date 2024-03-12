const startMessages = document.querySelector("#start-messages");

new Sortable(startMessages, {
    animation: 150,
    ghostClass: 'blue-background-class',
    onChange: function () {
        console.log(startMessages);
        console.log(startMessages.children);
        let x = 0;
        startMessages.querySelectorAll(".text-inputs").forEach(inputDiv => {
            inputDiv.querySelectorAll("input[type=text]").forEach(input => {
                input.name = `startmessages[${x}][]`;
            })
            x++;
        })
    }
});