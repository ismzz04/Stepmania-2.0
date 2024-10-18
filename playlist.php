<?php
ob_start(); // Captura la sortida abans de redirigir

// Ruta al fitxer JSON
$playlist_file = 'playlist.json';

ini_set('upload_max_filesize', '20M'); // Augmenta el límit de mida del fitxer (20M o ajusta segons sigui necessari)
ini_set('post_max_size', '25M'); // La mida total de la sol·licitud POST (ha de ser més gran que upload_max_filesize)
ini_set('memory_limit', '30M'); // Memòria màxima permès durant l'execució del script

// Recuperar dades del formulari
$title = $_POST['title'] ?? null;
$artist = $_POST['artist'] ?? null;
$duration = $_POST['duration'] ?? null;
$url_game = $_POST['url_game'] ?? null;

// Processar fitxer de la caràtula (cover)
$cover = null;
if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
    $cover_tmp_name = $_FILES['cover']['tmp_name'];
    $cover_name = basename($_FILES['cover']['name']);
    $cover_target_path = 'uploads/covers/' . $cover_name;

    if (move_uploaded_file($cover_tmp_name, $cover_target_path)) {
        $cover = $cover_target_path; // Guardar la ruta de la caràtula
    }
}

// Processar fitxer de la cançó (url_song)
$url_song = null;
if (isset($_FILES['url_song']) && $_FILES['url_song']['error'] === UPLOAD_ERR_OK) {
    $song_tmp_name = $_FILES['url_song']['tmp_name'];
    $song_name = basename($_FILES['url_song']['name']);
    $song_target_path = 'uploads/songs/' . $song_name;

    // Intentar moure el fitxer de la cançó
    if (move_uploaded_file($song_tmp_name, $song_target_path)) {
        $url_song = $song_target_path; // Guardar la ruta del fitxer de la cançó
    } else {
        echo "Error: No s'ha pogut moure el fitxer de música.";
    }
} else {
    // Depurar errors de pujada
    if (isset($_FILES['url_song']['error'])) {
        echo "Error en pujar la cançó: " . $_FILES['url_song']['error'];
    } else {
        echo "No s'ha seleccionat cap fitxer de cançó.";
    }
}

// Llegir l'actual playlist
if (file_exists($playlist_file)) {
    $playlist = json_decode(file_get_contents($playlist_file), true);
} else {
    $playlist = array(); // Si no existeix, inicialitza com a un array buit
}

// Crear una nova entrada per a la cançó
$new_song = array(
    "title" => $title,
    "artist" => $artist,
    "duration" => $duration,
    "cover" => $cover, // Ruta de la caràtula
    "url_song" => $url_song, // Ruta de la cançó
    "url_game" => $url_game
);

// Afegir la nova cançó a la playlist
$playlist[] = $new_song;

// Escriure la nova playlist al fitxer JSON
file_put_contents($playlist_file, json_encode($playlist, JSON_PRETTY_PRINT));

// Redirigir a la pàgina de la playlist
header('Location: playlist.html');
$url_song = null;
if (isset($_FILES['url_song']) && $_FILES['url_song']['error'] === UPLOAD_ERR_OK) {
    $song_tmp_name = $_FILES['url_song']['tmp_name'];
    $song_name = basename($_FILES['url_song']['name']);
    $song_target_path = 'uploads/songs/' . $song_name;

    if (move_uploaded_file($song_tmp_name, $song_target_path)) {
        $url_song = $song_target_path; // Guardar la ruta del fitxer de la cançó
    } else {
        echo "Error: No s'ha pogut moure el fitxer de música.";
    }
} else {
    switch ($_FILES['url_song']['error']) {
        case UPLOAD_ERR_INI_SIZE:
            echo "Error: El fitxer de la cançó és massa gran.";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo "Error: El fitxer de la cançó excedeix el límit especificat.";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "Error: No s'ha seleccionat cap fitxer de cançó.";
            break;
        default:
            echo "Error en pujar la cançó: " . $_FILES['url_song']['error'];
            break;
    }
    exit;  // Atura el procés si hi ha un error de pujada
}
