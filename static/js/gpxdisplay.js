document.addEventListener("DOMContentLoaded", function () {
    // Fix missing Leaflet marker icons
    const LIcon = L.Icon.Default;
    LIcon.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
    });

    // Load and display GPX route
    fetch("../../functions/getgpx.php?id=<?= intval($_GET['route_id']) ?>")
        .then(res => {
            if (!res.ok) throw new Error("GPX file not found");
            return res.text();
        })
        .then(gpxText => {
            const map = L.map('map').setView([0, 0], 2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            new L.GPX(gpxText, { async: true })
                .on('loaded', function(e) {
                    map.fitBounds(e.target.getBounds());
                })
                .addTo(map);
        })
        .catch(err => {
            document.getElementById('map').innerHTML = "<p style='color:red;'>Error loading GPX file.</p>";
            console.error(err);
        });
});