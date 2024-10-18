<?php
// carregar la cançó actual
$id = $_POST['id'];
$playlist = json_decode(file_get_contents('playlist.json'), true);
$canco = array_filter($playlist, fn($song) => $song['id'] === $id);

if (empty($canco)) {
    echo "Cançó no trobada.";
    exit;
}

$canco = reset($canco); // Obtenim la cançó

// Si l'usuari ha modificat els camps
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validacions com al formulari d'afegir cançó
    $errors = [];
    $title = $_POST['title'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $gameData = $_POST['gameData'] ?? '';

    // Validar que no es pugi fitxer de joc i dades alhora
    if (!empty($_FILES['game']['name']) && !empty($gameData)) {
        $errors[] = "Només pots pujar un fitxer o emplenar les dades del joc, no ambdós.";
    }

    // Si no hi ha errors, esborrar i crear una nova cançó
    if (empty($errors)) {
        // Eliminar la cançó original
        $playlist = array_filter($playlist, fn($song) => $song['id'] !== $id);

        // Crear la nova cançó amb els canvis
        $newCanco = [
            'id' => $id,
            'title' => $title,
            'artist' => $artist,
            'duration' => $canco['duration'],  // Manté la duració si no es canvia el fitxer de música
            'url_song' => $_FILES['music']['name'] ? "uploads/".$_FILES['music']['name'] : $canco['url_song'],
            'url_game' => $_FILES['game']['name'] ? "uploads/".$_FILES['game']['name'] : $canco['url_game'],
            'cover' => $_FILES['cover']['name'] ? "uploads/".$_FILES['cover']['name'] : $canco['cover']
        ];

        // Guardar el nou fitxer de playlist
        $playlist[] = $newCanco;
        file_put_contents('playlist.json', json_encode($playlist));

        echo "Cançó editada correctament!";
    } else {
        foreach ($errors as $error) {
            echo "<div class='error'>{$error}</div>";
        }
    }
}
?>
