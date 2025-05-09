// Base de datos de canciones (simulada)
const songs = [
    // Los datos se cargarán desde la base de datos real
    // Formato: {artist, name, ppm, year, audioUrl}
];

let currentAudio = null;
let currentAudioUrl = null;
let currentAudioButton = null;

// Cargar las canciones desde la base de datos
fetch('get_songs.php')
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data)) {
            songs.push(...data);
        } else {
            console.error('Respuesta inesperada:', data);
        }
    })
    .catch(error => console.error('Error cargando canciones:', error));

// Elementos del DOM
const ppmSlider = document.getElementById('ppmSlider');
const ppmValue = document.getElementById('ppmValue');
const startButton = document.getElementById('startSimulation');
const recommendationsContainer = document.getElementById('songRecommendations');
const volumeSlider = document.getElementById('volumeSlider');
const stopSongButton = document.getElementById('stopSong');

let isSimulating = false;
let simulationInterval;

// Medidor de volumen
if (volumeSlider) {
    volumeSlider.addEventListener('input', function() {
        if (currentAudio) {
            currentAudio.volume = volumeSlider.value / 100;
        }
    });
}

// Actualizar el volumen al reproducir nueva canción
function setAudioVolume() {
    if (currentAudio && volumeSlider) {
        currentAudio.volume = volumeSlider.value / 100;
    }
}

// Actualizar el valor mostrado cuando se mueve el slider
ppmSlider.addEventListener('input', (e) => {
    updatePPMDisplay(e.target.value);
    updateRecommendations(e.target.value);
});

// Iniciar/Detener simulación
startButton.addEventListener('click', () => {
    if (!isSimulating) {
        startSimulation();
        startButton.textContent = 'Detener Simulación';
        startAutoPlaySongs();
    } else {
        stopSimulation();
        startButton.textContent = 'Iniciar Simulación';
        stopAutoPlaySongs();
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
        }
        showStopButton(false);
    }
    isSimulating = !isSimulating;
});

let autoPlayActive = false;
let autoPlayTimeout = null;

function startAutoPlaySongs() {
    autoPlayActive = true;
    playNextAutoSong();
}

function stopAutoPlaySongs() {
    autoPlayActive = false;
    if (autoPlayTimeout) clearTimeout(autoPlayTimeout);
}

function playNextAutoSong() {
    if (!autoPlayActive) return;
    // Tomar el BPM actual simulado del slider
    const currentPPM = parseInt(ppmSlider.value);
    // Buscar canciones similares
    const matchingSongs = songs
        .filter(song => Math.abs(song.ppm - currentPPM) <= 5)
        .sort((a, b) => Math.abs(a.ppm - currentPPM) - Math.abs(b.ppm - currentPPM));
    if (matchingSongs.length === 0) {
        // Si no hay ninguna, buscar la más cercana
        const closest = songs.slice().sort((a, b) => Math.abs(a.ppm - currentPPM) - Math.abs(b.ppm - currentPPM))[0];
        if (!closest) return;
        playSongAuto(closest.audioUrl);
    } else {
        // Elegir una aleatoria entre las más cercanas
        const song = matchingSongs[Math.floor(Math.random() * Math.min(3, matchingSongs.length))];
        playSongAuto(song.audioUrl);
    }
}

function playSongAuto(audioUrl) {
    // Buscar canción por URL
    const song = songs.find(s => s.audioUrl === audioUrl);
    // Detener cualquier audio anterior
    if (currentAudio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
    }
    currentAudio = new Audio(audioUrl);
    currentAudioUrl = audioUrl;
    setAudioVolume();
    currentAudio.play();
    showStopButton(true);
    showNowPlaying(song, currentAudio);
    addToHistory(song);
    currentAudio.onended = () => {
        hideNowPlaying();
        autoPlayTimeout = setTimeout(() => {
            playNextAutoSong();
        }, 1000);
    };
}

function startSimulation() {
    let direction = 1;
    let currentPPM = parseInt(ppmSlider.value);
    let intensityFactor = 1;
    
    simulationInterval = setInterval(() => {
        // Simular variaciones naturales en el PPM para corredores
        const baseVariation = Math.random() * 5; // Mayor variación para reflejar ejercicio
        const variation = baseVariation * intensityFactor;
        currentPPM += variation * direction;
        
        // Cambiar dirección y ajustar intensidad ocasionalmente
        if (Math.random() < 0.15) {
            direction *= -1;
            // Ajustar factor de intensidad basado en el nivel de ejercicio
            intensityFactor = 0.8 + Math.random() * 0.4; // Varía entre 0.8 y 1.2
        }
        
        // Mantener PPM dentro del rango para corredores (100-180)
        currentPPM = Math.min(Math.max(currentPPM, 100), 180);
        
        ppmSlider.value = Math.round(currentPPM);
        updatePPMDisplay(currentPPM);
        updateRecommendations(currentPPM);
    }, 1000);
}

