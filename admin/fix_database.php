<?php
// Script pour corriger la structure de la base de données
require_once '../includes/config.php';

echo "<h1>🔧 Correction de la Base de Données</h1>";
echo "<style>
    body { font-family: Arial; padding: 20px; }
    .success { background: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f44336; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #ff9800; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #2196F3; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
</style>";

try {
    // 1. Modifier la colonne status pour ajouter 'king' et 'queen'
    echo "<h2>Étape 1 : Modification de la colonne 'status'</h2>";
    echo "<p>Ajout de 'king' et 'queen' aux valeurs possibles...</p>";
    
    $sql = "ALTER TABLE chats MODIFY COLUMN status ENUM('available', 'reserved', 'sold', 'king', 'queen') DEFAULT 'available'";
    $pdo->exec($sql);
    
    echo "<div class='success'>✅ Colonne 'status' modifiée avec succès !</div>";
    echo "<p>Valeurs acceptées maintenant : 'available', 'reserved', 'sold', 'king', 'queen'</p>";
    
    // 2. Corriger les chats existants qui devraient être des Kings ou Queens
    echo "<h2>Étape 2 : Correction des chats existants</h2>";
    
    // Demander à l'utilisateur quels chats doivent être des Kings/Queens
    echo "<div class='info'>";
    echo "<h3>🔍 Chats mâles dans votre base :</h3>";
    $stmt = $pdo->query("SELECT id, name, status FROM chats WHERE gender = 'Male' ORDER BY name");
    $males = $stmt->fetchAll();
    
    if (!empty($males)) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Status Actuel</th></tr>";
        foreach ($males as $male) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($male['id']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($male['name']) . "</strong></td>";
            echo "<td>" . ($male['status'] ?: '<em>vide</em>') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h3>🔍 Chats femelles dans votre base :</h3>";
    $stmt = $pdo->query("SELECT id, name, status FROM chats WHERE gender = 'Female' ORDER BY name");
    $females = $stmt->fetchAll();
    
    if (!empty($females)) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Status Actuel</th></tr>";
        foreach ($females as $female) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($female['id']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($female['name']) . "</strong></td>";
            echo "<td>" . ($female['status'] ?: '<em>vide</em>') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    echo "<div class='warning'>";
    echo "<h3>⚠️ Action Manuelle Requise</h3>";
    echo "<p>Pour chaque chat que vous voulez définir comme King ou Queen, exécutez ces requêtes dans phpMyAdmin :</p>";
    echo "<pre>";
    echo "-- Pour définir un King (remplacez 'ID_DU_CHAT' par l'id réel, par exemple 'thor')\n";
    echo "UPDATE chats SET status = 'king' WHERE id = 'ID_DU_CHAT';\n\n";
    echo "-- Pour définir une Queen\n";
    echo "UPDATE chats SET status = 'queen' WHERE id = 'ID_DU_CHAT';\n\n";
    echo "-- Exemples concrets basés sur vos données :\n";
    echo "UPDATE chats SET status = 'king' WHERE id = 'thor';  -- Thor devient King\n";
    echo "UPDATE chats SET status = 'queen' WHERE id = 'luna'; -- Luna devient Queen\n";
    echo "</pre>";
    echo "</div>";
    
    echo "<div class='success'>";
    echo "<h3>✅ Correction Terminée !</h3>";
    echo "<p><strong>Prochaines étapes :</strong></p>";
    echo "<ol>";
    echo "<li>Ouvrez phpMyAdmin</li>";
    echo "<li>Sélectionnez la table 'chats'</li>";
    echo "<li>Allez dans l'onglet 'SQL'</li>";
    echo "<li>Copiez-collez les requêtes UPDATE ci-dessus pour vos Kings et Queens</li>";
    echo "<li>Maintenant, tous les nouveaux Kings/Queens que vous ajouterez via le formulaire seront correctement enregistrés !</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<hr>";
    echo "<p>";
    echo "<a href='debug_db.php' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>🔍 Vérifier la Base</a> ";
    echo "<a href='cat_edit.php' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>➕ Ajouter un Chat</a>";
    echo "</p>";
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
    echo "<p>Essayez d'exécuter manuellement cette requête dans phpMyAdmin :</p>";
    echo "<pre>ALTER TABLE chats MODIFY COLUMN status ENUM('available', 'reserved', 'sold', 'king', 'queen') DEFAULT 'available';</pre>";
}
?>
