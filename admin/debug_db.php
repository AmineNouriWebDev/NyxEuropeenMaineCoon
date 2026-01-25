<?php
// Debug complet de la base de données
require_once '../includes/config.php';

echo "<h1>🔍 Debug Complet - Base de Données Chats</h1>";
echo "<style>
    body { font-family: Arial; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #4CAF50; color: white; }
    .error { background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0; }
    .success { background: #e8f5e9; padding: 10px; border-left: 4px solid #4CAF50; margin: 10px 0; }
    .warning { background: #fff3e0; padding: 10px; border-left: 4px solid #ff9800; margin: 10px 0; }
    .highlight { background: #ffeb3b; font-weight: bold; }
</style>";

// 1. TOUS LES CHATS
echo "<h2>📋 Tous les Chats dans la Base</h2>";
try {
    $stmt = $pdo->query("SELECT id, name, gender, status, created_at FROM chats ORDER BY created_at DESC");
    $all_cats = $stmt->fetchAll();
    
    if (empty($all_cats)) {
        echo "<div class='error'>❌ AUCUN CHAT dans la base de données !</div>";
    } else {
        echo "<div class='success'>✅ " . count($all_cats) . " chat(s) trouvé(s)</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Nom</th><th>Genre</th><th>Status</th><th>Date Création</th></tr>";
        foreach ($all_cats as $cat) {
            $highlight = ($cat['status'] == 'king' || $cat['status'] == 'queen') ? 'class="highlight"' : '';
            echo "<tr $highlight>";
            echo "<td>" . htmlspecialchars($cat['id']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($cat['name']) . "</strong></td>";
            echo "<td>" . $cat['gender'] . "</td>";
            echo "<td><strong>" . $cat['status'] . "</strong></td>";
            echo "<td>" . $cat['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Erreur SQL : " . $e->getMessage() . "</div>";
}

// 2. KINGS SPÉCIFIQUEMENT
echo "<hr><h2>👑 KINGS (Requête exacte de cat_edit.php)</h2>";
echo "<p><code>SELECT id, name FROM chats WHERE gender = 'Male' AND status = 'king' ORDER BY name</code></p>";
try {
    $stmt = $pdo->query("SELECT id, name, gender, status FROM chats WHERE gender = 'Male' AND status = 'king' ORDER BY name");
    $kings = $stmt->fetchAll();
    
    if (empty($kings)) {
        echo "<div class='error'>❌ AUCUN KING trouvé avec cette requête !</div>";
        echo "<div class='warning'>";
        echo "<strong>Critères de recherche :</strong><br>";
        echo "- gender = 'Male'<br>";
        echo "- status = 'king'<br><br>";
        echo "<strong>Vérifiez si vos Kings ont bien ces valeurs exactes !</strong>";
        echo "</div>";
    } else {
        echo "<div class='success'>✅ " . count($kings) . " King(s) trouvé(s)</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Nom</th><th>Genre</th><th>Status</th></tr>";
        foreach ($kings as $king) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($king['id']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($king['name']) . "</strong></td>";
            echo "<td>" . $king['gender'] . "</td>";
            echo "<td>" . $king['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Erreur SQL : " . $e->getMessage() . "</div>";
}

// 3. QUEENS SPÉCIFIQUEMENT
echo "<hr><h2>👸 QUEENS (Requête exacte de cat_edit.php)</h2>";
echo "<p><code>SELECT id, name FROM chats WHERE gender = 'Female' AND status = 'queen' ORDER BY name</code></p>";
try {
    $stmt = $pdo->query("SELECT id, name, gender, status FROM chats WHERE gender = 'Female' AND status = 'queen' ORDER BY name");
    $queens = $stmt->fetchAll();
    
    if (empty($queens)) {
        echo "<div class='error'>❌ AUCUNE QUEEN trouvée avec cette requête !</div>";
        echo "<div class='warning'>";
        echo "<strong>Critères de recherche :</strong><br>";
        echo "- gender = 'Female'<br>";
        echo "- status = 'queen'<br><br>";
        echo "<strong>Vérifiez si vos Queens ont bien ces valeurs exactes !</strong>";
        echo "</div>";
    } else {
        echo "<div class='success'>✅ " . count($queens) . " Queen(s) trouvée(s)</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Nom</th><th>Genre</th><th>Status</th></tr>";
        foreach ($queens as $queen) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($queen['id']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($queen['name']) . "</strong></td>";
            echo "<td>" . $queen['gender'] . "</td>";
            echo "<td>" . $queen['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Erreur SQL : " . $e->getMessage() . "</div>";
}

// 4. CHATS AVEC STATUS 'king' ou 'queen' mais mauvais gender
echo "<hr><h2>⚠️ Diagnostic des Problèmes Potentiels</h2>";

echo "<h3>Chats avec status='king' mais gender != 'Male'</h3>";
$stmt = $pdo->query("SELECT id, name, gender, status FROM chats WHERE status = 'king' AND gender != 'Male'");
$problematic_kings = $stmt->fetchAll();
if (empty($problematic_kings)) {
    echo "<div class='success'>✅ Aucun problème détecté</div>";
} else {
    echo "<div class='error'>❌ " . count($problematic_kings) . " King(s) avec un genre incorrect :</div>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nom</th><th>Genre (devrait être Male)</th><th>Status</th></tr>";
    foreach ($problematic_kings as $cat) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($cat['id']) . "</td>";
        echo "<td>" . htmlspecialchars($cat['name']) . "</td>";
        echo "<td class='highlight'>" . $cat['gender'] . "</td>";
        echo "<td>" . $cat['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h3>Chats avec gender='Male' mais status != 'king'</h3>";
$stmt = $pdo->query("SELECT id, name, gender, status FROM chats WHERE gender = 'Male' AND status != 'king'");
$male_non_kings = $stmt->fetchAll();
if (empty($male_non_kings)) {
    echo "<div class='success'>✅ Aucun problème détecté</div>";
} else {
    echo "<div class='warning'>⚠️ " . count($male_non_kings) . " mâle(s) qui ne sont pas Kings :</div>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nom</th><th>Genre</th><th>Status (devrait être king ?)</th></tr>";
    foreach ($male_non_kings as $cat) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($cat['id']) . "</td>";
        echo "<td>" . htmlspecialchars($cat['name']) . "</td>";
        echo "<td>" . $cat['gender'] . "</td>";
        echo "<td class='highlight'>" . $cat['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><em>Note : Ce sont peut-être des chatons mâles, c'est normal.</em></p>";
}

// 5. STRUCTURE DE LA TABLE
echo "<hr><h2>🗄️ Structure de la Table 'chats'</h2>";
try {
    $stmt = $pdo->query("DESCRIBE chats");
    $columns = $stmt->fetchAll();
    
    echo "<table>";
    echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>" . $col['Field'] . "</strong></td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<div class='error'>Erreur : " . $e->getMessage() . "</div>";
}

// 6. ACTIONS RECOMMANDÉES
echo "<hr><h2>🔧 Actions Recommandées</h2>";
echo "<div class='warning'>";
echo "<h3>Si votre King n'apparaît pas :</h3>";
echo "<ol>";
echo "<li><strong>Vérifiez dans le tableau 'Tous les Chats'</strong> ci-dessus que votre King existe</li>";
echo "<li><strong>Vérifiez que le Status est exactement 'king'</strong> (pas 'King' ou 'KING')</li>";
echo "<li><strong>Vérifiez que le Gender est exactement 'Male'</strong> (pas 'male' ou 'MALE')</li>";
echo "<li><strong>Si le Status ou Gender est incorrect</strong>, corrigez-le dans phpMyAdmin</li>";
echo "</ol>";

echo "<h3>Requête SQL pour corriger manuellement (dans phpMyAdmin) :</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
echo "-- Corriger un chat spécifique (remplacez 'ID_DU_CHAT' par l'id réel)\n";
echo "UPDATE chats SET gender = 'Male', status = 'king' WHERE id = 'ID_DU_CHAT';\n\n";
echo "-- Exemple :\n";
echo "UPDATE chats SET gender = 'Male', status = 'king' WHERE id = 'thor';";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<p><a href='cat_edit.php'>➕ Ajouter un nouveau chat</a> | ";
echo "<a href='cats.php'>📋 Liste admin</a> | ";
echo "<a href='javascript:location.reload()'>🔄 Recharger cette page</a></p>";
?>
