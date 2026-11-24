
<?php
header('Content-Type: application/json');

require_once 'Database.php';

try {
    $sql = "SELECT
                AVG(mechanical) AS mechanical,
                AVG(electrical) AS electrical,
                AVG(automation) AS automation,
                AVG(programming) AS programming,
                COUNT(*) AS total
            FROM ratings";

    $stmt = $pdo->query($sql);
    $result = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'mechanical' => round((float) $result['mechanical'], 2),
        'electrical' => round((float) $result['electrical'], 2),
        'automation' => round((float) $result['automation'], 2),
        'programming' => round((float) $result['programming'], 2),
        'total' => (int) $result['total']
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengambil data penilaian.'
    ]);
}
?>