function stopSimulation() {
    clearInterval(simulationInterval);
}

function updatePPMDisplay(value) {
    ppmValue.textContent = Math.round(value);
}

function updateRecommendations(currentPPM) {
    // Calcular una puntuación para cada canción basada en la proximidad al PPM actual
    // Usamos una función gaussiana para dar mayor peso a las canciones más cercanas
    // y una disminución gradual de relevancia a medida que se alejan
    const scoredSongs = songs.map(song => {
        // Calcular la diferencia de PPM
        const ppmDiff = Math.abs(song.ppm - currentPPM);
        
        // Función gaussiana para calcular la puntuación de similitud
        // La puntuación será 1.0 para diferencia 0, y disminuirá exponencialmente
        // sigma determina qué tan rápido disminuye la puntuación (mayor sigma = más canciones consideradas)
        const sigma = 5; // Ajustar este valor para controlar la sensibilidad
        const score = Math.exp(-(ppmDiff * ppmDiff) / (2 * sigma * sigma));
        
        return {
            ...song,
            score: score,
            ppmDiff: ppmDiff
        };
    });
    
    // Ordenar por puntuación (de mayor a menor)
    const matchingSongs = scoredSongs
        .sort((a, b) => b.score - a.score)
        .slice(0, 3); // Mostrar las 3 canciones con mejor puntuación

    recommendationsContainer.innerHTML = matchingSongs.map(song => {
        // Calcular el porcentaje de coincidencia para mostrar (0-100%)
        const matchPercentage = Math.round(song.score * 100);
        // Determinar el color basado en la puntuación (verde para alta coincidencia, amarillo para media, rojo para baja)
        const matchColor = matchPercentage > 80 ? '#28a745' : matchPercentage > 50 ? '#ffc107' : '#dc3545';
        
        return `
        <div class="col-md-4 mb-4">
            <div class="card song-card text-white">
                <div class="card-body">
                    <h5 class="card-title">${song.name}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">${song.artist}</h6>
                    <p class="card-text">
                        Año: ${song.year}<br>
                        PPM: ${song.ppm} <small class="text-muted">(${song.ppmDiff > 0 ? '+' : ''}${song.ppmDiff})</small><br>
                        <span style="color: ${matchColor}; font-weight: bold;">
                            Coincidencia: ${matchPercentage}%
                        </span>
                    </p>
                    <div class="progress mb-2" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" style="width: ${matchPercentage}%; background-color: ${matchColor};" 
                             aria-valuenow="${matchPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <button class="btn btn-outline-light mt-2" onclick="playSong('${song.audioUrl}', this)">
                        <i class="bi bi-play-fill"></i> Reproducir
                    </button>
                </div>
            </div>
        </div>
        `;
    }).join('');
}

function playSong(audioUrl, buttonElement) {
    // Si se está reproduciendo otra canción, detenerla
    if (currentAudio && currentAudioUrl !== audioUrl) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        if (currentAudioButton) {
            currentAudioButton.innerHTML = '<i class="bi bi-play-fill"></i> Reproducir';
        }
        currentAudio = null;
    }

    // Si no hay audio o es una canción diferente, crear nuevo Audio
    if (!currentAudio || currentAudioUrl !== audioUrl) {
        currentAudio = new Audio(audioUrl);
        currentAudioUrl = audioUrl;
        currentAudioButton = buttonElement;
        setAudioVolume();
        currentAudio.play();
        buttonElement.innerHTML = '<i class="bi bi-pause-fill"></i> Pausar';
        showStopButton(true);
        currentAudio.onended = () => {
            buttonElement.innerHTML = '<i class="bi bi-play-fill"></i> Reproducir';
            showStopButton(false);
        };
        return;
    }

    // Si está pausado, reanudar
    if (currentAudio.paused) {
        currentAudio.play();
        buttonElement.innerHTML = '<i class="bi bi-pause-fill"></i> Pausar';
        showStopButton(true);
    } else {
        // Si está sonando, pausar
        currentAudio.pause();
        buttonElement.innerHTML = '<i class="bi bi-play-fill"></i> Reproducir';
        showStopButton(true);
    }
}

// Mostrar/ocultar el botón de detener según haya audio
function showStopButton(show) {
    if (stopSongButton) {
        stopSongButton.style.display = show ? '' : 'none';
    }
}

if (stopSongButton) {
    stopSongButton.addEventListener('click', function() {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            stopAutoPlaySongs();
        }
        hideNowPlaying();
        showStopButton(false);
    });
}

