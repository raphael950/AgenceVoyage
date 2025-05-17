document.addEventListener("DOMContentLoaded", function () {
    var fields = document.querySelectorAll(".editable-field");
    var submitButton = document.getElementById("submit-button");
    var isModified = false;

    fields.forEach(function (field) {
        var input = field.querySelector("input, select");
        var editButton = field.querySelector(".edit-button");
        var cancelButton = field.querySelector(".cancel-button");
        var validateButton = field.querySelector(".validate-button");
        var originalValue = input.value;

        editButton.addEventListener("click", function () {
            input.disabled = false;
            editButton.style.display = "none";
            cancelButton.style.display = "inline-block";
            validateButton.style.display = "inline-block";
        });

        cancelButton.addEventListener("click", function () {
            input.value = originalValue;
            input.disabled = true;
            cancelButton.style.display = "none";
            validateButton.style.display = "none";
            editButton.style.display = "inline-block";
        });

        validateButton.addEventListener("click", function () {
            originalValue = input.value;
            input.disabled = true;
            cancelButton.style.display = "none";
            validateButton.style.display = "none";
            editButton.style.display = "inline-block";

            // verif si au moins un champ a été validé
            isModified = Array.from(fields).some(function (field) {
                var fieldInput = field.querySelector("input, select");
                return fieldInput.value !== originalValue;
            });

            // affiche le bouton "soumettre" que si une modification a été validée
            submitButton.style.display = isModified ? "block" : "none";
        });

        // par définition les données ne peuvent pas etre envoyée en post quand il y a 'disabled'
        // on décide donc que, lorsque l'user sumbit, on passe tout les champ en non 'disabled'
        // ce qui nous permet donc d'envoyer les données en post normalement :        
        submitButton.addEventListener("click", function () {
            fields.forEach(function (field) {
                var input = field.querySelector("input, select");
                input.disabled = false;
                console.log("oaoaoa");
            });
        });
        
    });

    // PHASE 4 :
    // save des valeurs originales
    var originalValues = {};
    fields.forEach(function (field) {
        var input = field.querySelector("input, select");
        if (input) originalValues[input.name] = input.value;
    });
    
// });

    document.getElementsByTagName("form")[0].addEventListener("submit", function (event) {
        const dateInput = document.getElementById("date");
        const errorMsg = document.getElementById("erreurdate");
        const selectedDate = new Date(dateInput.value);
        const today = new Date();

        if(today <= selectedDate){
            event.preventDefault(); // empeche le submit du form
            errorMsg.textContent = "La date de naissance doit être strictement inférieure à aujourd'hui. (bonjour Marty Mcfly)";
            var fields = document.querySelectorAll(".editable-field");
            fields.forEach(function (field) {   // pour eviter que les champs restent activés
                var input = field.querySelector("input, select");
                input.disabled = true;
            });
        }
        else errorMsg.textContent = "";


        // PHASE 4 :
        event.preventDefault();
        const form = event.target;              // = le formulaire
        const formData = new FormData(form);    // = objet avec les valeurs du formulaire

        // on applique les conventions pour les noms des variables/fonctions pour ce 1er exemple
        async function editProfile(){
            try{
                const response = await fetch("script/edit_trigger.php", {
                    method:"POST",
                    /*headers: {
                        "Content-Type": "application/json", // ce que j'envoie, c'est du json
                    },*/
                    body: formData // contenu de la requete json (les valeurs du formulaire)
                });
                const result = await response.json();
                console.log(formData);

                if(result.success == true){
                    console.log("result.success == true");
                    for (var name in originalValues) {
                        originalValues[name] = form.elements[name].value;   // application des nouvelles valeurs
                    }
                    errorMsg.textContent = "Modifications enregistrées";
                }
                else{
                    console.log("result.success X true");
                    for (var name in originalValues) {
                        form.elements[name].value = originalValues[name];   // retour aux anciennes valeurs
                    }
                    errorMsg.textContent = "Erreur serveur. Modifications annulées";
                }
                
            }
            catch(error){
                console.log("error");
                for (var name in originalValues) {
                        form.elements[name].value = originalValues[name];   // idem
                }
                errorMsg.textContent = "Erreur réseau. Modifications annulées";
            }
        }

        editProfile();

        var fields = document.querySelectorAll(".editable-field");
        fields.forEach(function (field) {   // pour eviter que les champs restent activés
            var input = field.querySelector("input, select");
            input.disabled = true;
        });

        submitButton.style.display = "none";
    });

});

// https://developer.mozilla.org/fr/docs/Web/API/Window/fetch
// http://callbackhell.com/