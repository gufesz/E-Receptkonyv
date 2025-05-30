<?php
header('Content-Type: application/json');
require 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

$recipeId = $_GET['id'] ?? 0;

try {
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE id = ? AND user_id = ?");
    $stmt->execute([$recipeId, $_SESSION['user_id']]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($recipe) {
        echo json_encode($recipe);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Recipe not found']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>