<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $client = new Predis\Client();
    $client->set('test', 'Hello');
    echo "Predis is working! Value: " . $client->get('test');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}