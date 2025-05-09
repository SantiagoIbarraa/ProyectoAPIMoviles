document.addEventListener('DOMContentLoaded', function() {
    fetch('get_songs.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#songsTable tbody');
            tbody.innerHTML = '';
            if (Array.isArray(data)) {
                data.forEach((song, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${idx + 1}</td>
                        <td>${song.artist || '-'}</td>
                        <td>${song.name || '-'}</td>
                        <td>${song.year || '-'}</td>
                        <td>${song.ppm || '-'}</td>
                        <td>${song.audioUrl ? `<audio controls src='${song.audioUrl}' style='width:140px;'></audio>` : '-'}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan='6'>No se pudieron cargar las canciones.</td></tr>`;
            }
        })
        .catch(err => {
            const tbody = document.querySelector('#songsTable tbody');
            tbody.innerHTML = `<tr><td colspan='6'>Error cargando canciones.</td></tr>`;
        });
});
