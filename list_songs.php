<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Canciones - Reproductor Inteligente</title>
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
    </style>
</head>
<body>
    <header class="bg-dark text-white py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Reproductor Inteligente</h2>
            <div>
                <a href="index.html" class="btn btn-outline-light">
                    <i class="bi bi-music-player me-2"></i>Volver al Reproductor
                </a>
            </div>
        </div>
    </header>
    
    <div class="container py-5 main-container">
        <h1 class="page-title">
            <i class="bi bi-music-note-list me-2"></i>Listado de Canciones
        </h1>
        
        <!-- Filtros y búsqueda -->
        <div class="filters-container">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por título o artista...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select bg-dark text-white border-0" id="filterPPM">
                        <option value="">Filtrar por PPM</option>
                        <option value="low">Bajo (100-120)</option>
                        <option value="medium">Medio (121-150)</option>
                        <option value="high">Alto (151-180)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select bg-dark text-white border-0" id="sortBy">
                        <option value="title">Ordenar por Título</option>
                        <option value="artist">Ordenar por Artista</option>
                        <option value="ppm">Ordenar por PPM</option>
                        <option value="year">Ordenar por Año</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100" id="resetFilters">
                        <i class="bi bi-x-circle me-1"></i> Resetear
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Tabla de canciones -->
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
                <tbody id="songsList">
                    <!-- Ejemplo de canción (será generado dinámicamente) -->
                    <tr>
                        <td class="song-title">Bohemian Rhapsody</td>
                        <td class="song-artist">Queen</td>
                        <td><span class="badge badge-ppm">114</span></td>
                        <td class="song-year">1975</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/bohemian-rhapsody.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Don't Stop Me Now</td>
                        <td class="song-artist">Queen</td>
                        <td><span class="badge badge-ppm">156</span></td>
                        <td class="song-year">1979</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/dont-stop-me-now.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Billie Jean</td>
                        <td class="song-artist">Michael Jackson</td>
                        <td><span class="badge badge-ppm">117</span></td>
                        <td class="song-year">1982</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/billie-jean.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Beat It</td>
                        <td class="song-artist">Michael Jackson</td>
                        <td><span class="badge badge-ppm">138</span></td>
                        <td class="song-year">1982</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/beat-it.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Stayin' Alive</td>
                        <td class="song-artist">Bee Gees</td>
                        <td><span class="badge badge-ppm">104</span></td>
                        <td class="song-year">1977</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/stayin-alive.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Eye of the Tiger</td>
                        <td class="song-artist">Survivor</td>
                        <td><span class="badge badge-ppm">109</span></td>
                        <td class="song-year">1982</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/eye-of-the-tiger.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">November Rain</td>
                        <td class="song-artist">Guns N' Roses</td>
                        <td><span class="badge badge-ppm">122</span></td>
                        <td class="song-year">1991</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/november-rain.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Hotel California</td>
                        <td class="song-artist">Eagles</td>
                        <td><span class="badge badge-ppm">147</span></td>
                        <td class="song-year">1976</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/hotel-california.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Sweet Child O' Mine</td>
                        <td class="song-artist">Guns N' Roses</td>
                        <td><span class="badge badge-ppm">128</span></td>
                        <td class="song-year">1987</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/sweet-child-o-mine.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="song-title">Another One Bites the Dust</td>
                        <td class="song-artist">Queen</td>
                        <td><span class="badge badge-ppm">110</span></td>
                        <td class="song-year">1980</td>
                        <td>
                            <button class="btn-action" onclick="playSong('audio/another-one-bites-the-dust.mp3', this)">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button class="btn-action" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="btn-action" onclick="showOptions(this)">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Navegación de páginas">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cargar las canciones desde la base de datos
        document.addEventListener('DOMContentLoaded', function() {
            fetch('get_songs.php')
                .then(response => response.json())
                .then(songs => {
                    if (Array.isArray(songs)) {
                        window.allSongs = songs; // Guardar todas las canciones para la búsqueda
                        displaySongs(songs);
                        setupSearch();
                    } else {
                        console.error('Respuesta inesperada:', songs);
                        document.getElementById('songsList').innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center">Error al cargar las canciones</td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error cargando canciones:', error);
                    document.getElementById('songsList').innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center">Error al cargar las canciones</td>
                        </tr>
                    `;
                });
        });

        // Mostrar las canciones en la tabla
        function displaySongs(songs) {
            const songsListElement = document.getElementById('songsList');
            
            // Limpiar cualquier contenido de ejemplo
            songsListElement.innerHTML = '';
            
            if (songs.length === 0) {
                songsListElement.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">No hay canciones disponibles</td>
                    </tr>
                `;
                return;
            }
            
            // Crear filas para cada canción
            songs.forEach(song => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="song-title">${song.name}</td>
                    <td class="song-artist">${song.artist}</td>
                    <td><span class="badge badge-ppm">${song.ppm}</span></td>
                    <td class="song-year">${song.year}</td>
                    <td>
                        <button class="btn-action" onclick="playSong('${song.audioUrl}', this)">
                            <i class="bi bi-play-fill"></i>
                        </button>
                        <button class="btn-action stop-btn" onclick="stopSong(this)" style="display: none;">
                            <i class="bi bi-stop-fill"></i>
                        </button>
                        <button class="btn-action" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                        <button class="btn-action" onclick="showOptions(this)">
                            <i class="bi bi-three-dots"></i>
                        </button>
                    </td>
                `;
                songsListElement.appendChild(row);
            });
        }

        // Función para reproducir una canción
        function playSong(audioUrl, button) {
            // Detener cualquier audio que se esté reproduciendo
            if (window.currentAudio) {
                window.currentAudio.pause();
                window.currentAudio.currentTime = 0;
                
                // Restablecer todos los botones de reproducción/detención
                document.querySelectorAll('.stop-btn').forEach(btn => {
                    btn.style.display = 'none';
                    btn.previousElementSibling.style.display = 'inline-flex';
                });
            }
            
            // Crear y reproducir el nuevo audio
            window.currentAudio = new Audio(audioUrl);
            window.currentAudio.volume = 0.7; // Volumen predeterminado
            window.currentAudio.play();
            
            // Actualizar botones de reproducción/detención
            if (button) {
                button.style.display = 'none';
                const stopButton = button.nextElementSibling;
                stopButton.style.display = 'inline-flex';
                
                // Cuando la canción termine, restaurar los botones
                window.currentAudio.onended = function() {
                    button.style.display = 'inline-flex';
                    stopButton.style.display = 'none';
                    window.currentAudio = null;
                };
            }
        }
        
        // Función para detener la reproducción
        function stopSong(button) {
            if (window.currentAudio) {
                window.currentAudio.pause();
                window.currentAudio.currentTime = 0;
                window.currentAudio = null;
            }
            
            // Restaurar botones
            button.style.display = 'none';
            button.previousElementSibling.style.display = 'inline-flex';
        }
        
        // Función para marcar/desmarcar como favorito
        function toggleFavorite(button) {
            // Alternar entre corazón vacío y lleno
            const icon = button.querySelector('i');
            if (icon.classList.contains('bi-heart')) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                icon.style.color = '#ff4444';
                
                // Aquí se podría implementar la lógica para guardar en favoritos
                // Por ejemplo, usando localStorage o enviando a la base de datos
                showToast('Canción añadida a favoritos');
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                icon.style.color = '';
                
                showToast('Canción eliminada de favoritos');
            }
        }
        
        // Función para mostrar opciones adicionales
        function showOptions(button) {
            // Obtener información de la canción desde la fila
            const row = button.closest('tr');
            const title = row.querySelector('.song-title').textContent;
            const artist = row.querySelector('.song-artist').textContent;
            
            // Crear y mostrar un menú contextual
            const optionsMenu = document.createElement('div');
            optionsMenu.className = 'options-menu';
            optionsMenu.innerHTML = `
                <div class="card bg-dark text-white" style="position: absolute; z-index: 1000; width: 200px;">
                    <div class="card-header">
                        <strong>${title}</strong>
                        <button type="button" class="btn-close btn-close-white float-end" onclick="this.closest('.options-menu').remove()"></button>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-dark text-white" onclick="addToPlaylist('${title}', '${artist}')">
                            <i class="bi bi-music-note-list me-2"></i> Añadir a playlist
                        </li>
                        <li class="list-group-item bg-dark text-white" onclick="downloadSong('${title}', '${artist}')">
                            <i class="bi bi-download me-2"></i> Descargar
                        </li>
                        <li class="list-group-item bg-dark text-white" onclick="shareSong('${title}', '${artist}')">
                            <i class="bi bi-share me-2"></i> Compartir
                        </li>
                    </ul>
                </div>
            `;
            
            // Posicionar el menú cerca del botón
            const rect = button.getBoundingClientRect();
            optionsMenu.style.position = 'fixed';
            optionsMenu.style.top = `${rect.bottom}px`;
            optionsMenu.style.left = `${rect.left}px`;
            
            // Eliminar cualquier menú existente y añadir el nuevo
            document.querySelectorAll('.options-menu').forEach(menu => menu.remove());
            document.body.appendChild(optionsMenu);
            
            // Cerrar el menú al hacer clic fuera de él
            document.addEventListener('click', function closeMenu(e) {
                if (!optionsMenu.contains(e.target) && e.target !== button) {
                    optionsMenu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }
        
        // Funciones para las opciones del menú
        function addToPlaylist(title, artist) {
            showToast(`${title} añadida a la playlist`);
            document.querySelectorAll('.options-menu').forEach(menu => menu.remove());
        }
        
        function downloadSong(title, artist) {
            showToast(`Descargando ${title}`);
            document.querySelectorAll('.options-menu').forEach(menu => menu.remove());
        }
        
        function shareSong(title, artist) {
            showToast(`Compartiendo ${title}`);
            document.querySelectorAll('.options-menu').forEach(menu => menu.remove());
        }
        
        // Función para mostrar notificaciones toast
        function showToast(message) {
            // Crear elemento toast
            const toastEl = document.createElement('div');
            toastEl.className = 'toast-notification';
            toastEl.innerHTML = `
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-dark text-white">
                        <strong class="me-auto">Reproductor Inteligente</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body bg-dark text-white">
                        ${message}
                    </div>
                </div>
            `;
            
            // Estilo para el contenedor de toast
            toastEl.style.position = 'fixed';
            toastEl.style.bottom = '20px';
            toastEl.style.right = '20px';
            toastEl.style.zIndex = '1050';
            
            // Añadir al DOM
            document.body.appendChild(toastEl);
            
            // Eliminar después de 3 segundos
            setTimeout(() => {
                toastEl.remove();
            }, 3000);
        }
        
        // Configurar la funcionalidad de búsqueda
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    if (!window.allSongs) return;
                    
                    if (searchTerm.trim() === '') {
                        // Si la búsqueda está vacía, mostrar todas las canciones
                        displaySongs(window.allSongs);
                    } else {
                        // Filtrar canciones que coincidan con el término de búsqueda
                        const filteredSongs = window.allSongs.filter(song => 
                            song.name.toLowerCase().includes(searchTerm) || 
                            song.artist.toLowerCase().includes(searchTerm)
                        );
                        
                        displaySongs(filteredSongs);
                    }
                });
            }
        }
    </script>
</body>
</html>
