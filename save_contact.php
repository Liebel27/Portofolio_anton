
<?php
header('Content-Type: application/json');

require_once 'Database.php';

// Memastikan permintaan menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Metode tidak diizinkan.'
    ]);

    exit;
}

// Mengambil data dari formulir
$name = trim($_POST['name'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validasi input
if ($name === '' || $subject === '' || $message === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Semua kolom wajib diisi.'
    ]);

    exit;
}

if (strlen($name) > 150 || strlen($subject) > 200) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Nama atau subjek terlalu panjang.'
    ]);

    exit;
}

try {
    // Menyimpan pesan ke database
    $sql = "INSERT INTO contacts (name, subject, message)
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $name,
        $subject,
        $message
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Pesan berhasil disimpan.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan pesan.'
    ]);
}
?>
