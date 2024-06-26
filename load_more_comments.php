<?php
include 'db.php';

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$annonce_id = isset($_GET['annonce_id']) ? intval($_GET['annonce_id']) : 0;

$stmt = $conn->prepare("SELECT c.*, u.prenom FROM commentaires c JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.annonce_id = ? ORDER BY c.created_at DESC LIMIT 10 OFFSET ?");
$stmt->bind_param("ii", $annonce_id, $offset);
$stmt->execute();
$result = $stmt->get_result();
$commentaires = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($commentaires as $commentaire) {
    echo '<div class="bg-gray-100 p-4 card-transparent mb-4">';
    echo '<p class="text-gray-800">' . htmlspecialchars($commentaire['contenu']) . '</p>';
    echo '<p class="text-gray-600 text-sm">Posté par: ' . htmlspecialchars($commentaire['prenom']) . '</p>';
    echo '<p class="text-gray-600 text-sm">Date: ' . htmlspecialchars($commentaire['created_at']) . '</p>';
    echo '</div>';
}
?>
<style>
    .card-transparent {
        background-color: rgba(255, 255, 255, 0.4); /* 80% opacity */
        border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border to highlight the card edges */
    }
</style>
