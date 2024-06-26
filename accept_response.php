<?php
session_start();

include 'db.php';

if (!isset($_SESSION['pro']) || !isset($_POST['id'])) {
    http_response_code(400);
    echo "Requête invalide.";
    exit();
}

$pro_id = $_SESSION['pro']['id'];
$response_id = $_POST['id'];

$stmt = $conn->prepare("UPDATE reponse SET accepted = 1 WHERE id = ? AND pro_id = ?");
$stmt->bind_param("ii", $response_id, $pro_id);

if ($stmt->execute()) {
    echo "Réponse acceptée avec succès.";
} else {
    http_response_code(500);
    echo "Erreur lors de l'acceptation de la réponse.";
}

$stmt->close();
$conn->close();
?>
