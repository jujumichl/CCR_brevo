<?php
require_once __DIR__ . '\\vue\\header.html';
require_once __DIR__ . '\\vue\\formsBrevo.html';
require_once __DIR__ . '\\vue\\foot.html';

require_once __DIR__ . '\\utils\\Utils_csv.php';
require_once __DIR__ . '\\utils\\Utils_API.php';
include __DIR__ . '\\utils\\utils.php';

$uc = lireDonneeUrl('uc');
switch ($uc) {
    case 'upload':        
        include __DIR__ . "\\utils\\upload.php";
        if ($upload){
            $idList = is_numeric($_POST['idlist']);
            $apiKey = htmlspecialchars($_POST['API']);
            $csvPath = $upload[1];
            include __DIR__ . '\\utils\\main.php';
            main($csvPath, $idList, $apiKey);
        }
        else {
            echo "Désolé, il y a eu une erreur lors du téléchargement de votre fichier. Vérifiez que votre fichier est bien au format CSV";
        }
        break;
}

