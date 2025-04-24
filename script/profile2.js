/*var a = document.querySelectorAll(" input");
a[0].value="enter";


// <span onclick=“alert( ‘Click Event’ )”>Click me</span>
a.addEventListener('click', myfunction);

*/



document.addEventListener("DOMContentLoaded", function () {
    var fields = document.querySelectorAll(".editable-field");
    var submitButton = document.getElementById("submit-button");
    var isModified = false;

    fields.forEach(function (field) {
        var input = field.querySelector("input, select");
        var editButton = field.querySelector(".edit-button");
        var cancelButton = field.querySelector(".cancel-button");
        var validateButton = field.querySelector(".validate-button");

        editButton.addEventListener("click", function () {
            input.disabled = false;
            editButton.style.display = "none";
            cancelButton.style.display = "inline-block";
            validateButton.style.display = "inline-block";
        });

        cancelButton.addEventListener("click", function () {
            input.value = input.dataset.originalValue;
            input.disabled = true;
            cancelButton.style.display = "none";
            validateButton.style.display = "none";
            editButton.style.display = "inline-block";
        });

        validateButton.addEventListener("click", function () {
            input.dataset.originalValue = input.value;
            input.disabled = true;
            cancelButton.style.display = "none";
            validateButton.style.display = "none";
            editButton.style.display = "inline-block";

            // verif si au moins un champ a été validé
            isModified = Array.from(fields).some(function (field) {
                var fieldInput = field.querySelector("input, select");
                return fieldInput.value !== fieldInput.dataset.originalValue;
            });

            // affiche le bouton "soumettre" que si une modification a été validée
            submitButton.style.display = isModified ? "block" : "none";
        });
    });
});