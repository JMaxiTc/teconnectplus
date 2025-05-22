<?php
// Archivo temporal para crear la tabla de notificaciones

// Información de conexión a la base de datos
$host = 'localhost';
$dbname = 'teconnectplus';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si la tabla ya existe
    $tableExists = false;
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'notificacions'");
    $stmt->execute();
    $tableExists = ($stmt->rowCount() > 0);
    
    if ($tableExists) {
        echo "La tabla 'notificacions' ya existe.\n";
    } else {
        // Crear la tabla
        $sql = "
        CREATE TABLE IF NOT EXISTS `notificacions` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `id_usuario` int(11) NOT NULL COMMENT 'ID del usuario que recibe la notificación',
          `titulo` varchar(255) NOT NULL,
          `mensaje` text NOT NULL,
          `tipo` varchar(50) NOT NULL DEFAULT 'info' COMMENT 'Tipo de notificación: info, success, warning, error',
          `icono` varchar(100) DEFAULT NULL,
          `url` varchar(255) DEFAULT NULL COMMENT 'URL opcional para redirigir al hacer clic',
          `leida` tinyint(1) NOT NULL DEFAULT 0,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `id_usuario` (`id_usuario`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        // Ejecutar la consulta
        $pdo->exec($sql);
        echo "Tabla de notificaciones creada correctamente.\n";
    }
    
    // Mostrar todas las tablas
    echo "Tablas en la base de datos:\n";
    foreach($pdo->query("SHOW TABLES") as $row) {
        echo "- " . $row[0] . "\n";
    }
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
