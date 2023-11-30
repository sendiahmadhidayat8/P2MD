<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script>
    // Variabel untuk menyimpan data sensor
const suhuData = [];
const kelembapanData = [];
const kecepatanAnginData = [];

// Fungsi untuk menambahkan data ke grafik
function updateChart() {
    const ctx = document.getElementById("myChart").getContext("2d");

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: suhuData.map((_, index) => `Data ${index + 1}`),
            datasets: [
                {
                    label: 'Suhu',
                    data: suhuData,
                    borderColor: 'red',
                    fill: false,
                },
                {
                    label: 'Kelembapan',
                    data: kelembapanData,
                    borderColor: 'blue',
                    fill: false,
                },
                {
                    label: 'Kecepatan Angin',
                    data: kecepatanAnginData,
                    borderColor: 'green',
                    fill: false,
                },
            ],
        },
    });
}

// Fungsi untuk memulai koneksi SSE
function initSSE() {
    const source = new EventSource("sse.php"); // Menghubungkan ke script SSE di server

    source.addEventListener("message", function (event) {
        const data = JSON.parse(event.data);
        suhuData.push(data.suhu);
        kelembapanData.push(data.kelembapan);
        kecepatanAnginData.push(data.kecepatanangin);

        // Batasi jumlah data yang ditampilkan dalam grafik (misalnya, 10 data terbaru)
        if (suhuData.length > 10) {
            suhuData.shift();
            kelembapanData.shift();
            kecepatanAnginData.shift();
        }

        updateChart(); // Memperbarui grafik setelah menerima data baru
    });

    source.addEventListener("error", function (event) {
        console.error("Error: " + event.target.readyState);
        source.close();
        setTimeout(initSSE, 1000); // Mengulang koneksi jika terjadi kesalahan
    });
}

// Memulai koneksi SSE saat halaman dimuat
window.onload = initSSE;

</script>
<title>Realtime Data Update</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="myChart" width="400" height="200"></canvas>
<h1>Data Sensor Realtime</h1>
</body>