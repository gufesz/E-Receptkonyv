<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $prep_time = $_POST['prep_time'];
    $cook_time = $_POST['cook_time'];
    $instructions = $_POST['instructions'];
    $ingredients = $_POST['ingredients'];
   
    try {
        $pdo->beginTransaction();
       
        // Recept beszúrása
        $stmt = $pdo->prepare("INSERT INTO Recipes (user_id, title, category, prep_time, cook_time, instructions)
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title, $category, $prep_time, $cook_time, $instructions]);
        $recipe_id = $pdo->lastInsertId();
       
        // Hozzávalók beszúrása
        $stmt = $pdo->prepare("INSERT INTO Ingredients (recipe_id, name, amount, unit)
                              VALUES (?, ?, ?, ?)");
       
        foreach ($ingredients as $ingr) {
            $stmt->execute([$recipe_id, $ingr['name'], $ingr['amount'], $ingr['unit']]);
        }
       
        $pdo->commit();
       
        header("Location: recipe.php?id=$recipe_id");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Hiba a recept létrehozásakor: " . $e->getMessage();
    }
}

include 'header.php';
?>

<!-- Recept létrehozás form -->
<div class="container">
    <h1>Új recept létrehozása</h1>
   
    <?php if (isset($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
   
    <form method="POST" id="recipe-form">
        <div class="form-group">
            <label for="title">Recept neve*</label>
            <input type="text" id="title" name="title" required>
        </div>
       
        <div class="form-group">
            <label for="category">Kategória*</label>
            <select id="category" name="category" required>
                <option value="">Válassz...</option>
                <option value="Leves">Leves</option>
                <option value="Főétel">Főétel</option>
                <option value="Desszert">Desszert</option>
                <option value="Saláta">Saláta</option>
                <option value="Pékáru">Pékáru</option>
            </select>
        </div>
       
        <div class="form-row">
            <div class="form-group">
                <label for="prep_time">Előkészítési idő (perc)</label>
                <input type="number" id="prep_time" name="prep_time" min="0">
            </div>
           
            <div class="form-group">
                <label for="cook_time">Főzési idő (perc)</label>
                <input type="number" id="cook_time" name="cook_time" min="0">
            </div>
        </div>
       
        <div class="form-group">
            <label>Hozzávalók</label>
            <div id="ingredients-container">
                <div class="ingredient-row">
                    <input type="text" name="ingredients[0][name]" placeholder="Hozzávaló" required>
                    <input type="number" name="ingredients[0][amount]" placeholder="Mennyiség" step="0.1">
                    <input type="text" name="ingredients[0][unit]" placeholder="Egység">
                    <button type="button" class="remove-ingredient">✕</button>
                </div>
            </div>
            <button type="button" id="add-ingredient">+ Hozzávaló hozzáadása</button>
        </div>
       
        <div class="form-group">
            <label for="instructions">Elkészítés*</label>
            <textarea id="instructions" name="instructions" rows="10" required></textarea>
        </div>
       
        <button type="submit" class="btn">Recept mentése</button>
    </form>
</div>

<script src="js/create_recipe.js"></script>
<?php include 'footer.php'; ?>