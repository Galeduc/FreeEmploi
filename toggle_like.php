<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$utilisateur_id = $_SESSION['user']['id'];
$annonce_id = isset($_POST['annonce_id']) ? intval($_POST['annonce_id']) : 0;

// Vérifier si l'utilisateur a déjà liké cette annonce
$stmt = $conn->prepare("SELECT * FROM likes WHERE utilisateur_id = ? AND annonce_id = ?");
$stmt->bind_param("ii", $utilisateur_id, $annonce_id);
$stmt->execute();
$result = $stmt->get_result();
$liked = $result->num_rows > 0;
$stmt->close();

if ($liked) {
    // Retirer le like
    $stmt = $conn->prepare("DELETE FROM likes WHERE utilisateur_id = ? AND annonce_id = ?");
    $stmt->bind_param("ii", $utilisateur_id, $annonce_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['liked' => false]);
} else {
    // Ajouter le like
    $stmt = $conn->prepare("INSERT INTO likes (utilisateur_id, annonce_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $utilisateur_id, $annonce_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['liked' => true]);
}
?>
