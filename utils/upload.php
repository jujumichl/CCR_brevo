<?php
function upload(): array|bool{
    $target_dir = dirname(__DIR__, 1) . "\\fichier\\";

    ///ne pas mettre le meme nom plutôt un id (fiche de frais)
    $target_file = $target_dir . basename($_FILES['fileToUpload']['name']); 
    $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
    $type = array("csv");
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if CSV file is a actual CSV or fake CSV
    if (is_uploaded_file($tmp_name) && in_array($imageFileType, $type)){
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        $res = array(true, $target_file);
        return $res;
        
    }
    else {
        return false;
    }
}
$upload = upload();