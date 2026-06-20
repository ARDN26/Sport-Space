<?php
include '../config/db.php';

$id_waktu = $_POST['id_waktu'] ?? null;

if (!$id_waktu) {
    echo json_encode(['error' => 'ID waktu tidak valid']);
    exit;
}

$query = "SELECT MIN(Jam_Mulai) AS jam_mulai, MAX(Jam_Selesai) AS jam_selesai FROM jam WHERE ID_Waktu = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_waktu);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode([
    'jam_mulai' => $data['jam_mulai'],
    'jam_selesai' => $data['jam_selesai']
]);
