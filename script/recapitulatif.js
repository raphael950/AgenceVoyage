document.addEventListener("DOMContentLoaded", () => {
    const prixParPersonne = parseFloat(document.body.dataset.prixpersonne);
    let nombrePersonnesInput = document.getElementById("nombre_personne");
    let optionsIndividuelles = document.querySelectorAll(".option-individuelle");
    let optionsGroupes = document.querySelectorAll(".option-groupe");
    let prixTotalElement = document.getElementById("prix_total");

    function calculerPrixTotal(){
        let prixTotal = prixParPersonne * (parseInt(nombrePersonnesInput.value, 10) || 0);

        optionsIndividuelles.forEach(function(option){
            let quantite = parseInt(option.value, 10) || 0; // si pas défini, =0
            let prixOption = parseFloat(option.dataset.prix) || 0;
            prixTotal += quantite*prixOption;
        });

        optionsGroupes.forEach(function(option){
            if (option.checked) {
                let prixOption = parseFloat(option.dataset.prix) || 0;
                prixTotal += prixOption;
            }
        });

        prixTotalElement.textContent = `${prixTotal.toFixed(2)} €`; // 2 chiffre apres la virgule
    };

    if (nombrePersonnesInput) {
        nombrePersonnesInput.addEventListener("input", calculerPrixTotal);
    }
    optionsIndividuelles.forEach(function(option){
        option.addEventListener("input", calculerPrixTotal);
    });
    optionsGroupes.forEach(function(option){
        option.addEventListener("change", calculerPrixTotal);
    });

    // arrive sur la page => calcul initial du prix
    calculerPrixTotal();
});