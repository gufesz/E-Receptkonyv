<?php
$host = 'localhost';
$dbname = 'e_receptkonyv';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
    
    // Create tables if they don't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS recipes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        ingredients TEXT NOT NULL,
        instructions TEXT NOT NULL,
        time INT NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    
    // Insert sample recipes if table is empty
    $count = $conn->query("SELECT COUNT(*) FROM recipes")->fetchColumn();
    if ($count == 0) {
        $sampleRecipes = [
            [
                'user_id' => 1,
                'name' => 'Bolognai spagetti',
                'category' => 'Olasz',
                'ingredients' => "400 g spagetti\n300 g darált marhahús\n1 db hagyma\n2 gerezd fokhagyma\n400 g paradicsomszósz\n2 ek olívaolaj\nsó, bors, oregánó",
                'instructions' => "1. Forralj fel sós vizet a tésztának\n2. Pirítsd meg a hagymát és fokhagymát\n3. Add hozzá a darált húst és süsd meg\n4. Öntsd hozzá a paradicsomszószt\n5. Főzd 15-20 percig\n6. Keverd össze a kifőzött tésztával",
                'time' => 30,
                'image' => 'https://images.pexels.com/photos/41320/beef-cheese-cuisine-delicious-41320.jpeg'
            ],
            [
                'user_id' => 1,
                'name' => 'Pulykamell sonkás-tejfölös szószban',
                'category' => 'Magyar',
                'ingredients' => "4 db pulykamell\n10 dkg sonka\n2 dl tejföl\n1 ek liszt\n1 db hagyma\n1 ek vaj\nsó, bors, majoránna",
                'instructions' => "1. A pulykamellet szeleteld fel\n2. A hagymát vajon pirítsd meg\n3. Add hozzá a húst és süsd meg\n4. Keverd össze a tejfölt a liszttel\n5. Öntsd a húsra és főzd 15 percig",
                'time' => 40,
                'image' => 'https://images.pexels.com/photos/1624487/pexels-photo-1624487.jpeg'
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => '',
                'category' => '',
                'ingredients' => "",
                'instructions' => "",
                'time' => ,
                'image' => ''
            ],
            [
                'user_id' => 1,
                'name' => 'Csokoládés muffin',
                'category' => 'Desszert',
                'ingredients' => "200 g liszt\n150 g cukor\n100 g vaj\n2 db tojás\n3 ek kakaópor\n1 tk sütőpor\n1 dl tej",
                'instructions' => "1. Keverd össze a száraz hozzávalókat\n2. A vajat olvaszd meg\n3. Keverd össze a vajat a tojásokkal és tejjel\n4. Öntsd a száraz keverékre\n5. 180°C-on süsd 20 percig",
                'time' => 35,
                'image' => 'https://images.pexels.com/photos/1070850/pexels-photo-1070850.jpeg'
            ]
        ];
        
        $stmt = $conn->prepare("INSERT INTO recipes (user_id, name, category, ingredients, instructions, time, image) 
                               VALUES (:user_id, :name, :category, :ingredients, :instructions, :time, :image)");
        
        foreach ($sampleRecipes as $recipe) {
            $stmt->execute($recipe);
        }
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>