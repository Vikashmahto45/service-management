<?php
// Test Stock Deduction Script - Standalone
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'service_management_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1>Stock Deduction Test</h1>";

    // 1. Get First Product
    $stmt = $pdo->query("SELECT * FROM products LIMIT 1");
    $product = $stmt->fetch(PDO::FETCH_OBJ);

    if($product){
        echo "Product: {$product->name} (ID: {$product->id})<br>";
        echo "Initial Stock: <strong>{$product->stock}</strong><br>";
        
        // 2. Decrement by 1
        echo "Decrementing by 1...<br>";
        $sql = "UPDATE products SET stock = stock - 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $product->id]);
        
        // 3. Check New Stock
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $product->id]);
        $new_product = $stmt->fetch(PDO::FETCH_OBJ);
        
        echo "New Stock: <strong>{$new_product->stock}</strong><br>";
        
        if($new_product->stock == $product->stock - 1){
            echo "<h2 style='color:green'>SUCCESS: Stock deducted correctly!</h2>";
            
            // Revert changes
            echo "Reverting...<br>";
            $sql = "UPDATE products SET stock = stock + 1 WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $product->id]);
            echo "Stock reverted.";
        } else {
            echo "<h2 style='color:red'>FAILED: Stock did not change correctly.</h2>";
        }

    } else {
        echo "No products found to test.";
    }

} catch(PDOException $e){
    echo "DB Error: " . $e->getMessage();
}
