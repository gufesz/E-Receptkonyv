<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Felhasználónév megadása kötelező!";
    }
    
    if (empty($email)) {
        $errors[] = "Email megadása kötelező!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Érvénytelen email formátum!";
    }
    
    if (empty($password)) {
        $errors[] = "Jelszó megadása kötelező!";
    } elseif (strlen($password) < 6) {
        $errors[] = "A jelszónak legalább 6 karakter hosszúnak kell lennie!";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "A jelszavak nem egyeznek!";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            header("Location: login.html");
            exit();
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "A felhasználónév vagy email cím már foglalt!";
            } else {
                $errors[] = "Hiba történt a regisztráció során: " . $e->getMessage();
            }
        }
    }
    
    if (!empty($errors)) {
        echo '<div style="color: red; margin-bottom: 20px;">';
        foreach ($errors as $error) {
            echo '<p>'.$error.'</p>';
        }
        echo '</div>';
    }
}
?>