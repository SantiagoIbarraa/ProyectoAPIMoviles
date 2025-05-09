<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Reproductor Inteligente</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <header class="bg-dark text-white py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Reproductor Inteligente</h2>
            <div>
                <a href="index.html" class="btn btn-outline-light me-2">
                    <i class="bi bi-music-player me-2"></i>Ir al Reproductor
                </a>
                <a href="list_songs.php" class="btn btn-outline-light">
                    <i class="bi bi-music-note-list me-2"></i>Ver Canciones
                </a>
            </div>
        </div>
    </header>

    <div class="container py-5 main-container">
        <h1 class="page-title">
            <i class="bi bi-gear-fill me-2"></i>Panel de Administración
        </h1>
        
        <!-- Formulario para agregar canciones -->
        <div class="add-song-form">
            <h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Agregar Nueva Canción</h3>
            
            <form id="addSongForm" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="songTitle" class="form-label">Título de la Canción</label>
                        <input type="text" class="form-control" id="songTitle" name="songTitle" required>
                    </div>
                    <div class="col-md-6">
                        <label for="songArtist" class="form-label">Artista</label>
                        <input type="text" class="form-control" id="songArtist" name="songArtist" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="songYear" class="form-label">Año</label>
                        <input type="number" class="form-control" id="songYear" name="songYear" min="1900" max="2025">
                    </div>
                    <div class="col-md-6">
                        <label for="songPPM" class="form-label">PPM (Pulsos Por Minuto)</label>
                        <input type="number" class="form-control" id="songPPM" name="songPPM" min="1" max="300">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-3">Parámetros Tradicionales</h5>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="songEnergy" class="form-label">Energía: <span id="energyValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songEnergy" name="songEnergy" min="0" max="1" step="0.05" value="0.5">
                            </div>
                            <div class="col-md-6">
                                <label for="songDanceability" class="form-label">Bailabilidad: <span id="danceabilityValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songDanceability" name="songDanceability" min="0" max="1" step="0.05" value="0.5">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="songHappiness" class="form-label">Felicidad: <span id="happinessValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songHappiness" name="songHappiness" min="0" max="1" step="0.05" value="0.5">
                            </div>
                            <div class="col-md-6">
                                <label for="songInstrumentalness" class="form-label">Instrumentalidad: <span id="instrumentalnessValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songInstrumentalness" name="songInstrumentalness" min="0" max="1" step="0.05" value="0.5">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Características Avanzadas</h5>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="songDynamics" class="form-label">Dinámica: <span id="dynamicsValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songDynamics" name="songDynamics" min="0" max="1" step="0.05" value="0.5">
                                <small class="text-muted d-block">Variación de volumen</small>
                            </div>
                            <div class="col-md-6">
                                <label for="songBrightness" class="form-label">Brillo: <span id="brightnessValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songBrightness" name="songBrightness" min="0" max="1" step="0.05" value="0.5">
                                <small class="text-muted d-block">Presencia de agudos</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="songComplexity" class="form-label">Complejidad: <span id="complexityValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songComplexity" name="songComplexity" min="0" max="1" step="0.05" value="0.5">
                                <small class="text-muted d-block">Variación musical</small>
                            </div>
                            <div class="col-md-6">
                                <label for="songRhythm" class="form-label">Ritmo: <span id="rhythmValue" class="slider-value">0.5</span></label>
                                <input type="range" class="form-range" id="songRhythm" name="songRhythm" min="0" max="1" step="0.05" value="0.5">
                                <small class="text-muted d-block">Intensidad rítmica</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="songFile" class="form-label">Archivo de Audio</label>
                    <input type="file" class="form-control" id="songFile" name="songFile" accept=".mp3,.wav,.ogg" required>
                    <div id="analysisStatus" class="mt-2 d-none">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Analizando...</span>
                            </div>
                            <span>Analizando audio...</span>
                        </div>
                    </div>
                    <div class="form-text text-light">Formatos aceptados: MP3, WAV, OGG (Máx. 20MB)</div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Canción
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Listado de canciones para administrar -->
        <div class="mb-4">
            <h3 class="mb-4"><i class="bi bi-music-note-list me-2"></i>Administrar Canciones</h3>
            
            <div class="filters-container mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="searchAdmin" class="form-control" placeholder="Buscar por título o artista...">
                    </div>
                    <div class="col-md-3">
                        <select id="sortAdmin" class="form-select">
                            <option value="name">Ordenar por Título</option>
                            <option value="artist">Ordenar por Artista</option>
                            <option value="year">Ordenar por Año</option>
                            <option value="ppm">Ordenar por PPM</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button id="refreshSongs" class="btn btn-outline-light w-100">
                            <i class="bi bi-arrow-clockwise me-2"></i>Actualizar Lista
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive songs-table">
                <table class="table table-dark mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Artista</th>
                            <th>PPM</th>
                            <th>Año</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminSongsList">
                        <!-- Las canciones se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar esta canción? Esta acción no se puede deshacer.</p>
                    <p class="mb-0"><strong>Canción: </strong><span id="deleteSongName"></span></p>
                    <p><strong>Artista: </strong><span id="deleteSongArtist"></span></p>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast para notificaciones -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-dark text-white">
                <i class="bi bi-info-circle me-2"></i>
                <strong class="me-auto">Notificación</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-dark text-white" id="toastMessage">
                Operación completada con éxito.
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/simple-audio-analyzer.js"></script>
    <script src="js/admin-audio-analyzer.js"></script>
    <script>
        // Variables globales
        let currentAudio = null;
        let songsList = [];
        let deleteId = null;
        
        // Elementos DOM
        const addSongForm = document.getElementById('addSongForm');
        const adminSongsList = document.getElementById('adminSongsList');
        const searchAdmin = document.getElementById('searchAdmin');
        const sortAdmin = document.getElementById('sortAdmin');
        const refreshBtn = document.getElementById('refreshSongs');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        
        // Variables para el analizador de audio
        let audioAnalyzer = null;
        let analysisResults = null;
        const songFileInput = document.getElementById('songFile');
        const analysisStatus = document.getElementById('analysisStatus');
        const songPPMInput = document.getElementById('songPPM');
        
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar canciones
            loadSongs();
            
            // Inicializar el analizador de audio
            audioAnalyzer = new AdminAudioAnalyzer();
            audioAnalyzer.init();
            
            // Configurar eventos de los sliders
            setupSliders();
            
            // Configurar evento para analizar audio cuando se seleccione un archivo
            songFileInput.addEventListener('change', analyzeAudioFile);
            
            // Configurar eventos
            addSongForm.addEventListener('submit', handleAddSong);
            searchAdmin.addEventListener('input', filterSongs);
            sortAdmin.addEventListener('change', sortSongs);
            refreshBtn.addEventListener('click', loadSongs);
            confirmDeleteBtn.addEventListener('click', confirmDelete);
        });
        
        // Función para analizar el archivo de audio seleccionado
        async function analyzeAudioFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            try {
                // Mostrar indicador de análisis
                analysisStatus.classList.remove('d-none');
                
                // Analizar el archivo
                analysisResults = await audioAnalyzer.analyzeAudioFile(file);
                
                // Actualizar los controles del formulario con los resultados
                updateFormWithAnalysisResults(analysisResults);
                
                // Ocultar indicador de análisis
                analysisStatus.classList.add('d-none');
                
                // Mostrar mensaje de éxito
                showToast('Análisis de audio completado con éxito');
            } catch (error) {
                console.error('Error al analizar el archivo de audio:', error);
                analysisStatus.classList.add('d-none');
                showToast('Error al analizar el archivo de audio. Se utilizarán valores por defecto.');
            }
        }
        
        // Función para actualizar el formulario con los resultados del análisis
        function updateFormWithAnalysisResults(results) {
            if (!results) return;
            
            // Actualizar BPM
            if (results.bpm && songPPMInput) {
                songPPMInput.value = Math.round(results.bpm);
            }
            
            // Actualizar parámetros tradicionales
            updateSliderValue('songEnergy', results.energy);
            updateSliderValue('songDanceability', results.danceability);
            updateSliderValue('songHappiness', results.happiness);
            updateSliderValue('songInstrumentalness', results.instrumentalness);
            
            // Actualizar características avanzadas
            updateSliderValue('songDynamics', results.dynamics);
            updateSliderValue('songBrightness', results.brightness);
            updateSliderValue('songComplexity', results.complexity);
            updateSliderValue('songRhythm', results.rhythm);
        }
        
        // Función para actualizar el valor de un control deslizante
        function updateSliderValue(sliderId, value) {
            const slider = document.getElementById(sliderId);
            if (!slider) return;
            
            // Establecer el valor (asegurarse de que esté en el rango [0,1])
            const normalizedValue = Math.max(0, Math.min(1, value));
            slider.value = normalizedValue;
            
            // Actualizar el texto del valor
            const valueId = sliderId + 'Value';
            const valueEl = document.getElementById(valueId);
            if (valueEl) {
                valueEl.textContent = normalizedValue;
            }
            
            // Disparar el evento input para actualizar cualquier listener
            const event = new Event('input', { bubbles: true });
            slider.dispatchEvent(event);
        }
        
        // Función para cargar las canciones
        function loadSongs() {
            fetch('admin_get_songs.php')
                .then(response => response.json())
                .then(data => {
                    songsList = data;
                    renderSongs(songsList);
                })
                .catch(error => {
                    console.error('Error al cargar las canciones:', error);
                    showToast('Error al cargar las canciones. Por favor, intenta de nuevo.');
                });
        }
        
        // Función para renderizar las canciones en la tabla
        function renderSongs(songs) {
            adminSongsList.innerHTML = '';
            
            if (songs.length === 0) {
                adminSongsList.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="bi bi-music-note-beamed me-2"></i>No se encontraron canciones
                        </td>
                    </tr>
                `;
                return;
            }
            
            songs.forEach(song => {
                const row = document.createElement('tr');
                
                // Crear el tooltip con las características avanzadas
                let tooltipContent = '';
                
                // Parámetros tradicionales
                tooltipContent += `<div class="mb-2"><strong>Parámetros Tradicionales:</strong></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-1"><span>Energía:</span> <span>${formatPercentage(song.energy)}</span></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-1"><span>Bailabilidad:</span> <span>${formatPercentage(song.danceability)}</span></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-1"><span>Felicidad:</span> <span>${formatPercentage(song.happiness)}</span></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-3"><span>Instrumentalidad:</span> <span>${formatPercentage(song.instrumentalness)}</span></div>`;
                
                // Características avanzadas
                tooltipContent += `<div class="mb-2"><strong>Características Avanzadas:</strong></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-1"><span>Dinámica:</span> <span>${formatPercentage(song.dynamics)}</span></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-1"><span>Brillo:</span> <span>${formatPercentage(song.brightness)}</span></div>`;
                tooltipContent += `<div class="d-flex justify-content-between mb-1"><span>Complejidad:</span> <span>${formatPercentage(song.complexity)}</span></div>`;
                tooltipContent += `<div class="d-flex justify-content-between"><span>Ritmo:</span> <span>${formatPercentage(song.rhythm)}</span></div>`;
                
                row.setAttribute('data-bs-toggle', 'tooltip');
                row.setAttribute('data-bs-html', 'true');
                row.setAttribute('data-bs-title', tooltipContent);
                row.classList.add('song-row');
                
                row.innerHTML = `
                    <td class="song-title">${song.name}</td>
                    <td class="song-artist">${song.artist}</td>
                    <td><span class="badge badge-ppm">${song.ppm || 'N/A'}</span></td>
                    <td class="song-year">${song.year || 'N/A'}</td>
                    <td>
                        <div class="d-flex">
                            <button class="btn-action play-btn" onclick="playSong(this, '${song.audioUrl}')">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="openDeleteModal(${song.id}, '${song.name}', '${song.artist}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                adminSongsList.appendChild(row);
            });
            
            // Inicializar los tooltips de Bootstrap
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover',
                    placement: 'left',
                    customClass: 'song-tooltip'
                });
            });
        }
        
        // Formatear porcentaje para mostrar en la interfaz
        function formatPercentage(value) {
            if (value === null || value === undefined) {
                return 'N/A';
            }
            return Math.round(value * 100) + '%';
        }
        
        // Configurar los sliders
        function setupSliders() {
            const sliders = [
                // Parámetros tradicionales
                { id: 'songEnergy', valueId: 'energyValue' },
                { id: 'songDanceability', valueId: 'danceabilityValue' },
                { id: 'songHappiness', valueId: 'happinessValue' },
                { id: 'songInstrumentalness', valueId: 'instrumentalnessValue' },
                
                // Nuevas características avanzadas
                { id: 'songDynamics', valueId: 'dynamicsValue' },
                { id: 'songBrightness', valueId: 'brightnessValue' },
                { id: 'songComplexity', valueId: 'complexityValue' },
                { id: 'songRhythm', valueId: 'rhythmValue' }
            ];
            
            sliders.forEach(slider => {
                const sliderEl = document.getElementById(slider.id);
                const valueEl = document.getElementById(slider.valueId);
                
                if (sliderEl && valueEl) {
                    valueEl.textContent = sliderEl.value;
                    
                    sliderEl.addEventListener('input', function() {
                        valueEl.textContent = this.value;
                    });
                }
            });
        }
        
        // Manejar el envío del formulario para agregar canción
        function handleAddSong(e) {
            e.preventDefault();
            
            const formData = new FormData(addSongForm);
            
            // Si tenemos resultados del análisis, asegurarse de que se incluyan en el formulario
            // SOLO para los parámetros tradicionales (energía, bailabilidad, felicidad, instrumentalidad)
            if (analysisResults) {
                // Verificar si el usuario ha modificado manualmente los valores
                const userModifiedValues = {
                    ppm: formData.get('songPPM') !== songPPMInput.defaultValue,
                    energy: formData.get('songEnergy') !== document.getElementById('songEnergy').defaultValue,
                    danceability: formData.get('songDanceability') !== document.getElementById('songDanceability').defaultValue,
                    happiness: formData.get('songHappiness') !== document.getElementById('songHappiness').defaultValue,
                    instrumentalness: formData.get('songInstrumentalness') !== document.getElementById('songInstrumentalness').defaultValue
                };
                
                // Solo sobrescribir los valores que no han sido modificados manualmente
                // y solo para los parámetros tradicionales que se guardan en la BD
                if (!userModifiedValues.ppm && analysisResults.bpm) {
                    formData.set('songPPM', Math.round(analysisResults.bpm));
                }
                if (!userModifiedValues.energy && analysisResults.energy !== undefined) {
                    formData.set('songEnergy', analysisResults.energy);
                }
                if (!userModifiedValues.danceability && analysisResults.danceability !== undefined) {
                    formData.set('songDanceability', analysisResults.danceability);
                }
                if (!userModifiedValues.happiness && analysisResults.happiness !== undefined) {
                    formData.set('songHappiness', analysisResults.happiness);
                }
                if (!userModifiedValues.instrumentalness && analysisResults.instrumentalness !== undefined) {
                    formData.set('songInstrumentalness', analysisResults.instrumentalness);
                }
                
                // Eliminar las características avanzadas del FormData ya que no se guardan en la BD
                formData.delete('songDynamics');
                formData.delete('songBrightness');
                formData.delete('songComplexity');
                formData.delete('songRhythm');
            }
            
            fetch('admin_add_song.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Canción agregada correctamente');
                    addSongForm.reset();
                    setupSliders(); // Resetear los valores de los sliders
                    loadSongs(); // Recargar la lista de canciones
                    
                    // Resetear los resultados del análisis
                    analysisResults = null;
                } else {
                    showToast('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error al agregar la canción. Por favor, intenta de nuevo.');
            });
        }
        
        // Filtrar canciones
        function filterSongs() {
            const searchTerm = searchAdmin.value.toLowerCase();
            
            const filteredSongs = songsList.filter(song => {
                return song.name.toLowerCase().includes(searchTerm) || 
                       song.artist.toLowerCase().includes(searchTerm);
            });
            
            renderSongs(filteredSongs);
        }
        
        // Ordenar canciones
        function sortSongs() {
            const sortBy = sortAdmin.value;
            
            const sortedSongs = [...songsList].sort((a, b) => {
                if (sortBy === 'name' || sortBy === 'artist') {
                    return a[sortBy].localeCompare(b[sortBy]);
                } else {
                    return (a[sortBy] || 0) - (b[sortBy] || 0);
                }
            });
            
            renderSongs(sortedSongs);
        }
        
        // Abrir modal de confirmación para eliminar
        function openDeleteModal(id, name, artist) {
            deleteId = id;
            document.getElementById('deleteSongName').textContent = name;
            document.getElementById('deleteSongArtist').textContent = artist;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }
        
        // Confirmar eliminación
        function confirmDelete() {
            if (!deleteId) return;
            
            fetch('admin_delete_song.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: deleteId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Canción eliminada correctamente');
                    loadSongs(); // Recargar la lista de canciones
                    
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                    modal.hide();
                } else {
                    showToast('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error al eliminar la canción. Por favor, intenta de nuevo.');
            });
        }
        
        // Reproducir canción
        function playSong(button, audioUrl) {
            // Detener cualquier reproducción actual
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                
                // Restablecer todos los botones
                document.querySelectorAll('.play-btn').forEach(btn => {
                    btn.style.display = 'inline-flex';
                });
                document.querySelectorAll('.stop-btn').forEach(btn => {
                    btn.style.display = 'none';
                });
            }
            
            // Crear nuevo audio - asegurarse de que la ruta sea correcta
            console.log('Intentando reproducir:', audioUrl);
            
            // Si la ruta no comienza con http o https, asumimos que es una ruta local
            if (!audioUrl.startsWith('http://') && !audioUrl.startsWith('https://')) {
                // Asegurarse de que la ruta sea relativa al servidor
                if (audioUrl.startsWith('SpotiDownloader.com')) {
                    audioUrl = audioUrl; // Ya está en formato correcto
                }
            }
            
            currentAudio = new Audio(audioUrl);
            
            // Manejar errores de reproducción
            currentAudio.onerror = function() {
                console.error('Error al reproducir el audio:', audioUrl);
                showToast('Error al reproducir la canción. Verifique que el archivo exista.');
                stopSong(button.nextElementSibling);
            };
            
            currentAudio.play();
            
            // Cambiar botones
            button.style.display = 'none';
            button.nextElementSibling.style.display = 'inline-flex';
            
            // Cuando termine la reproducción
            currentAudio.onended = function() {
                stopSong(button.nextElementSibling);
            };
        }
        
        // Detener reproducción
        function stopSong(button) {
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                currentAudio = null;
            }
            
            // Cambiar botones
            button.style.display = 'none';
            button.previousElementSibling.style.display = 'inline-flex';
        }
        
        // Mostrar notificación toast
        function showToast(message) {
            const toastEl = document.getElementById('liveToast');
            document.getElementById('toastMessage').textContent = message;
            
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>
</body>
</html>
