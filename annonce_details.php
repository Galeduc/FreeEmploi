<?php
session_start();
include 'db.php';

// Récupérer l'annonce par titre (ou idéalement par ID)
$titre = isset($_GET['titre']) ? urldecode($_GET['titre']) : '';
$stmt = $conn->prepare("SELECT * FROM annonces WHERE titre = ?");
$stmt->bind_param("s", $titre);
$stmt->execute();
$result = $stmt->get_result();
$annonce = $result->fetch_assoc();
$stmt->close();

if (!$annonce) {
    // Rediriger vers la page principale si l'annonce n'existe pas
    header("Location: emplois.php");
    exit();
}

// Vérifier si l'utilisateur a déjà liké cette annonce
$isLiked = false;
if (isset($_SESSION['user'])) {
    $utilisateur_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("SELECT * FROM likes WHERE utilisateur_id = ? AND annonce_id = ?");
    $stmt->bind_param("ii", $utilisateur_id, $annonce['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $isLiked = $result->num_rows > 0;
    $stmt->close();
}

// Traitement du formulaire de commentaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contenu'])) {
    $contenu = $_POST['contenu'];
    $utilisateur_id = $_SESSION['user']['id']; // Récupérer l'ID de l'utilisateur connecté
    // Insérer le commentaire dans la base de données
    $stmt = $conn->prepare("INSERT INTO commentaires (annonce_id, utilisateur_id, contenu) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $annonce['id'], $utilisateur_id, $contenu);
    $stmt->execute();
    $stmt->close();
}

// Récupérer les commentaires pour cette annonce
$stmt = $conn->prepare("SELECT c.*, u.prenom FROM commentaires c JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.annonce_id = ? ORDER BY c.created_at DESC LIMIT 5");
$stmt->bind_param("i", $annonce['id']);
$stmt->execute();
$result = $stmt->get_result();
$commentaires = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fermer la connexion à la base de données
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($annonce['titre']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
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
<div class="container mx-auto p-6">
    <div class="bg-white card-transparent p-6 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/2">
                <a href="emplois.php" class="text-blue-600 hover:text-blue-700 focus:outline-none focus:text-blue-700">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <img src="<?php echo $annonce['banniere']; ?>" alt="<?php echo htmlspecialchars($annonce['titre']); ?>" class="w-full h-auto object-contain rounded-md">
            </div>
            <div class="w-full md:w-1/2 md:pl-6 mt-6 md:mt-0">
                <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($annonce['titre']); ?></h1>
                <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($annonce['description'])); ?></p>
                <p class="text-lg text-gray-800 mb-4">Salaire Brut: <?php echo htmlspecialchars($annonce['salaire']); ?>€</p>
                <p class="text-lg text-gray-800 mb-4">Publié le: <?php echo htmlspecialchars($annonce['created_at']); ?></p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="#" onclick="postuler(<?php echo $annonce['id']; ?>);" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Postuler</a>
                    <button class="px-4 py-2 text-white rounded-full focus:outline-none <?php echo $isLiked ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-600 hover:bg-gray-700'; ?>" onclick="toggleLike(<?php echo $annonce['id']; ?>)">
                        <i class="fas fa-heart"></i> <?php echo $isLiked ? 'Unlike' : 'Like'; ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <div class="bg-white p-6 card-transparent shadow-lg">
        <h1 class="text-3xl font-bold mb-4">Commentaires</h1>
        <div id="commentaires">
            <?php foreach ($commentaires as $commentaire): ?>
                <div class="bg-gray-100 card-transparent p-4 rounded-lg mb-4">
                    <p class="text-gray-800"><?php echo htmlspecialchars($commentaire['contenu']); ?></p>
                    <p class="text-gray-600 text-sm">Posté par: <?php echo htmlspecialchars($commentaire['prenom']); ?></p>
                    <p class="text-gray-600 text-sm">Date: <?php echo htmlspecialchars($commentaire['created_at']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-center items-center">
            <button id="loadMore" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                <i class="fas fa-arrow-down"></i>
            </button>
        </div>
        <?php if (isset($_SESSION['user'])): ?>
            <form id="commentaireForm" class="mt-4" method="post">
                <textarea name="contenu" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Votre commentaire"></textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Envoyer</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<style>
    .card-transparent {
        background-color: rgba(255, 255, 255, 0.4); /* 80% opacity */
        border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border to highlight the card edges */
    }
</style>

<?php include 'footer.php'; ?>
<script>
    function postuler(annonceId) {
        // Log the annonceId to check if it's correctly passed
        console.log(annonceId);

        // Logic for applying to the job goes here
        window.location.href = 'postuler.php?id=' + annonceId + '&banniere=' + encodeURIComponent('<?php echo $annonce['banniere']; ?>');
    }

    let offset = 5;
    document.getElementById('loadMore').addEventListener('click', function() {
        fetch('load_more_comments.php?offset=' + offset + '&annonce_id=' + <?php echo $annonce['id']; ?>)
            .then(response => response.text())
            .then(data => {
                document.getElementById('commentaires').innerHTML += data;
                offset += 10;
            });
    });

    function toggleLike(annonceId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'toggle_like.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                const response = JSON.parse(this.responseText);
                const likeButton = document.querySelector('button[onclick="toggleLike(' + annonceId + ')"]');
                if (response.liked) {
                    likeButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                    likeButton.classList.add('bg-red-600', 'hover:bg-red-700');
                    likeButton.innerHTML = '<i class="fas fa-heart"></i> Unlike';
                } else {
                    likeButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                    likeButton.classList.add('bg-gray-600', 'hover:bg-gray-700');
                    likeButton.innerHTML = '<i class="fas fa-heart"></i> Like';
                }
            }
        };
        xhr.send('annonce_id=' + annonceId);
    }
</script>
</body>
</html>
