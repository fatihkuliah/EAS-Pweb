<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

// Autoloader
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $path = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

// Register helpers
require BASE_PATH . '/app/helpers.php';

$config = require BASE_PATH . '/config/database.php';

if ($config['driver'] !== 'mysql') {
    echo "Warning: Database driver in config/database.php is not set to 'mysql'. Current driver is: " . $config['driver'] . "\n";
    echo "We will proceed with mysql connection details using host: " . $config['host'] . "\n\n";
}

$action = $argv[1] ?? 'help';

try {
    // 1. Connect to MySQL (without dbname first, to ensure database exists)
    $dsnWithoutDb = sprintf('mysql:host=%s;charset=utf8mb4', $config['host']);
    $pdo = new PDO($dsnWithoutDb, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Create database if not exists
    $pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', $config['database']));
    
    // Connect to the specific database
    $pdo->exec(sprintf('USE `%s`', $config['database']));
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check config/database.php settings.\n";
    exit(1);
}

switch ($action) {
    case 'migrate':
        runMigration($pdo);
        break;

    case 'rollback':
        rollbackMigration($pdo);
        break;

    case 'seed':
        runSeeder($pdo);
        break;

    case 'reset':
        echo "Resetting database...\n";
        rollbackMigration($pdo);
        runMigration($pdo);
        runSeeder($pdo);
        break;

    default:
        echo "MieME CLI Database Tool (Laravel-style Migrations)\n";
        echo "Usage:\n";
        echo "  php db.php migrate   - Run migrations to create tables (up)\n";
        echo "  php db.php rollback  - Rollback migrations (down)\n";
        echo "  php db.php seed      - Seed mock data into tables\n";
        echo "  php db.php reset     - Rollback all migrations, run migrations, and seeders\n";
        break;
}

function runMigration(PDO $pdo): void
{
    echo "Running migrations...\n";
    $dir = BASE_PATH . '/database/migrations';
    
    if (!is_dir($dir)) {
        echo "Migration directory not found: $dir\n";
        exit(1);
    }
    
    $files = glob($dir . '/*.php');
    if (!$files) {
        echo "No migrations found.\n";
        return;
    }
    
    sort($files); // Run in alphabetical order (timestamp order)
    
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    try {
        foreach ($files as $file) {
            echo "Migrating: " . basename($file) . "\n";
            $migration = require $file;
            if (is_object($migration) && method_exists($migration, 'up')) {
                $migration->up($pdo);
            } else {
                echo "Invalid migration class in: " . basename($file) . "\n";
            }
        }
        echo "Migrations executed successfully.\n";
    } catch (\Throwable $e) {
        echo "Migration failed: " . $e->getMessage() . "\n";
        exit(1);
    } finally {
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}

function rollbackMigration(PDO $pdo): void
{
    echo "Rolling back migrations...\n";
    $dir = BASE_PATH . '/database/migrations';
    
    if (!is_dir($dir)) {
        echo "Migration directory not found: $dir\n";
        exit(1);
    }
    
    $files = glob($dir . '/*.php');
    if (!$files) {
        echo "No migrations to rollback.\n";
        return;
    }
    
    rsort($files); // Run in reverse alphabetical order to safely drop dependent tables first
    
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    try {
        foreach ($files as $file) {
            echo "Rolling back: " . basename($file) . "\n";
            $migration = require $file;
            if (is_object($migration) && method_exists($migration, 'down')) {
                $migration->down($pdo);
            } else {
                echo "Invalid migration class in: " . basename($file) . "\n";
            }
        }
        echo "Rollback completed.\n";
    } catch (\Throwable $e) {
        echo "Rollback failed: " . $e->getMessage() . "\n";
        exit(1);
    } finally {
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}

function runSeeder(PDO $pdo): void
{
    echo "Running seeders...\n";
    $seederFile = BASE_PATH . '/database/seeds/seeder.php';
    
    if (!file_exists($seederFile)) {
        echo "Seeder file not found: $seederFile\n";
        exit(1);
    }
    
    require_once $seederFile;
    
    try {
        $seeder = new \Database\Seeds\Seeder($pdo);
        $seeder->run();
    } catch (\Throwable $e) {
        echo "Seeder failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}
