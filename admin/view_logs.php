<?php
// Afficher les dernières lignes du log d'erreurs PHP
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logs PHP</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        h1 { color: #4ec9b0; }
        pre { background: #252526; padding: 15px; border-radius: 5px; overflow-x: auto; line-height: 1.6; }
        .highlight { background: #ffe66d; color: #000; padding: 2px 4px; }
        button { background: #007acc; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; margin: 10px 5px; }
        button:hover { background: #005a9e; }
    </style>
</head>
<body>
    <h1>📋 Logs d'Erreurs PHP</h1>
    <p>
        <button onclick="location.reload()">🔄 Recharger</button>
        <button onclick="window.location='debug_db.php'">🔍 Debug Base de Données</button>
        <button onclick="window.location='cat_edit.php'">➕ Formulaire Chat</button>
    </p>

<?php
// Trouver le fichier de log PHP
$log_file = ini_get('error_log');

if (!$log_file || $log_file == 'syslog') {
    // Essayer les emplacements courants pour XAMPP Windows
    $possible_logs = [
        'C:\\xampp\\php\\logs\\php_error_log',
        'C:\\xampp\\apache\\logs\\error.log',
        'C:\\xampp\\logs\\php_error_log',
        dirname(dirname(__DIR__)) . '\\logs\\php_error_log'
    ];
    
    foreach ($possible_logs as $possible) {
        if (file_exists($possible)) {
            $log_file = $possible;
            break;
        }
    }
}

echo "<h3>Fichier de log : " . htmlspecialchars($log_file ?? 'Non trouvé') . "</h3>";

if ($log_file && file_exists($log_file)) {
    // Lire les 100 dernières lignes
    $lines = file($log_file);
    $last_lines = array_slice($lines, -100);
    
    echo "<pre>";
    foreach ($last_lines as $line) {
        // Highlight les lignes contenant DEBUG
        if (stripos($line, 'DEBUG') !== false) {
            echo "<span class='highlight'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<div style='background: #f44336; color: white; padding: 15px; border-radius: 5px;'>";
    echo "❌ Fichier de log non trouvé. Vérifiez la configuration PHP.";
    echo "</div>";
    
    echo "<h3>Configuration PHP actuelle :</h3>";
    echo "<pre>";
    echo "error_log = " . ini_get('error_log') . "\n";
    echo "log_errors = " . ini_get('log_errors') . "\n";
    echo "display_errors = " . ini_get('display_errors') . "\n";
    echo "</pre>";
    
    echo "<h3>Pour activer les logs dans XAMPP :</h3>";
    echo "<ol>";
    echo "<li>Ouvrez <code>C:\\xampp\\php\\php.ini</code></li>";
    echo "<li>Cherchez <code>error_log</code></li>";
    echo "<li>Définissez : <code>error_log = \"C:\\xampp\\php\\logs\\php_error_log\"</code></li>";
    echo "<li>Redémarrez Apache</li>";
    echo "</ol>";
}
?>

<h3>📝 Instructions</h3>
<ol>
    <li>Allez sur le <a href="cat_edit.php">formulaire d'ajout de chat</a></li>
    <li>Ajoutez un King ou une Queen</li>
    <li>Revenez ici et rechargez la page</li>
    <li>Cherchez les lignes qui commencent par <code>=== DEBUG CAT FORM ===</code></li>
    <li>Vérifiez que <code>status</code> = <code>king</code> ou <code>queen</code></li>
</ol>

</body>
</html>
