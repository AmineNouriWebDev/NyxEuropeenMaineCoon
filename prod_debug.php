<?php
// prod_debug.php
// A UPLOADER SUR LE SERVEUR POUR DIAGNOSTIC
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic Connexion BDD</h1>";

$credentials = [
    'dbname' => 'nyxcooncattery_db',
    'user' => 'deposark_user',
    'pass' => 'Azerty1234***' // Vérifiez que c'est bien le bon mot de passe (sans étoiles si c'était masqué)
];

$hosts_to_test = ['localhost', '127.0.0.1', 'mysql'];

foreach ($hosts_to_test as $host) {
    echo "<h2>Test avec Host: <code>$host</code></h2>";
    try {
        $dsn = "mysql:host=$host;dbname={$credentials['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $credentials['user'], $credentials['pass']);
        echo "<h3 style='color: green'>✅ SUCCÈS : Connexion réussie !</h3>";
        echo "Le bon hôte est : <strong>$host</strong>";
        break; // On arrête si ça marche
    } catch (PDOException $e) {
        echo "<p style='color: red'>❌ ÉCHEC : " . $e->getMessage() . "</p>";
    }
    echo "<hr>";
}
?>
