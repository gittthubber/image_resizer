<?php

// Image Resizer - Ridimensiona immagini
function resizeImage($inputFile, $outputFile, $width, $height, $outputFormat = null) {
    if (!file_exists($inputFile) || !is_readable($inputFile)) {
        echo "File non trovato o non leggibile.";
        return;
    }

    $info = getimagesize($inputFile);
    $origWidth = $info[0];
    $origHeight = $info[1];
    $aspectRatio = $origWidth / $origHeight;
    if ($width / $height > $aspectRatio) {
        $width = $height * $aspectRatio;
    } else {
        $height = $width / $aspectRatio;
    }

    switch ($info['mime']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($inputFile);
            break;
        case 'image/png':
            $image = imagecreatefrompng($inputFile);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($inputFile);
            break;
        default:
            echo "Formato immagine non supportato.";
            return;
    }

    if (!$image) {
        echo "Errore nel caricamento dell'immagine.";
        return;
    }

    $resizedImage = imagecreatetruecolor($width, $height);

    if ($info['mime'] === 'image/png' || $info['mime'] === 'image/gif') {
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
    }

    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    $outputFormat = $outputFormat ?? $info['mime'];
    switch ($outputFormat) {
        case 'image/jpeg':
            imagejpeg($resizedImage, $outputFile, 90);
            break;
        case 'image/png':
            imagepng($resizedImage, $outputFile);
            break;
        case 'image/gif':
            imagegif($resizedImage, $outputFile);
            break;
        default:
            echo "Formato di output non supportato.";
            return;
    }

    imagedestroy($image);
    imagedestroy($resizedImage);
    echo "Immagine ridimensionata: $outputFile";
}

?>
