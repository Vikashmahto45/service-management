<?php
require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/core/Database.php';

$db = new Database;

$iconMap = [
    'AC & Appliance Repair' => 'fa-fan',
    'Cleaning & Pest Control' => 'fa-broom',
    'Electricians & Plumbers' => 'fa-bolt',
    'Home Painting' => 'fa-paint-roller',
    'Water Purification' => 'fa-faucet',
    'Solar Energy' => 'fa-solar-panel',
    'Carpenters' => 'fa-hammer',
    'Salon for Men' => 'fa-cut',
    'Salon for Women' => 'fa-spa'
];

foreach ($iconMap as $name => $icon) {
    $db->query('UPDATE categories SET icon = :icon WHERE name = :name');
    $db->bind(':icon', $icon);
    $db->bind(':name', $name);
    if($db->execute()){
        echo "Updated $name with $icon<br>";
    }
}
echo "Done.";
