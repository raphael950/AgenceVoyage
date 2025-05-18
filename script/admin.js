document.addEventListener("DOMContentLoaded", function () {

    /* ne sert plus à rien
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
                    //form.submit();
                }, 5000);
            });
        });
    }

    multiBoutons(".editButton");
    multiBoutons(".vipButton", "vip");
    multiBoutons(".banButton", "ban");




    */

    // Ajoute un écouteur sur chaque bouton d'action
    document.querySelectorAll('.vipButton, .banButton').forEach(function(button) {
        button.addEventListener('click', async function(event) {
            event.preventDefault();
            const form = button.closest('form');
            if (!form) return;
            const formData = new FormData(form); // contient client_id 
            formData.set('action', button.value); // on ajoute l'action
            console.log(formData);                // { client_id = "4", action = "vip" } par exemple

            const spinner = document.getElementById('loading-spinner');
            spinner.style.display = "inline";
            button.style.backgroundColor = "grey";

            try {
                const response = await fetch('script/admin_trigger.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.text();
                console.log(result);

                if (response.ok) {
                    console.log("Action réussie");
                    button.style.backgroundColor = "#045F94";
                    if(button.textContent == "Donner VIP"){
                        button.textContent = "Enlever VIP";
                    }
                    else if (button.textContent == "Enlever VIP"){
                        button.textContent = "Donner VIP";
                    }
                    else if (button.textContent == "Bannir"){
                        const row = form.closest('tr');
                        row.remove();
                    }
                    spinner.style.display = "none";
                }
                else {
                    console.log("erreur " + response.status);
                    spinner.textContent = "Erreur "+response.status;
                    spinner.style.color = "red";
                    button.style.backgroundColor = "#045F94";
                }
            } catch (error) {
                console.log("erreur reseau");
                spinner.textContent = "Erreur réseau";
                spinner.style.color = "red";
            }
            finally{
                // d'après https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/finally
                // spinner.style.display = "none";
            }
        });
    });

    /*
    console.log("aaa");
    Array.from(document.getElementsByTagName("form")).forEach(function (form){
        console.log("bbb");
        form.addEventListener("click", async function(bouton){
            console.log("ccc");
            bouton.preventDefault();
            const formData = new FormData(form);
            
            if(bouton.textContent == "Modifer") return;
            try{
                const response = await fetch("script/admin_trigger.php", {
                    method:"POST",
                    body: FormData // bouton
                });
                const result = await response.text();
                console.log(formData);
                console.log(bouton);

                if(response.ok == true){
                    console.log("response.ok == true");
                }
                else{
                    console.log("erreur" + response.status);
                }
            }
            catch(error){
                console.log("error");
                // errorMsg.textContent = "Erreur réseau. Modifications annulées";                
            }

        });


    });*/

    




});