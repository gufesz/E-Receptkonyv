<?php

header('Content-Type: application/json');
session_start();

echo json_encode([
    'loggedId' => isset($_SESSION['user_id'])
]);
?>