<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Récupérer l'identifiant de l'utilisateur connecté
$utilisateur_id = $_SESSION['user']['id'];

// Sélectionner les annonces aimées par l'utilisateur
$stmt = $conn->prepare("SELECT annonces.* FROM annonces INNER JOIN likes ON annonces.id = likes.annonce_id WHERE likes.utilisateur_id = ?");
$stmt->bind_param("i", $utilisateur_id);
$stmt->execute();
$result = $stmt->get_result();
$annonces = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Likes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
<?php include 'navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center my-8">Mes Likes</h1>
    <div class="grid grid-cols-1 gap-4">
        <?php if (empty($annonces)) : ?>
            <p class="text-center text-gray-700">Vous n'avez aimé aucune annonce pour le moment.</p>
        <?php else : ?>
            <?php foreach ($annonces as $annonce) { ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="flex justify-center items-center">
                        <img src="<?php echo $annonce['banniere']; ?>" alt="<?php echo $annonce['titre']; ?>" class="object-cover object-center w-full h-48 md:w-auto">
                    </div>
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2"><?php echo $annonce['titre']; ?></h2>
                        <p class="text-gray-700 text-base mb-2"><?php echo $annonce['description']; ?></p>
                        <p class="text-gray-800 text-lg">Salaire Brut: <?php echo $annonce['salaire']; ?>€</p>
                        <p class="text-gray-800 text-lg">Publié le: <?php echo $annonce['created_at']; ?></p>
                        <a href="annonce_details.php?id=<?php echo $annonce['id']; ?>" class="block mt-4 bg-blue-500 text-white px-4 py-2 rounded-md text-center hover:bg-blue-600">Voir l'annonce</a>
                    </div>
                </div>
            <?php } ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'?>
</body>
</html>


