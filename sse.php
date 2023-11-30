<?php
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

// Fungsi untuk melakukan permintaan HTTP GET
function performHttpGetRequest($url, $headers) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        return "cURL Error: " . curl_error($curl);
    } else {
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            return $response;
        } else {
            return "Error: HTTP Response Code $httpCode";
        }
    }

    curl_close($curl);
}

// Header yang diperlukan (sesuaikan dengan kebutuhan Anda)
$headers = array(
    "X-M2M-Origin: 039a2ba9d88afee6:8351257502e4cae8",
    "Content-Type: application/json;ty=4",
    "Accept: application/json"
);

// URL target untuk GET (sesuaikan dengan URL API Anda)
$apiUrl = "https://platform.antares.id:8443/~/antares-cse/antares-id/cobaasu/sensor/la";

// JavaScript untuk mengatur opsi caching di sisi klien
echo "<script>";
echo "var source = new EventSource('sse.php');";
echo "source.onmessage = function(event) {";
echo "  var data = JSON.parse(event.data);";
echo "  appendData(data);";
echo "};";
echo "source.onerror = function(event) {";
echo "  console.error('Error: ' + event.target.readyState);";
echo "  source.close();";
echo "  setTimeout(function() {";
echo "    source = new EventSource('sse.php');";
echo "  }, 1000);"; // Mengulang koneksi jika terjadi kesalahan
echo "};";
echo "</script>";

// Kirim respons dari API ke klien (halaman web) dalam format SSE
while (true) {
    $apiResponse = performHttpGetRequest($apiUrl, $headers);
    $data = json_decode($apiResponse, true);

    // Ambil bagian "con" dari respons API yang berisi data sensor
    $sensorData = json_decode($data["m2m:cin"]["con"], true);

    // Kirim data sensor ke klien dalam format SSE
    echo "data: " . json_encode($sensorData) . "\n\n";
    flush();

    // Menunggu beberapa saat sebelum mengirim pembaruan selanjutnya
    usleep(100); // 100ms dalam mikrodetik (0.1 detik)
}
?>
