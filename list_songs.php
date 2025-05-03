<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Canciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #222; color: #fff; }
        th, td { vertical-align: middle!important; border: 1px solid #444 !important; }
        .table thead { background: #333; color: #ff4444; }
        .table-striped > tbody > tr:nth-of-type(odd) { background-color: #292929; }
        .table-striped > tbody > tr:nth-of-type(even) { background-color: #232323; }
        .table-bordered { border: 2px solid #555 !important; }
        .table thead th { border-bottom: 2px solid #ff4444 !important; font-size: 1.1rem; }
        .table tbody td { font-size: 1.05rem; color: #f8f8f8; }
        .table tbody td.song-title { color: #ffe082; font-weight: bold; }
        .table tbody td.artist { color: #80cbc4; }
        .table tbody td.id { color: #b39ddb; }
        .table tbody td.ppm { color: #ffab91; font-weight: bold; }
        .table-responsive { box-shadow: 0 2px 16px rgba(0,0,0,0.5); border-radius: 10px; overflow: hidden; }
        .audio-cell audio { background: #111; border-radius: 5px; }
        .table-hover tbody tr:hover { background-color: #444 !important; }
        .container { max-width: 950px; }
    </style>
</head>
<body>
    <header class="bg-dark text-white py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Lista de Canciones</h2>
            <nav>
                <a href="ppm_music.html" class="btn btn-outline-light ms-2">Volver al reproductor</a>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3 class="mb-4">Todas las canciones</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle" id="songsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Artista</th>
                        <th>Nombre</th>
                        <th>Año</th>
                        <th>PPM</th>
                        <th>Audio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT artist, name, year, PPM as ppm, audioUrl FROM songs");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo '<div class="alert alert-danger">Error de base de datos: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $songs = [];
}
?>
                    <?php foreach ($songs as $idx => $song): ?>
                    <tr>
                        <td class="id"><?= $idx + 1 ?></td>
                        <td class="artist"><?= htmlspecialchars($song['artist'] ?? '-') ?></td>
                        <td class="song-title"><?= htmlspecialchars($song['name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($song['year'] ?? '-') ?></td>
                        <td class="ppm"><?= htmlspecialchars($song['ppm'] ?? '-') ?></td>
                        <td class="audio-cell"><?php if (!empty($song['audioUrl'])): ?>
                            <audio controls src="<?= htmlspecialchars($song['audioUrl']) ?>" style="width:140px;"></audio>
                        <?php else: ?>-
                        <?php endif; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
