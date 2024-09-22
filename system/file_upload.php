<?php
function fileUpload($pictures)
{
    $uploadedFiles = [];
    $errors = [];

    foreach ($pictures['tmp_name'] as $index => $tmpName) {
        if ($pictures["error"][$index] == 4) {
            $errors[] = "No file selected for one of the fields.";
            continue;
        }

        $checkIfImage = getimagesize($tmpName);
        if (!$checkIfImage) {
            $errors[] = "File at index $index is not a valid image.";
            continue;
        }

        $ext = strtolower(pathinfo($pictures['name'][$index], PATHINFO_EXTENSION));
        $pictureName = uniqid() . "." . $ext;


        $destination = "../../assets/event-images/{$pictureName}";

        if (move_uploaded_file($tmpName, $destination)) {
            $uploadedFiles[] = $pictureName;
        } else {
            $errors[] = "Error uploading file at index $index.";
        }
    }

    return [$uploadedFiles, $errors];
}
