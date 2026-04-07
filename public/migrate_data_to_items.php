<?php
/**
 * Migrate existing services and products into the unified items table
 * Visit: http://localhost:8080/Service Management System/public/migrate_data_to_items.php
 */

require_once dirname(__DIR__) . '/app/config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "<h2>Data Migration: Services & Products → Items</h2>";

    // --- Step 1: Show existing data ---
    echo "<h3>Existing Data:</h3>";

    // Services
    $services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h4>Services (" . count($services) . " rows):</h4>";
    if (count($services) > 0) {
        echo "<table border='1' cellpadding='5'><tr>";
        foreach (array_keys($services[0]) as $col) echo "<th>$col</th>";
        echo "</tr>";
        foreach ($services as $row) {
            echo "<tr>";
            foreach ($row as $val) echo "<td>" . htmlspecialchars($val ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No services found.</p>";
    }

    // Products
    $products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h4>Products (" . count($products) . " rows):</h4>";
    if (count($products) > 0) {
        echo "<table border='1' cellpadding='5'><tr>";
        foreach (array_keys($products[0]) as $col) echo "<th>$col</th>";
        echo "</tr>";
        foreach ($products as $row) {
            echo "<tr>";
            foreach ($row as $val) echo "<td>" . htmlspecialchars($val ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No products found.</p>";
    }

    // --- Step 2: Migrate Services → Items (type='service') ---
    echo "<hr><h3>Migrating...</h3>";
    $migrated_services = 0;
    $migrated_products = 0;
    $errors = [];

    // Get default unit for services (Hour or Service)
    $srvUnit = $pdo->query("SELECT id FROM item_units WHERE short_name = 'SRV' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $srvUnitId = $srvUnit ? $srvUnit['id'] : null;

    // Get default unit for products (Piece)
    $pcsUnit = $pdo->query("SELECT id FROM item_units WHERE short_name = 'PCS' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $pcsUnitId = $pcsUnit ? $pcsUnit['id'] : null;

    foreach ($services as $svc) {
        // Check if already migrated (by name + type)
        $check = $pdo->prepare("SELECT id FROM items WHERE name = :name AND type = 'service'");
        $check->execute([':name' => $svc['name']]);
        if ($check->fetch()) {
            echo "<p>⏭️ Service '<b>" . htmlspecialchars($svc['name']) . "</b>' already exists in items, skipping.</p>";
            continue;
        }

        try {
            // Generate service code
            $codeResult = $pdo->query("SELECT COUNT(*) as cnt FROM items WHERE type = 'service'")->fetch(PDO::FETCH_ASSOC);
            $nextNum = ($codeResult['cnt'] ?? 0) + 1;
            $itemCode = 'SRV-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            $stmt = $pdo->prepare("INSERT INTO items 
                (type, name, item_code, unit_id, sale_price, status, created_at) 
                VALUES 
                ('service', :name, :item_code, :unit_id, :sale_price, 'active', :created_at)");

            $stmt->execute([
                ':name' => $svc['name'],
                ':item_code' => $itemCode,
                ':unit_id' => $srvUnitId,
                ':sale_price' => $svc['price'] ?? 0,
                ':created_at' => $svc['created_at'] ?? date('Y-m-d H:i:s')
            ]);

            $migrated_services++;
            echo "<p>✅ Service: <b>" . htmlspecialchars($svc['name']) . "</b> → Item ($itemCode)</p>";
        } catch (PDOException $e) {
            $errors[] = "Service '" . $svc['name'] . "': " . $e->getMessage();
            echo "<p>❌ Service '" . htmlspecialchars($svc['name']) . "': " . $e->getMessage() . "</p>";
        }
    }

    // --- Step 3: Migrate Products → Items (type='product') ---
    foreach ($products as $prod) {
        // Check if already migrated
        $check = $pdo->prepare("SELECT id FROM items WHERE name = :name AND type = 'product'");
        $check->execute([':name' => $prod['name']]);
        if ($check->fetch()) {
            echo "<p>⏭️ Product '<b>" . htmlspecialchars($prod['name']) . "</b>' already exists in items, skipping.</p>";
            continue;
        }

        try {
            // Generate product code
            $codeResult = $pdo->query("SELECT COUNT(*) as cnt FROM items WHERE type = 'product'")->fetch(PDO::FETCH_ASSOC);
            $nextNum = ($codeResult['cnt'] ?? 0) + 1;
            $itemCode = 'PRD-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            $stmt = $pdo->prepare("INSERT INTO items 
                (type, name, item_code, unit_id, sale_price, purchase_price, 
                 opening_qty, current_stock, min_stock, status, created_at) 
                VALUES 
                ('product', :name, :item_code, :unit_id, :sale_price, :purchase_price,
                 :opening_qty, :current_stock, :min_stock, 'active', :created_at)");

            $stmt->execute([
                ':name' => $prod['name'],
                ':item_code' => $itemCode,
                ':unit_id' => $pcsUnitId,
                ':sale_price' => $prod['price'] ?? 0,
                ':purchase_price' => $prod['price'] ?? 0,
                ':opening_qty' => $prod['stock'] ?? 0,
                ':current_stock' => $prod['stock'] ?? 0,
                ':min_stock' => $prod['min_stock'] ?? 0,
                ':created_at' => $prod['created_at'] ?? date('Y-m-d H:i:s')
            ]);

            $migrated_products++;
            echo "<p>✅ Product: <b>" . htmlspecialchars($prod['name']) . "</b> → Item ($itemCode)</p>";
        } catch (PDOException $e) {
            $errors[] = "Product '" . $prod['name'] . "': " . $e->getMessage();
            echo "<p>❌ Product '" . htmlspecialchars($prod['name']) . "': " . $e->getMessage() . "</p>";
        }
    }

    // --- Summary ---
    echo "<hr>";
    echo "<h3>Migration Summary</h3>";
    echo "<ul>";
    echo "<li>Services migrated: <b>$migrated_services</b></li>";
    echo "<li>Products migrated: <b>$migrated_products</b></li>";
    echo "<li>Errors: <b>" . count($errors) . "</b></li>";
    echo "</ul>";

    // Show final items table
    $items = $pdo->query("SELECT id, type, name, item_code, sale_price, current_stock, status FROM items ORDER BY type, id")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h4>Items Table (" . count($items) . " total):</h4>";
    if (count($items) > 0) {
        echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Type</th><th>Name</th><th>Code</th><th>Sale Price</th><th>Stock</th><th>Status</th></tr>";
        foreach ($items as $item) {
            echo "<tr>";
            foreach ($item as $val) echo "<td>" . htmlspecialchars($val ?? '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    echo "<p><a href='" . URLROOT . "/items'>Go to Items Management →</a></p>";

} catch (PDOException $e) {
    die("<h2>Error</h2><p>" . $e->getMessage() . "</p>");
}
