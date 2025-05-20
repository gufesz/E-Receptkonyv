<?php

session_start();
sessiom_unset();
session_destroy();
header('Content-Type: application/json');

echo json_encode(['success' => true]);
?>