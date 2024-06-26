<?php
include 'db.php';

$notificationCount = $_POST['notificationCount'];

$stmt = $conn->prepare("SELECT r.*, a.titre, u.prenom FROM reponse r JOIN annonces a ON r.annonce_id = a.id JOIN utilisateurs u ON a.pro_id = u.id WHERE r.pro_id = ? AND r.accepted = 1 ORDER BY r.date_envoi DESC LIMIT ?,5");
$stmt->bind_param("ii", $user_id, $notificationCount);
$stmt->execute();
$result = $stmt->get_result();
$accepted_responses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($accepted_responses as $response) {
    echo '<div class="bg-gray-100 p-4 card-transparent mb-4">';
    echo '<p class="text-gray-800">Votre réponse pour l\'annonce ' . htmlspecialchars($response['annonce_id']) . ' a été acceptée.</p>';
    echo '<p class="text-gray-600 text-sm">Posté par: ' . htmlspecialchars($response['prenom']) . '</p>';
    echo '<p class="text-gray-600 text-sm">Date: ' . htmlspecialchars($response['date_envoi']) . '</p>';
    echo '</div>';
}
?>