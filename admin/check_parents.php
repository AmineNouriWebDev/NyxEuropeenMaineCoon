<?php
// Test pour vérifier les Kings et Queens dans la base de données
require_once '../includes/config.php';

echo "<h2>Vérification des Kings et Queens</h2>";

// Vérifier les Kings
echo "<h3>Kings (Mâles Reproducteurs)</h3>";
$stmt = $pdo->query("SELECT id, name, gender, status FROM chats WHERE gender = 'Male' AND status = 'king' ORDER BY name");
$kings = $stmt->fetchAll();

if (empty($kings)) {
    echo "<p style='color: red;'>❌ Aucun King trouvé dans la base de données.</p>";
    echo "<p><strong>Pour ajouter un King :</strong></p>";
    echo "<ol>";
    echo "<li>Allez sur <a href='cat_edit.php'>cat_edit.php</a></li>";
    echo "<li>Sélectionnez 'King (Mâle Reproducteur)' dans Type de Fiche</li>";
    echo "<li>Remplissez le formulaire et soumettez</li>";
    echo "</ol>";
} else {
    echo "<p style='color: green;'>✅ " . count($kings) . " King(s) trouvé(s):</p>";
    echo "<ul>";
    foreach ($kings as $king) {
        echo "<li><strong>" . htmlspecialchars($king['name']) . "</strong> (ID: " . $king['id'] . ")</li>";
    }
    echo "</ul>";
}

// Vérifier les Queens
echo "<h3>Queens (Femelles Reproductrices)</h3>";
$stmt = $pdo->query("SELECT id, name, gender, status FROM chats WHERE gender = 'Female' AND status = 'queen' ORDER BY name");
$queens = $stmt->fetchAll();

if (empty($queens)) {
    echo "<p style='color: red;'>❌ Aucune Queen trouvée dans la base de données.</p>";
    echo "<p><strong>Pour ajouter une Queen :</strong></p>";
    echo "<ol>";
    echo "<li>Allez sur <a href='cat_edit.php'>cat_edit.php</a></li>";
    echo "<li>Sélectionnez 'Queen (Femelle Reproductrice)' dans Type de Fiche</li>";
    echo "<li>Remplissez le formulaire et soumettez</li>";
    echo "</ol>";
} else {
    echo "<p style='color: green;'>✅ " . count($queens) . " Queen(s) trouvée(s):</p>";
    echo "<ul>";
    foreach ($queens as $queen) {
        echo "<li><strong>" . htmlspecialchars($queen['name']) . "</strong> (ID: " . $queen['id'] . ")</li>";
    }
    echo "</ul>";
}

// Vérifier tous les chats
echo "<h3>Tous les chats dans la base</h3>";
$stmt = $pdo->query("SELECT id, name, gender, status FROM chats ORDER BY status, name");
$all_cats = $stmt->fetchAll();

if (empty($all_cats)) {
    echo "<p style='color: red;'>❌ Aucun chat dans la base de données.</p>";
} else {
    echo "<p>Total: " . count($all_cats) . " chat(s)</p>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Nom</th><th>Genre</th><th>Status</th><th>ID</th></tr>";
    foreach ($all_cats as $cat) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($cat['name']) . "</td>";
        echo "<td>" . $cat['gender'] . "</td>";
        echo "<td><strong>" . $cat['status'] . "</strong></td>";
        echo "<td>" . $cat['id'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<p><a href='cat_edit.php'>➕ Ajouter un nouveau chat</a> | <a href='cats.php'>📋 Liste admin</a></p>";
?>
