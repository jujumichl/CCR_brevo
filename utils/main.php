<?php
require_once dirname(__DIR__, 1) . '\\utils\\Utils_csv.php';
require_once dirname(__DIR__, 1) . '\\utils\\Utils_API.php';

/**
 * Programme principale
 * @param mixed $target_file chemin du fichier donner dans le formulaire
 * @param mixed $listId Identifiant de la liste
 * @param mixed $apikey clé de d'accès a l'api
 * @throws \Exception Lève une erreur s'il y a un code http suppérieur a 400
 */
function main($target_file, $listId, $apikey){
    $chemin = dirname(__DIR__, 1) . '\\fichier';
    $valide = $chemin . "\\Valides.csv";
    $invalide = $chemin . "\\Invalides.csv";
    if (substr($apikey,0,8) == "xkeysib-"){

        //Vérification des contacts et classification de ces derniers
        $status = verifierContactEtClasser(
            $target_file,
            $valide,
            $invalide
        );
        
        //Initialisation des variables
        $adherent = new Adherents();
        $allContact = $adherent->getAllContacts($listId, $apikey);

        $email = $adherent->getAllEmails($allContact);

        $nom = $adherent->getAllNames($allContact);

        $prenom = $adherent->getAllFirstNames($allContact);
        

        if ($status){
            echo "Traitement terminé : <br>";
            echo "- Invalides : " . "<a class=\"button dl\"href=\"fichier\\Invalides.csv\" download=\"Fichier_Des_Invalides\">Télécharger</a>". "<br>";
            //Récupération des colonnes qui nous intéresses
            $csvArray = getNPTMA($valide);
            if ($csvArray) {
                //Renommage des colonnes
                $csvColumnRightName = renameRightColumn($csvArray);

                //Mise au bon format des colonnes
                $csvString = arrayTOstring($csvColumnRightName);

                //Import des contacts
                $res = $adherent->addContact($csvString, $apikey, $listId);
                $httpCode = $res[0];
                $response = $res[1];
                $check = $adherent->checkContact($csvArray, $email, $nom, $prenom);
                if (!empty($check)) { 
                    echo "<br>- Vérification : " . "<a class=\"button dl\" href=\"fichier\\verification.csv\" download=\"Fichier_De_Vérification\">Télécharger</a>" . "<br>";
                    echo "Attention tous les contacts n'ont pas été importer<br>";
                }
                else{
                    // Si le code HTTP est 202, l'import a été lancé avec succès
                    if ($httpCode === 202) {
                        echo "<br/>Import lancé avec succès\n";
                    } else {
                        // Sinon on affiche le code HTTP reçu (autres réponses possibles)
                        echo "Réponse HTTP $httpCode reçue\n";
                    }
                }
                // Gestion des erreurs HTTP >= 400 (échecs)
                if ($httpCode >= 400) {
                    // Lève une exception avec le code HTTP et la réponse brute
                    throw new Exception("Erreur HTTP $httpCode lors de l'ajout des contacts. Réponse : $response");
                }

                
                //suppression du fichier valide
                unlink($valide);
                //Suppression du fichier des adhérents
                unlink($target_file);
            }
            else {
                throw new Exception("Impossible d'ouvrir le fichier CSV, veuillez vérifier l'extension ainsi que l'intégrité des données.");
            }
        }
    }
    else {
        throw new Exception("Clé API incorrecte, veuillez vérifier votre clé API.");
    }
}
