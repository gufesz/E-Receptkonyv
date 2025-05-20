<?php
header('Content-Type: application/json');
require '../db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

try {
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($recipes);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>