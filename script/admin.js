/*
var myVar = setInterval(function () {
    var date = new Date();
    var time = date.toLocaleTimeString();
    document.getElementById("time").innerHTML = time;
    }, 1000);

var buttons = document.getElementsByTagName("button");

buttons.addEventListener("click", function(){

})

*/


document.addEventListener("DOMContentLoaded", function () {

    // TODO: factoriser ?

    document.querySelectorAll(".editButton").forEach(function (editButton) {
        editButton.addEventListener("click", function (event) {
            event.preventDefault(); // fix: empeche le formulaire de submit direct
            // editButton.disabled = true;
            editButton.style.backgroundColor = "grey"; // css->Js : background-color=backgroundColor
            setTimeout(function () {
                // executé après 5sec
                // editButton.style.backgroundColor = "#045F94";
                // editButton.textContent = "Retirer VIP";
                const form = editButton.closest("form");
                if (form) form.submit();
            }, 5000);
        });
    });

    document.querySelectorAll(".vipButton").forEach(function (vipButton) {
        vipButton.addEventListener("click", function (event) {
            event.preventDefault();
            // vipButton.disabled = true;
            vipButton.style.backgroundColor = "grey";
            setTimeout(function () {
                const form = vipButton.closest("form");
                // on recréé tous les champs à la main car form.submit() fait crash le php sinon
                let hiddenAction = document.createElement("input");
                hiddenAction.type = "hidden";
                hiddenAction.name = "action";
                hiddenAction.value = "vip";
                form.appendChild(hiddenAction);
                form.submit();
            }, 5000);
        });
    });

    document.querySelectorAll(".banButton").forEach(function (banButton) {
        banButton.addEventListener("click", function (event) {
            event.preventDefault();
            // banButton.disabled = true;
            banButton.style.backgroundColor = "grey";
            setTimeout(function () {
                const form = banButton.closest("form");
                let hiddenAction = document.createElement("input");
                hiddenAction.type = "hidden";
                hiddenAction.name = "action";
                hiddenAction.value = "ban";
                form.appendChild(hiddenAction);
                form.submit();
            }, 5000);
        });
    });
});