<?php
function getChemin(){return __DIR__ . "\\fichier";} 
function getValide(){return getChemin() . "\\Valides.csv";}
function getInvalide(){return getChemin() . "\\Invalides.csv";}
function getVerification() {return getChemin() . "\\verification.csv";}

function main($target_file, $listId, $apikey){
    //Vérification des contacts et classification de ces derniers
    $status = verifierContactEtClasser(
        $target_file,
        getValide(),
        getInvalide()
    );
    //Suppression du fichier des adhérents
    //unlink($target_file);

    
    //Initialisation des variables
    $adherent = new Adherents();
    $allContact = $adherent->getAllContacts($listId, $apikey);

    $email = $adherent->getAllEmails($allContact);

    $nom = $adherent->getAllNames($allContact);

    $prenom = $adherent->getAllFirstNames($allContact);
    

    if ($status){
        echo "Traitement terminé :<br>";
        echo "- Valides : " . getValide() . "<br>";
        echo "- Invalides : " . getInvalide() . "<br>";

        //Récupération des colonnes qui nous intéresses
        $csvArray = getNPTMA(getValide());
        if ($csvArray) {
            //suppression du fichier valide
            //unlink(getValide());
            //Renommage des colonnes
            $csvColumnRightName = renameRightColumn($csvArray);

            //Mise au bon format des colonnes
            $csvString = arrayTOstring($csvColumnRightName);

            //Import des contacts
            $res = $adherent->addContact($csvString, $apikey, $listId);
            $httpCode = $res[0];
            $response = $res[1];
            $check = $adherent->checkContact($csvArray, $email, $nom, $prenom);
            echo $check;
            echo "- Vérification : " . getVerification() . "<br>";

            // Gestion des erreurs HTTP >= 400 (échecs)
            if ($httpCode >= 400) {
                // Lève une exception avec le code HTTP et la réponse brute
                throw new Exception("Erreur HTTP $httpCode lors de l'ajout des contacts. Réponse : $response");
            }

            // Si le code HTTP est 202, l'import a été lancé avec succès
            if ($httpCode === 202) {
                echo "Import lancé avec succès\n";
            } else {
                // Sinon on affiche le code HTTP reçu (autres réponses possibles)
                echo "Réponse HTTP $httpCode reçue\n";
            }
        }
        else {
            throw new Exception("Impossible d'ouvrir le fichier CSV, veuillez vérifier l'extension ainsi que l'intégrité des données.");
        }
    }
}
