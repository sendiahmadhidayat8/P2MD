<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Sensor Realtime</title>
</head>
<body>
    <h1>Data Sensor Realtime</h1>

    <div id="sensor-data">
        <!-- Data sensor akan ditampilkan di sini -->
        <span id="suhu"></span>
        <span id="kelembaban"></span>
        <span id="kecepatanangin"></span>
    </div>

    <script>// Fungsi untuk menambahkan data ke elemen div
function appendData(data) {
    console.log(data); // Tampilkan data di konsol browser
    const sensorDataDiv = document.getElementById("sensor-data");
    sensorDataDiv.innerHTML = `
        ${data.suhu}
        ${data.kelembaban}
        ${data.kecepatanangin}
    `;
}

// Fungsi untuk memulai koneksi SSE
function initSSE() {
    const source = new EventSource("sse.php"); // Menghubungkan ke script SSE di server

    source.addEventListener("message", function(event) {
        const data = JSON.parse(event.data);
        appendData(data); // Menambahkan data baru ke halaman
    });

    source.addEventListener("error", function(event) {
        console.error("Error: " + event.target.readyState);
        source.close();
        setTimeout(initSSE, 1000); // Mengulang koneksi jika terjadi kesalahan
    });
}

// Memulai koneksi SSE saat halaman dimuat
window.onload = initSSE;

    </script>
</body>
</html>