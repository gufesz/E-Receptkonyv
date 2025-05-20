<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validáció
    if (empty($username)) $errors[] = "Felhasználónév kötelező!";
    if (empty($email)) $errors[] = "Email kötelező!";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Érvénytelen email!";
    if (strlen($password) < 6) $errors[] = "Jelszó minimum 6 karakter!";
    if ($password !== $confirm_password) $errors[] = "Jelszavak nem egyeznek!";

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            
            header("Location: login.php");
            exit();
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Felhasználónév vagy email már foglalt!";
            } else {
                $errors[] = "Hiba: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Regisztrálj a ReceptKönyvbe, hogy megoszthasd receptjeidet!">
    <title>ReceptKönyv - Regisztráció</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="hatterkep">
        <div class="hero">
            <div class="hero-container2">
                <div class="geCim">
                    Regisztrálj itt
                </div>
                <form action="register.php" method="post">
                    <div class="felhasznalonev">
                        <input type="text" name="username" placeholder="Felhasználónév..." required>
                    </div>
                    <div class="email">
                        <input type="email" name="email" placeholder="Email..." required>
                    </div>
                    <div class="password">
                        <input type="password" name="password" placeholder="Jelszó..." required>
                    </div>
                    <div class="password">
                        <input type="password" name="confirm_password" placeholder="Jelszó újra..." required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Regisztrálás</button>
                    </div>
                </form>
                <div class="valamiszoveg">
                    Ha van fiókod <a href="../php/login.php">itt</a> tudsz Bejelentkezni!
                </div>
            </div>
        </div>
    </div>
</body>
</html>