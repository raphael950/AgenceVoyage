document.addEventListener("DOMContentLoaded", () => {
    const prixTotalElement = document.getElementById("prix_total");
    const nombrePersonnesInput = document.getElementById("nombre_personne");
    const optionsIndividuelles = document.querySelectorAll(".option-individuelle");
    const optionsGroupes = document.querySelectorAll(".option-groupe");

    const selectVoyage = document.getElementById("voyage");
    const voyageId = selectVoyage.value;


    function isVisible(element) {
        return element.offsetParent !== null;
    }

    function getNomDepuisAttr(name) {
        const match = name.match(/^options\[(.+)\]$/);
        return match ? match[1] : null;
    }
    

    async function envoyerRequetePrixTotal() {
        const nombrePersonnes = parseInt(nombrePersonnesInput.value, 10) || 0;


        const optionsInd = Array.from(optionsIndividuelles)
            .filter(isVisible)
            .map(option => ({
                nom: getNomDepuisAttr(option.name),
                quantite: parseInt(option.value, 10) || 0
            }))
            .filter(opt => opt.quantite > 0);

        const optionsGrp = Array.from(optionsGroupes)
            .filter(isVisible)
            .filter(option => option.checked)
            .map(option => getNomDepuisAttr(option.name));

        try {
            const response = await fetch("script/calcul_prix.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_personnes: nombrePersonnes,
                    options_individuelles: optionsInd,
                    options_groupes: optionsGrp,
                    voyage_id: voyageId
                })
            });

            const text = await response.text();

            console.log("Réponse du serveur :", text);

            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error("Réponse JSON invalide : " + e.message);
            }

            if (data && typeof data.prix_total === 'number') {
                prixTotalElement.textContent = `${data.prix_total.toFixed(2)} €`;
            } else {
                prixTotalElement.textContent = "Erreur de calcul";
            }

        } catch (error) {
            console.error("Erreur lors de la récupération du prix total :", error);
            prixTotalElement.textContent = "Erreur réseau ou serveur";
        }
    }

    if (nombrePersonnesInput) {
        nombrePersonnesInput.addEventListener("input", envoyerRequetePrixTotal);
    }

    optionsIndividuelles.forEach(option => {
        option.addEventListener("input", envoyerRequetePrixTotal);
    });

    optionsGroupes.forEach(option => {
        option.addEventListener("change", envoyerRequetePrixTotal);
    });

    envoyerRequetePrixTotal();
});

document.getElementsByTagName("form")[0].addEventListener("submit", function (event) {
    const dateInput = document.getElementById("date_depart");
    const errorMsg = document.getElementById("erreurdate");
    const selectedDate = new Date(dateInput.value);
    const today = new Date();

    if (today >= selectedDate) {
        event.preventDefault();
        errorMsg.textContent = "La date du départ doit être strictement supérieure à aujourd'hui.";
    } else {
        errorMsg.textContent = "";
    }
});
