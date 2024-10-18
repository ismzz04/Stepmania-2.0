<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titol = $_POST['titol'];
    $artista = $_POST['artista'];
    $errors = [];

    // Comprovació del fitxer de música
    if (isset($_FILES['musica']) && $_FILES['musica']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/musica/";
        $targetFile = $targetDir . basename($_FILES["musica"]["name"]);
        $musicaType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if ($musicaType == "mp3" || $musicaType == "ogg") {
            if (move_uploaded_file($_FILES["musica"]["tmp_name"], $targetFile)) {
                echo "Fitxer de música pujat correctament.<br>";
            } else {
                $errors[] = "Error pujant el fitxer de música.";
            }
        } else {
            $errors[] = "Format de música no vàlid. Només s'accepten MP3 i OGG.";
        }
    } else {
        $errors[] = "No s'ha seleccionat cap fitxer de música o hi ha hagut un error en la pujada.";
    }

    // Comprovació del fitxer de caràtula
    if (isset($_FILES['caratula']) && $_FILES['caratula']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/caratula/";
        $targetFile = $targetDir . basename($_FILES["caratula"]["name"]);
        $caratulaType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if ($caratulaType == "jpg" || $caratulaType == "png") {
            if (move_uploaded_file($_FILES["caratula"]["tmp_name"], $targetFile)) {
                echo "Fitxer de caràtula pujat correctament.<br>";
            } else {
                $errors[] = "Error pujant el fitxer de caràtula.";
            }
        } else {
            $errors[] = "Format de caràtula no vàlid. Només s'accepten JPG i PNG.";
        }
    } else {
        $errors[] = "No s'ha seleccionat cap fitxer de caràtula o hi ha hagut un error en la pujada.";
    }

    // Mostrar errors si n'hi ha
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    } else {
        echo "La cançó s'ha afegit correctament.";
    }
}
?>