// --- NUEVO: Mostrar canción en reproducción y controles ---
let nowPlayingSong = null;
const nowPlayingCard = document.getElementById('nowPlayingCard');
const nowPlayingTitle = document.getElementById('nowPlayingTitle');
const nowPlayingArtist = document.getElementById('nowPlayingArtist');
const nowPlayingYear = document.getElementById('nowPlayingYear');
const nowPlayingPPM = document.getElementById('nowPlayingPPM');
const nowPlayingCurrent = document.getElementById('nowPlayingCurrent');
const nowPlayingDuration = document.getElementById('nowPlayingDuration');
const nowPlayingSeek = document.getElementById('nowPlayingSeek');
const nowPlayingPause = document.getElementById('nowPlayingPause');
const nowPlayingVolume = document.getElementById('nowPlayingVolume');
const nowPlayingSkip = document.getElementById('nowPlayingSkip');
const nowPlayingPrev = document.getElementById('nowPlayingPrev');

function showNowPlaying(song, audio) {
    if (!song) {
        nowPlayingCard.style.display = 'none';
        return;
    }
    nowPlayingSong = song;
    nowPlayingTitle.textContent = song.name;
    nowPlayingArtist.textContent = song.artist;
    nowPlayingYear.textContent = song.year ? `Año: ${song.year}` : '';
    nowPlayingPPM.textContent = song.ppm || '-';
    nowPlayingCard.style.display = '';
    // Actualizar duración cuando esté disponible
    audio.onloadedmetadata = () => {
        nowPlayingDuration.textContent = formatTime(audio.duration);
        nowPlayingSeek.max = Math.floor(audio.duration);
    };
    // Actualizar tiempo actual
    audio.ontimeupdate = () => {
        nowPlayingCurrent.textContent = formatTime(audio.currentTime);
        nowPlayingSeek.value = Math.floor(audio.currentTime);
    };
    // Permitir buscar
    nowPlayingSeek.oninput = function() {
        if (audio.duration) {
            audio.currentTime = this.value;
        }
    };
    // Inicializar valores
    nowPlayingCurrent.textContent = formatTime(audio.currentTime);
    nowPlayingDuration.textContent = audio.duration ? formatTime(audio.duration) : '0:00';
    nowPlayingSeek.value = 0;
    nowPlayingSeek.max = audio.duration ? Math.floor(audio.duration) : 100;
    // Volumen integrado
    nowPlayingVolume.value = Math.round(audio.volume * 100);
    nowPlayingVolume.oninput = function() {
        audio.volume = this.value / 100;
    };
    // Botón pausa/play
    nowPlayingPause.onclick = function() {
        if (audio.paused) {
            audio.play();
            nowPlayingPause.innerHTML = '<i class="bi bi-pause-fill"></i>';
        } else {
            audio.pause();
            nowPlayingPause.innerHTML = '<i class="bi bi-play-fill"></i>';
        }
    };
    audio.onplay = () => {
        nowPlayingPause.innerHTML = '<i class="bi bi-pause-fill"></i>';
    };
    audio.onpause = () => {
        nowPlayingPause.innerHTML = '<i class="bi bi-play-fill"></i>';
    };
    // Botón skip
    nowPlayingSkip.onclick = function() {
        if (autoPlayActive) {
            if (currentAudio) {
                currentAudio.onended = null; // Evita doble trigger
                currentAudio.pause();
                currentAudio.currentTime = 0;
            }
            hideNowPlaying();
            playNextAutoSong();
        }
    };
    // Botón anterior
    if (nowPlayingPrev) {
        nowPlayingPrev.onclick = function() {
            if (autoPlayActive && playedHistory.length > 1) {
                if (currentAudio) {
                    currentAudio.onended = null;
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                }
                hideNowPlaying();
                // Quitar la última canción reproducida (actual)
                playedHistory.pop();
                // Obtener la anterior
                const previousSong = playedHistory.pop();
                if (previousSong) {
                    playSongAuto(previousSong.audioUrl);
                }
            }
        };
    }
}

function hideNowPlaying() {
    nowPlayingCard.style.display = 'none';
    nowPlayingSong = null;
}

function formatTime(seconds) {
    seconds = Math.floor(seconds);
    const min = Math.floor(seconds / 60);
    const sec = seconds % 60;
    return `${min}:${sec < 10 ? '0' : ''}${sec}`;
}

// --- HISTORIAL DE REPRODUCCIÓN ---
const historyCard = document.getElementById('historyCard');
const historyList = document.getElementById('historyList');
let playedHistory = [];

function addToHistory(song) {
    if (!song) return;
    // Evitar duplicados consecutivos
    if (playedHistory.length > 0 && playedHistory[playedHistory.length-1].audioUrl === song.audioUrl) return;
    playedHistory.push(song);
    renderHistory();
}

function renderHistory() {
    if (playedHistory.length === 0) {
        historyCard.style.display = 'none';
        return;
    }
    historyCard.style.display = '';
    historyList.innerHTML = playedHistory.map(s =>
        `<li class='list-group-item bg-dark text-white py-2 px-3 border-0'>
            <strong>${s.name}</strong><br>
            <span style='font-size:0.95em;'>${s.artist} (${s.year || ''})</span>
            <span class='float-end badge bg-secondary'>${s.ppm || '-'} PPM</span>
        </li>`
    ).reverse().join(''); // Mostrar la última arriba
}
