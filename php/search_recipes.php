<?php
header('Content-Type: application/json');
require 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

$query = $_GET['query'] ?? '';

try {
    $stmt = $conn->prepare("SELECT * FROM recipes 
                          WHERE user_id = ? AND name LIKE ? 
                          ORDER BY name ASC");
    $stmt->execute([$_SESSION['user_id'], "%$query%"]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($recipes);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>