<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Reproductor Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Estilos base */
        body {
            background: #222;
            color: #fff;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Header y navegación */
        header {
            border-bottom: 2px solid #ff4444;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        
        /* Botones */
        .btn-danger {
            background: #ff4444;
            border: none;
            box-shadow: 0 4px 12px rgba(255, 68, 68, 0.4);
            transition: all 0.3s ease;
            border-radius: 25px;
            padding: 0.6rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .btn-danger:hover {
            background: #ff2020;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 68, 68, 0.5);
        }
        
        .btn-outline-light {
            border-radius: 20px;
            padding: 0.4rem 1.5rem;
            font-weight: 500;
            border-color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        /* Tabla de canciones */
        .songs-table {
            background: rgba(41, 41, 41, 0.7);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .songs-table th {
            background: rgba(0, 0, 0, 0.2);
            color: #ff4444;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            padding: 15px;
        }
        
        .songs-table td {
            padding: 12px 15px;
            border-color: rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }
        
        .songs-table tbody tr {
            transition: all 0.2s ease;
        }
        
        .songs-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .song-title {
            color: #ffe082;
            font-weight: 600;
        }
        
        .song-artist {
            color: #80cbc4;
        }
        
        .song-ppm {
            color: #ffab91;
            font-weight: bold;
        }
        
        .song-year {
            opacity: 0.8;
        }
        
        /* Botones de acción */
        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            transition: all 0.2s ease;
            margin-right: 5px;
        }
        
        .btn-action:hover {
            background: #ff4444;
            transform: scale(1.1);
        }
        
        /* Contenedor principal */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Título principal */
        .page-title {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }
        
        .page-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #ff4444;
            border-radius: 3px;
        }
        
        /* Filtros y búsqueda */
        .filters-container {
            background: rgba(41, 41, 41, 0.7);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .form-control {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .form-control:focus {
            background: rgba(0, 0, 0, 0.3);
            color: white;
            border-color: rgba(255, 68, 68, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(255, 68, 68, 0.25);
        }
        
        /* Badges/Etiquetas */
        .badge-ppm {
            background: linear-gradient(135deg, #ff4444, #ff8a80);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        /* Estilos adicionales para las nuevas funcionalidades */
        .toast-notification {
            min-width: 300px;
        }
        
        .options-menu .list-group-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .options-menu .list-group-item:hover {
            background-color: #333 !important;
        }
        
        /* Estilos para el formulario de agregar canción */
        .add-song-form {
            background: rgba(41, 41, 41, 0.7);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .form-label {
            color: #ff4444;
            font-weight: 600;
        }
        
        .form-range::-webkit-slider-thumb {
            background: #ff4444;
        }
        
        .form-range::-moz-range-thumb {
            background: #ff4444;
        }
        
        .slider-value {
            font-weight: bold;
            color: #ffe082;
        }
        
        /* Botón de eliminar */
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
            color: white;
        }
    </style>
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
                    <div class="col-md-3">
                        <label for="songEnergy" class="form-label">Energía: <span id="energyValue" class="slider-value">0.5</span></label>
                        <input type="range" class="form-range" id="songEnergy" name="songEnergy" min="0" max="1" step="0.05" value="0.5">
                    </div>
                    <div class="col-md-3">
                        <label for="songDanceability" class="form-label">Bailabilidad: <span id="danceabilityValue" class="slider-value">0.5</span></label>
                        <input type="range" class="form-range" id="songDanceability" name="songDanceability" min="0" max="1" step="0.05" value="0.5">
                    </div>
                    <div class="col-md-3">
                        <label for="songHappiness" class="form-label">Felicidad: <span id="happinessValue" class="slider-value">0.5</span></label>
                        <input type="range" class="form-range" id="songHappiness" name="songHappiness" min="0" max="1" step="0.05" value="0.5">
                    </div>
                    <div class="col-md-3">
                        <label for="songInstrumentalness" class="form-label">Instrumentalidad: <span id="instrumentalnessValue" class="slider-value">0.5</span></label>
                        <input type="range" class="form-range" id="songInstrumentalness" name="songInstrumentalness" min="0" max="1" step="0.05" value="0.5">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="songFile" class="form-label">Archivo de Audio</label>
                    <input type="file" class="form-control" id="songFile" name="songFile" accept="audio/*" required>
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
        
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar canciones
            loadSongs();
            
            // Configurar eventos de los sliders
            setupSliders();
            
            // Configurar eventos
            addSongForm.addEventListener('submit', handleAddSong);
            searchAdmin.addEventListener('input', filterSongs);
            sortAdmin.addEventListener('change', sortSongs);
            refreshBtn.addEventListener('click', loadSongs);
            confirmDeleteBtn.addEventListener('click', confirmDelete);
        });
        
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
        }
        
        // Configurar los sliders
        function setupSliders() {
            const sliders = [
                { id: 'songEnergy', valueId: 'energyValue' },
                { id: 'songDanceability', valueId: 'danceabilityValue' },
                { id: 'songHappiness', valueId: 'happinessValue' },
                { id: 'songInstrumentalness', valueId: 'instrumentalnessValue' }
            ];
            
            sliders.forEach(slider => {
                const sliderEl = document.getElementById(slider.id);
                const valueEl = document.getElementById(slider.valueId);
                
                valueEl.textContent = sliderEl.value;
                
                sliderEl.addEventListener('input', function() {
                    valueEl.textContent = this.value;
                });
            });
        }
        
        // Manejar el envío del formulario para agregar canción
        function handleAddSong(e) {
            e.preventDefault();
            
            const formData = new FormData(addSongForm);
            
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
