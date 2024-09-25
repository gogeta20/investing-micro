<?php

$host = 'mysql_g';
$db = 'symfony_db';
$user = 'symfony';
$pass = 'symfony_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $password = 'default_password'; // Aquí defines la contraseña en texto plano
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Genera el hash de la contraseña

    $sql = "
            INSERT INTO user (uuid, name ,email, roles, password)
            VALUES (UUID(), 'develop','develop@example.com', '[\"ROLE_DEVELOPER\"]', :password)
            ON DUPLICATE KEY UPDATE email = 'develop@example.com'
            ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    echo "Usuario 'develop' creado correctamente.\n";
} catch (\PDOException $e) {
    echo "Error al ejecutar el script SQL: " . $e->getMessage() . "\n";
}
