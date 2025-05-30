<?php
header('Content-Type: application/json');
require 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

$category = $_GET['category'] ?? null;
$maxTime = $_GET['max_time'] ?? null;

try {
    $sql = "SELECT * FROM recipes WHERE user_id = ?";
    $params = [$_SESSION['user_id']];
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    if ($maxTime) {
        $sql .= " AND time <= ?";
        $params[] = $maxTime;
    }
    
    $sql .= " ORDER BY name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($recipes);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>