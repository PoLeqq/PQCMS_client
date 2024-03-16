const startMessages = document.querySelector("#start-messages");

new Sortable(startMessages, {
    animation: 150,
    ghostClass: 'blue-background-class',
    onChange: function () {
        let x = 0;
        startMessages.querySelectorAll(".text-inputs").forEach(inputDiv => {
            inputDiv.querySelectorAll("input[type=text]").forEach(input => {
                input.name = `startmessages[${x}][]`;
            })
            x++;
        })
    },
    onStart: function(event) {
        // console.log(event.originalEvent.target);
        event.item.style.filter = "brightness(2) blur(1px)";
    },

    onEnd: function(event) {
        // event.item.style.filter = "";
        event.item.style.color = "";
        event.item.style.filter = "brightness(1) blur(0)";
    }
});

const botMessages = document.querySelector("#bot-messages");

new Sortable(botMessages, {
    animation: 150,
    ghostClass: 'blue-background-class',
    onChange: function () {
        let x = 0;
        botMessages.querySelectorAll(".text-inputs").forEach(inputDiv => {
            inputDiv.querySelectorAll("input[type=text]").forEach(input => {
                input.name = input.getAttribute('name').replace(/\[(\d+)]/, '[' + x + ']');
            })
            x++;
        })

        x = 0;
        botMessages.querySelectorAll(".text-inputs").forEach(inputDiv => {
            inputDiv.querySelectorAll("input[type=hidden]").forEach(input => {
                console.log(input);
                input.name = input.getAttribute('name').replace(/\[(\d+)]/, '[' + x + ']');
            })
            x++;
        })




    }
});