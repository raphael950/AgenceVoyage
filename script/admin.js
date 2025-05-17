document.addEventListener("DOMContentLoaded", function () {

    function multiBoutons(selecteurBouton, actionValue = null) {
        document.querySelectorAll(selecteurBouton).forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // fix: empeche le formulaire de submit direct
                button.style.backgroundColor = "grey";
                setTimeout(function () {
                    const form = button.closest("form");
                    if (!form) return;
                    if (actionValue !== null) {
                        // on recréé tous les champs à la main car form.submit() fait crash le php sinon
                        const hiddenAction = document.createElement("input");
                        hiddenAction.type = "hidden";
                        hiddenAction.name = "action";
                        hiddenAction.value = actionValue;
                        form.appendChild(hiddenAction);
                    }
                    form.submit();
                }, 5000);
            });
        });
    }

    multiBoutons(".editButton");
    multiBoutons(".vipButton", "vip");
    multiBoutons(".banButton", "ban");
});