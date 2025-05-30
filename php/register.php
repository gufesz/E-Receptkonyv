<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
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
            
            // Auto-login after registration
            session_start();
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $username;
            
            header("Location: ../html/index.html");
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
    <title>Regisztráció - E-Receptkönyv</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="hero">
        <div class="hero-container2">
            <div class="geCim">
                Regisztrálj
            </div>
            <?php if (!empty($errors)): ?>
                <div class="error" style="color:red; margin-bottom:15px;">
                    <?php foreach ($errors as $error): ?>
                        <div><?= $error ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <div>
                    <input type="text" name="username" placeholder="Felhasználónév..." required>
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email..." required>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Jelszó..." required>
                </div>
                <div>
                    <input type="password" name="confirm_password" placeholder="Jelszó újra..." required>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Regisztrálás</button>
                </div>
            </form>
            <div class="valamiszoveg">
                Már van fiókod? <a href="login.php">Jelentkezz be!</a>
            </div>
        </div>
    </div>
</body>
</html>