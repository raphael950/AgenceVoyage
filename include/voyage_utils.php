<?php

    function duree($voyageTab) {
        $jours = 0;
        foreach($voyageTab["etapes"] as $etap) {
            $jours += $etap["nb_jours"];
        }
        return $jours;
    }

?>