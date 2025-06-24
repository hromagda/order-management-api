<?php

/**
 * Seeder pro naplnění databáze ukázkovými daty objednávek a položek.
 *
 * Skript smaže existující data v tabulkách orders a items,
 * a vloží nové testovací objednávky a položky k nim.
 *
 * Používá PDO připojení z bootstrapu.
 */

require_once __DIR__ . '/../../../vendor/autoload.php';
$bootstrap = require __DIR__ . '/../../../bootstrap.php';

$pdo = $bootstrap['pdo'];

try {
    // Začne transakci
    $pdo->beginTransaction();

    // Nejprve smaže stará data
    $pdo->exec('DELETE FROM items');
    $pdo->exec('DELETE FROM orders');

    // Vloží objednávky
    $orders = [
        ['customer_name' => 'Jan Novák', 'total_amount' => 1200.50, 'status' => 'new'],
        ['customer_name' => 'Petra Svobodová', 'total_amount' => 2350.00, 'status' => 'processing'],
        ['customer_name' => 'Karel Dvořák', 'total_amount' => 875.75, 'status' => 'completed'],
    ];

    $orderIds = [];

    foreach ($orders as $order) {
        $stmt = $pdo->prepare(
            'INSERT INTO orders (customer_name, total_amount, status, created_at, updated_at)
             VALUES (:customer_name, :total_amount, :status, NOW(), NOW())'
        );
        $stmt->execute([
            'customer_name' => $order['customer_name'],
            'total_amount' => $order['total_amount'],
            'status' => $order['status'],
        ]);

        $orderIds[] = $pdo->lastInsertId();
    }

    // Vloží položky objednávek
    $items = [
        // Položky pro první objednávku
        ['order_id' => $orderIds[0], 'product_name' => 'Notebook Lenovo', 'quantity' => 1, 'price' => 1200.50],
        // Položky pro druhou objednávku
        ['order_id' => $orderIds[1], 'product_name' => 'Monitor LG', 'quantity' => 1, 'price' => 1500.00],
        ['order_id' => $orderIds[1], 'product_name' => 'Klávesnice Logitech', 'quantity' => 2, 'price' => 425.00],
        // Položky pro třetí objednávku
        ['order_id' => $orderIds[2], 'product_name' => 'Myš Microsoft', 'quantity' => 1, 'price' => 875.75],
    ];

    foreach ($items as $item) {
        $stmt = $pdo->prepare(
            'INSERT INTO items (order_id, product_name, quantity, price)
             VALUES (:order_id, :product_name, :quantity, :price)'
        );
        $stmt->execute([
            'order_id' => $item['order_id'],
            'product_name' => $item['product_name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    // Potvrdí transakci
    $pdo->commit();

    echo "✅ Seeder úspěšně dokončen!\n";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "❌ Chyba při seedování: " . $e->getMessage() . "\n";
}