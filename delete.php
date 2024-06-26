<?php
session_start();

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'])) {
    $titre = $_POST['titre'];

    // Préparer la requête pour supprimer les commentaires
    $stmt = $conn->prepare("DELETE FROM commentaires WHERE annonce_id IN (SELECT id FROM annonces WHERE titre = ?)");
    $stmt->bind_param("s", $titre);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Les commentaires ont été supprimés, maintenant supprimer l'annonce
        $stmt = $conn->prepare("DELETE FROM annonces WHERE titre = ?");
        $stmt->bind_param("s", $titre);

        if ($stmt->execute()) {
            echo "L'annonce et ses commentaires ont été supprimés avec succès.";
        } else {
            echo "Une erreur s'est produite lors de la suppression de l'annonce.";
        }
    } else {
        echo "Une erreur s'est produite lors de la suppression des commentaires.";
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    echo "Erreur : Requête invalide.";
}
?>
