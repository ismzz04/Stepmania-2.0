// Quan l'usuari selecciona un fitxer de música, calcula la durada
document.getElementById('url_song').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const audio = new Audio(URL.createObjectURL(file));
        audio.addEventListener('loadedmetadata', function() {
            const durationInSeconds = audio.duration;
            const minutes = Math.floor(durationInSeconds / 60);
            const seconds = Math.floor(durationInSeconds % 60);
            const durationFormatted = minutes + ":" + (seconds < 10 ? '0' + seconds : seconds);

            // Assigna la durada calculada al camp ocult
            document.getElementById('duration').value = durationFormatted;
        });
    }
});

// Validació
document.getElementById('afegirCançoForm').addEventListener('submit', function(event) {
    let titol = document.getElementById('titol').value.trim();
    let artista = document.getElementById('artista').value.trim();
    let musica = document.getElementById('url_song').files[0];
    let caratula = document.getElementById('cover').files[0];

    // Validacions addicionals
    if (!titol || !artista || !musica || !caratula) {
        alert("Títol, artista, fitxer de música i fitxer de caràtula són obligatoris!");
        event.preventDefault(); // Atura l'enviament del formulari
        return;
    }

    // Validació del tipus de fitxer
    const allowedMusicTypes = ['audio/mp3', 'audio/ogg'];
    if (musica && !allowedMusicTypes.includes(musica.type)) {
        alert("El fitxer de música ha de ser MP3 o OGG!");
        event.preventDefault();
        return;
    }

    const allowedImageTypes = ['image/jpeg', 'image/png'];
    if (caratula && !allowedImageTypes.includes(caratula.type)) {
        alert("El fitxer de caràtula ha de ser JPG o PNG!");
        event.preventDefault();
        return;
    }

    // Comprova si el fitxer de joc és un fitxer TXT, només si s'ha pujat
    const fitxerJoc = document.getElementById('url_game').files[0];
    if (fitxerJoc && fitxerJoc.type !== 'text/plain') {
        alert("El fitxer de joc ha de ser un fitxer TXT!");
        event.preventDefault();
        return;
    }

    // Validació per assegurar que no es puja fitxer i s'emplena el textarea a la vegada
    const fitxerJocTextarea = document.getElementById('fitxerJocTextarea').value.trim();
    if (fitxerJoc && fitxerJocTextarea.length > 0) {
        alert('Només pots pujar un fitxer o escriure les instruccions al textarea, no ambdós.');
        event.preventDefault();
    }
});

function addSong() {
    const title = document.getElementById('title').value;
    const artist = document.getElementById('artist').value;
    const urlSong = document.getElementById('url_song').value;
    const urlGame = document.getElementById('url_game').value;
    const duration = document.getElementById('duration').value;
    const cover = document.getElementById('cover').value;

    const newSong = {
        title: title,
        artist: artist,
        url_song: urlSong,
        url_game: urlGame,
        duration: duration,
        cover: cover,
    };

    // Afegir la nova cançó al fitxer playlist.json (això requereix un backend per fer-ho)
    // Fins que ho tinguis, farem servir localStorage
    localStorage.setItem('newSong', JSON.stringify(newSong));

    // Notificar a la playlist que hi ha una nova cançó
    window.localStorage.setItem('newSong', JSON.stringify(newSong));
    window.location.href = "playlist.html"; // Redirigeix a la playlist
}
