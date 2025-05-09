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
        // Ya no mostramos las recomendaciones
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
    
    // Configurar eventos del audio antes de reproducirlo
    currentAudio.onplay = () => {
        // Actualizar el botón de pausa/play cuando se reproduce el audio
        if (nowPlayingPause) {
            nowPlayingPause.innerHTML = '<i class="bi bi-pause-fill"></i>';
        }
    };
    
    currentAudio.onpause = () => {
        // Actualizar el botón de pausa/play cuando se pausa el audio
        if (nowPlayingPause) {
            nowPlayingPause.innerHTML = '<i class="bi bi-play-fill"></i>';
        }
    };
    
    currentAudio.onended = () => {
        hideNowPlaying();
        autoPlayTimeout = setTimeout(() => {
            playNextAutoSong();
        }, 1000);
    };
    
    // Reproducir el audio
    currentAudio.play()
        .catch(error => {
            console.error('Error al reproducir el audio:', error);
        });
    
    showStopButton(true);
    showNowPlaying(song, currentAudio);
    addToHistory(song);
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
    hideNowPlaying(); // Ocultar la caja de reproducción
    // Ya no es necesario ocultar las recomendaciones
}

function updatePPMDisplay(value) {
    ppmValue.textContent = Math.round(value);
}

function updateRecommendations(currentPPM) {
    // Función vacía - ya no mostramos recomendaciones
    // Esta función se mantiene para no romper las referencias existentes
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
    // Botón pausa/play - implementación mejorada
    nowPlayingPause.onclick = function() {
        if (!audio) return;
        
        try {
            if (audio.paused) {
                // Intentar reproducir el audio
                audio.play()
                    .then(() => {
                        // Éxito al reproducir
                        nowPlayingPause.innerHTML = '<i class="bi bi-pause-fill"></i>';
                    })
                    .catch(error => {
                        console.error('Error al reproducir:', error);
                        // Mantener el icono de play si hay error
                        nowPlayingPause.innerHTML = '<i class="bi bi-play-fill"></i>';
                    });
            } else {
                // Pausar el audio
                audio.pause();
                nowPlayingPause.innerHTML = '<i class="bi bi-play-fill"></i>';
            }
        } catch (error) {
            console.error('Error al manipular el audio:', error);
        }
    };
    
    // Asegurarse de que el botón de pausa/play siempre refleje el estado actual del audio
    if (audio && !audio.paused) {
        nowPlayingPause.innerHTML = '<i class="bi bi-pause-fill"></i>';
    } else {
        nowPlayingPause.innerHTML = '<i class="bi bi-play-fill"></i>';
    }
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
