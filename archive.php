<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: cadmin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];

    // Récupérer le message à archiver
    $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();
    $stmt->close();

    // Insérer le message dans la table d'archives
    $stmt = $conn->prepare("INSERT INTO archives (nom, adresse, ville, code_postal, telephone, email, message, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $message['nom'], $message['adresse'], $message['ville'], $message['code_postal'], $message['telephone'], $message['email'], $message['message'], $message['date_creation']);
    $stmt->execute();
    $stmt->close();

    // Supprimer le message de la table des messages
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
</head>
<style>
    body {
        background-image: url('img/background.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
    }
</style>
<body class="bg-gray-100">
<div class="flex h-screen">
    <!-- Barre latérale gauche -->
    <div class="bg-gray-800 text-white w-20 flex flex-col items-center">
        <span class="text-xs mt-4 font-medium">FreeEmploi</span>
        <a href="admin.php" class="mt-2 p-2 hover:bg-gray-700">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Travel%20and%20places/House.png" alt="House" width="25" height="25" />
        </a>
        <a href="message.php" class="mt-2 p-2 hover:bg-gray-700">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Receipt.png" alt="Receipt" width="25" height="25" />
        </a>
        <a href="archive.php" class="mt-2 p-2 hover:bg-gray-700">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Card%20Index%20Dividers.png" alt="Card Index Dividers" width="25" height="25" />
        </a>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 flex flex-col items-center justify-start p-4 overflow-y-auto">
        <?php
        // Récupérer les données des archives depuis la base de données
        $stmt = $conn->prepare("SELECT * FROM archives");
        $stmt->execute();
        $result = $stmt->get_result();
        $archives = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        ?>
        <h1 class="text-3xl font-bold text-center my-8">Archives</h1>
        <div class="grid grid-cols-1 gap-4">
            <?php foreach ($archives as $archive): ?>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p><strong>Nom :</strong> <?php echo $archive['nom']; ?></p>
                    <p><strong>Adresse :</strong> <?php echo $archive['adresse']; ?></p>
                    <p><strong>Ville :</strong> <?php echo $archive['ville']; ?></p>
                    <p><strong>Code Postal :</strong> <?php echo $archive['code_postal']; ?></p>
                    <p><strong>Téléphone :</strong> <?php echo $archive['telephone']; ?></p>
                    <p><strong>Email :</strong> <?php echo $archive['email']; ?></p>
                    <p><strong>Message :</strong> <?php echo $archive['message']; ?></p>
                    <p><strong>Date de création :</strong> <?php echo $archive['date_creation']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
