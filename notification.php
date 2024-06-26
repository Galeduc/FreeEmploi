<?php
session_start(); // Start the session

include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user']['id'];

// Fetch the user's email using their ID
$stmt = $conn->prepare("SELECT email FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit();
}

$user_email = $user['email'];

// Fetch accepted responses for the user
$stmt = $conn->prepare("
    SELECT r.*, a.titre, u.prenom 
    FROM reponse r 
    JOIN annonces a ON r.annonce_id = a.id 
    JOIN utilisateurs u ON a.pro_id = u.id 
    WHERE r.email = ? AND r.accepted = 1 
    ORDER BY r.date_envoi DESC
");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$accepted_responses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
    </style>
</head>

<body>
<?php include 'navbar.php'; ?>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold text-center mb-4">Notifications</h1>
    <?php if ($accepted_responses) : ?>
        <?php foreach ($accepted_responses as $response) : ?>
            <div class="bg-gray-100 p-4 card-transparent mb-4">
                <p class="text-gray-800">Votre réponse pour l'annonce <?php echo htmlspecialchars($response['titre']); ?> a été acceptée.</p>
                <p class="text-gray-600 text-sm">Posté par: <?php echo htmlspecialchars($response['prenom']); ?></p>
                <p class="text-gray-600 text-sm">Date: <?php echo htmlspecialchars($response['date_envoi']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="text-center text-gray-500">Aucune notification pour le moment.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>

</html>
