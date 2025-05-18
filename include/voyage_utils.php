<?php

    function duree($voyageTab) {
        $jours = 0;
        foreach($voyageTab["etapes"] as $etap) {
            $jours += $etap["nb_jours"];
        }
        return $jours;
    }
    
    function voyageFromId($id) {
        $voyages = json_decode(file_get_contents('data/voyages.json'), true);
        $voy = NULL;
        foreach ($voyages as $v) {
            if ($v["id"] == $id) {
                $voy = $v;
                break;
            }
        }
        return $voy;
    }

    function voyageFromIdExt($id) {
        $voyages = json_decode(file_get_contents('../data/voyages.json'), true);
        $voy = NULL;
        foreach ($voyages as $v) {
            if ($v["id"] == $id) {
                $voy = $v;
                break;
            }
        }
        return $voy;
    }

    function prixTotal($reserv) {
        $options_select = $reserv["options_souscrites"];
        $voyage = voyageFromId($reserv["voyage_id"]);

        $prix_base = $voyage["prix"] * $reserv["nombre_personne"];

        $prix_options = 0;
        foreach ($voyage['etapes'] as $etape) {
            foreach ($etape['options'] as $option) {
                if (in_array($option["nom"], array_keys($options_select))) {
                    $quantite_option = $options_select[$option['nom']];
                    $prix_options += $option["prix"] * $quantite_option;
                }
            }
        }
        return $prix_base + $prix_options;
    }

    function prixTotalExt($reserv) {
        $options_select = $reserv["options_souscrites"];
        $voyage = voyageFromIdExt($reserv["voyage_id"]);

        $prix_base = $voyage["prix"] * $reserv["nombre_personne"];

        $prix_options = 0;
        foreach ($voyage['etapes'] as $etape) {
            foreach ($etape['options'] as $option) {
                if (in_array($option["nom"], array_keys($options_select))) {
                    $quantite_option = $options_select[$option['nom']];
                    $prix_options += $option["prix"] * $quantite_option;
                }
            }
        }
        return $prix_base + $prix_options;
    }

?>