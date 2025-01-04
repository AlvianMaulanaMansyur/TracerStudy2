<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=tracerstudy', 'root', '');
    echo "Koneksi berhasil!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
