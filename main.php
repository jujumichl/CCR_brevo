<?php
//Inlcusion des fichiers Utiles pour le traitement des csv et interaction avec l'API
require_once __DIR__ . '\\Utils_csv.php';
require_once __DIR__ . '\\Utils_API.php';

//Initialisation des variables
$adherent = new Adherents();
$listId = 3;
$chemin = __DIR__;
$apikey = file_get_contents($chemin . "\\API_key.txt");
$adhesion = $chemin . "\\Adhésions KKR-2024-2025(Adhésions)1(in).csv";
$valide = $chemin . "\\Valides.csv";
$invalide = $chemin . "\\Invalides.csv";
$verification = $chemin . "\\verification.csv";

//Récupération de tous les contacts présent dans une liste
$data = $adherent->getAllContacts($listId,$apikey);

//récupération de tous les emails de la liste
$email = $adherent->getAllEmails($data);

//Récupération de tous les noms et prénoms de la liste
$nom = $adherent->getAllNames($data);

$prenom = $adherent->getAllFirstNames($data);

//Vérification des contacts et classification de ces derniers
$status = verifierContactEtClasser(
    $adhesion,
    $valide,
    $invalide
);
//Suppression du fichier des adhérents
//unlink($adhesion);
if ($status){
    echo "Traitement terminé :<br>";
    echo "- Valides : $valide<br>";
    echo "- Invalides : $invalide<br>";

    //Récupération des colonnes qui nous intéresses
    $csvArray = getNPTMA($valide);
    if ($csvArray) {
        //suppression du fichier valide
        //unlink($valide);
        //Renommage des colonnes
        $csvColumnRightName = renameRightColumn($csvArray);

        //Mise au bon format des colonnes
        $csvString = arrayTOstring($csvColumnRightName);

        //Import des contacts
        $httpCode = $adherent->addContact($csvString, $apikey, $listId);

        $check = $adherent->checkContact($csvArray, $email, $nom, $prenom);

        echo "- Vérification : $verification";

        echo "<br>";
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
else {
    echo "coucou";
}
