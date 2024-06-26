<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: cadmin.php");
    exit();
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
        // Récupère dans des card : id	nom	adresse	ville	code_postal	telephone	email	message	date_creation
        $stmt = $conn->prepare("SELECT * FROM contacts");
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        ?>
        <h1 class="text-3xl font-bold text-center my-8">Messages</h1>
        <div class="grid grid-cols-1 gap-4">
            <?php if (empty($messages)) : ?>
                <p class="text-center text-gray-700">Aucun message pour le moment.</p>
            <?php else : ?>
                <?php foreach ($messages as $message): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold"><?= $message['nom'] ?></h2>
                        <p class="text-gray-600">Adresse: <?= $message['adresse'] ?>, <?= $message['ville'] ?>, <?= $message['code_postal'] ?></p>
                        <p class="text-gray-600">Téléphone: <?= $message['telephone'] ?></p>
                        <p class="text-gray-600">Email: <?= $message['email'] ?></p>
                        <p class="text-gray-600">Message: <?= $message['message'] ?></p>
                        <p class="text-gray-600">Date de création: <?= $message['date_creation'] ?></p>
                        <!-- Bouton Archiver -->
                        <form action="archive.php" method="POST">
                            <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                                Archiver
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
    </div>
</div>

    <script>
    </script>
</body>
</html>


