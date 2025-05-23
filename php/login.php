<?php
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: ../html/index.html");
            exit();
        } else {
            $error = "Hibás adatok!";
        }
    } catch(PDOException $e) {
        $error = "Hiba: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Jelentkezz be a ReceptKönyvbe, hogy megoszthasd receptjeidet!">
    <title>ReceptKönyv - Bejelentkezés</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="hatterkep">
        <div class="hero">
            <div class="hero-container2">
                <div class="geCim">
                    Jelentkezz be
                </div>
                <form action="login.php" method="post">
                    <div>
                        <input class="felhasznalonev" type="text" name="username" placeholder="Felhasználónév..." required>
                    </div>
                    <div>
                        <input class="password" type="password" name="password" placeholder="Jelszó..." required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Bejelentkezés</button>
                    </div>
                </form>
                <div class="valamiszoveg">
                    Ha nem lenne fiókod <a href="../php/register.php">itt</a> tudsz regisztrálni!
                </div>
            </div>
        </div>
    </div>
</body>
</html