<?php
include 'config/koneksi.php'; 

header('Content-Type: application/json');

// Menghitung jumlah booking per tanggal yang aktif
$query = "SELECT tanggal, COUNT(*) as total_booking 
          FROM booking 
          WHERE status != 'batal' 
          GROUP BY tanggal";

$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Jika dalam 1 tanggal sudah terisi 2 jadwal atau lebih
    if ($row['total_booking'] >= 2) {
        $events[] = [
            'title' => 'Slot Penuh',
            'start' => $row['tanggal'], // Format: YYYY-MM-DD
            'color' => '#ef4444', // Merah (Tailwind red-500)
            'display' => 'background', // Blokir background tanggal tersebut
            'extendedProps' => [
                'isFull' => true
            ]
        ];
    }
}

echo json_encode($events);
?>