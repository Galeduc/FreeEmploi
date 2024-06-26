<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeEmploi</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
<?php
include 'db.php';

$titre = isset($_POST['titre']) ? $_POST['titre'] : '';
$salaire = isset($_POST['salaire']) ? $_POST['salaire'] : '';
$entreprise = isset($_POST['entreprise']) ? $_POST['entreprise'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$contrat = isset($_POST['contrat']) ? $_POST['contrat'] : '';

// Vérifier si la connexion à la base de données est établie
if ($conn) {
    $stmt = $conn->prepare("SELECT annonces.* FROM annonces JOIN professionnels ON annonces.pro_id = professionnels.id WHERE titre LIKE ? AND salaire >= ? AND professionnels.societe LIKE ? AND annonces.created_at >= ? AND annonces.description LIKE ?");
    $likeTitre = "%" . $titre . "%";
    $likeEntreprise = "%" . $entreprise . "%";
    $likeContrat = "%" . $contrat . "%";
    $stmt->bind_param("sisss", $likeTitre, $salaire, $likeEntreprise, $date, $likeContrat);
    $stmt->execute();
    $result = $stmt->get_result();
    $annonces = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Erreur de connexion à la base de données.";
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

include 'navbar.php';
?>
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center my-8">Nos Annonces</h1>
    <div class="flex items-center justify-center mt-8">
        <div class="m-10 w-screen max-w-screen-md">
            <div class="flex flex-col">
                <div class="rounded-xl border border-gray-200 bg-white p-6 card-transparent shadow-lg">
                    <form class="" method="POST" action="emplois.php">
                        <div class="relative mb-10 w-full flex items-center justify-between rounded-md">
                            <svg class="absolute left-2 block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" name="titre" class="h-12 w-full cursor-text rounded-md border border-gray-100 bg-gray-100 py-4 pr-40 pl-12 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Rechercher une annonce" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <div class="flex flex-col">
                                <label for="salaire" class="text-sm font-medium text-stone-600">Salaire</label>
                                <input type="number" name="salaire" id="salaire" placeholder="Salaire Minimum" class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            </div>

                            <div class="flex flex-col">
                                <label for="entreprise" class="text-sm font-medium text-stone-600">Entreprise</label>
                                <input type="text" name="entreprise" id="entreprise" placeholder="Entreprise" class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            </div>

                            <div class="flex flex-col">
                                <label for="date" class="text-sm font-medium text-stone-600">Date de publication</label>
                                <input type="date" name="date" id="date" class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            </div>

                            <div class="flex flex-col">
                                <label for="contrat" class="text-sm font-medium text-stone-600">Type de contrat</label>
                                <select id="contrat" name="contrat" class="mt-2 block w-full cursor-pointer rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Tous</option>
                                    <option value="CDI">CDI</option>
                                    <option value="CDD">CDD</option>
                                    <option value="Stage">Stage</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 grid w-full grid-cols-2 justify-end space-x-4 md:flex">
                            <button class="rounded-lg bg-gray-200 px-8 py-2 font-medium text-gray-700 outline-none hover:opacity-80 focus:ring">Réinitialiser</button>
                            <button class="rounded-lg bg-blue-600 px-8 py-2 font-medium text-white outline-none hover:opacity-80 focus:ring">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <?php if (empty($annonces)) : ?>
            <p class="text-center text-gray-700">OUPSS... Aucune annonce ne correspond à votre recherche.</p>
        <?php else : ?>
        <?php foreach ($annonces as $annonce) { ?>
            <div class="bg-white mt-16 rounded-lg shadow-md overflow-hidden flex relative h-36 card-transparent" style="max-height: 400px;">
                <img src="<?php echo $annonce['banniere']; ?>" alt="<?php echo $annonce['titre']; ?>" class="w-1/3 h-auto object-contain">
                <div class="p-4 w-2/3 relative">
                    <h2 class="text-xl font-bold mb-2 overflow-hidden text-truncate"><?php echo $annonce['titre']; ?></h2>
                    <p class="text-gray-700 text-base mb-2 overflow-hidden text-truncate"><?php echo $annonce['description']; ?></p>
                    <p class="text-gray-800 text-lg overflow-hidden text-truncate">Salaire Brut: <?php echo $annonce['salaire']; ?>€</p>
                    <p class="text-gray-800 text-lg overflow-hidden text-truncate">Publié le: <?php echo $annonce['created_at']; ?></p>
                    <button class="absolute top-1/2 right-2 transform -translate-y-1/2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:bg-blue-700" onclick="afficherDetails('<?php echo urlencode($annonce['titre']); ?>')">Afficher</button>
                </div>
            </div>

            <style>
                .card-transparent {
                    background-color: rgba(255, 255, 255, 0.4); /* 80% opacity */
                    border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border to highlight the card edges */
                }
            </style>

            <script>
                function afficherDetails(titre) {
                    window.location.href = 'annonce_details.php?titre=' + titre;
                }

                function postuler(annonceId) {
                    // Log the annonceId to check if it's correctly passed
                    console.log(annonceId);

                    // Logic for applying to the job goes here
                    window.location.href = 'postuler.php?id=' + annonceId;
                }

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
        <?php } ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'?>
</body>
</html>
