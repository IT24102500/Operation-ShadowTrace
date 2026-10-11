<?php
$db_path = "/var/www/db/users.db";

try {
    $db = new PDO("sqlite:" . $db_path);
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL
    )");
    $db->exec("DELETE FROM users");
    $db->exec("INSERT INTO users (username, password) VALUES ('affiliate', 'shadow123')");
    echo "Database created successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>