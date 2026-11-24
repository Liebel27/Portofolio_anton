
<?php
header('Content-Type: application/json');

require_once 'Database.php';

// Pastikan hanya menerima metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Metode tidak diizinkan.'
    ]);
    exit;
}

// Mengambil data penilaian
$mechanical  = filter_input(INPUT_POST, 'mechanical', FILTER_VALIDATE_INT);
$electrical  = filter_input(INPUT_POST, 'electrical', FILTER_VALIDATE_INT);
$automation  = filter_input(INPUT_POST, 'automation', FILTER_VALIDATE_INT);
$programming = filter_input(INPUT_POST, 'programming', FILTER_VALIDATE_INT);

// Validasi nilai 1 sampai 10
$ratings = [
    $mechanical,
    $electrical,
    $automation,
    $programming
];

foreach ($ratings as $rating) {
    if ($rating === false || $rating === null || $rating < 1 || $rating > 10) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nilai harus berada antara 1 sampai 10.'
        ]);
        exit;
    }
}

try {
    // Menyimpan penilaian ke database
    $sql = "INSERT INTO ratings
            (mechanical, electrical, automation, programming)
            VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $mechanical,
        $electrical,
        $automation,
        $programming
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Penilaian berhasil disimpan ke database.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan penilaian.'
    ]);
}
?>

