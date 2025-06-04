<?php
function upload(): array|bool{
    $target_dir = dirname(__DIR__, 1) . "\\fichier\\";

    $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
    $type = array("csv");
    $csvFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    ///ne pas mettre le meme nom plutôt un id (fiche de frais)
    $target_file = $target_dir . date('Yms_His') . '.' . $csvFileType; 
    
    // Check if CSV file is a actual CSV or fake CSV
    if (is_uploaded_file($tmp_name) && in_array($csvFileType, $type)){
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        $res = array(true, $target_file);
        return $res;
    }
    else {
        return false;
    }
}
$upload = upload();