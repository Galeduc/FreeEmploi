<?php
session_start();

include 'db.php';

$login_error = '';

// Gestion du profil professionnel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_profile']) && isset($_POST['nom'])) {
    $societe = $_POST['nom'];
    $email_pro = $_POST['email'];
    $siret = $_POST['siret'];
    $id = $_POST['id'];

    $photo_profil = '';

    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['size'] > 0) {
        $target_dir = "img_profil/";
        $target_file = $target_dir . basename($_FILES["photo_profil"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["photo_profil"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }

        if ($_FILES["photo_profil"]["size"] > 5000000) {
            echo "Désolé, votre fichier est trop volumineux.";
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $target_file)) {
                $photo_profil = $target_file;
            } else {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        }
    } else {
        $stmt = $conn->prepare("SELECT profil_image FROM professionnels WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pro = $result->fetch_assoc();
        $stmt->close();

        $photo_profil = $pro['profil_image'];
    }

    if (empty($photo_profil)) {
        $photo_profil = NULL;
    }

    $stmt = $conn->prepare("UPDATE professionnels SET societe = ?, email_pro = ?, siret = ?, profil_image = ? WHERE id = ?");
    if ($stmt === false) {
        $login_error = "Erreur de connexion à la base de données.";
    } else {
        $stmt->bind_param("ssssi", $societe, $email_pro, $siret, $photo_profil, $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM professionnels WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pro = $result->fetch_assoc();
        $_SESSION['pro'] = $pro;
        $stmt->close();
        $conn->close();

        header('Location: profil_pro.php');
        exit();
    }
}

// Gestion des annonces
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $salaire = $_POST['salaire'];
    $pro_id = $_SESSION['pro']['id'];

    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES['banniere']) && $_FILES['banniere']['error'] == 0) {

        // Récupérer les informations sur le fichier
        $banniere_name = $_FILES['banniere']['name'];
        $banniere_tmp_name = $_FILES['banniere']['tmp_name'];
        $banniere_size = $_FILES['banniere']['size'];
        $banniere_type = $_FILES['banniere']['type'];

        // Vérifier la taille et le type du fichier
        if ($banniere_size > 5000000) {
            die('Désolé, votre fichier est trop volumineux.');
        }

        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        if (!in_array($banniere_type, $allowed_types)) {
            die('Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.');
        }

        // Générer un nouveau nom de fichier unique
        $banniere_name_parts = explode('.', $banniere_name);
        $banniere_extension = end($banniere_name_parts);
        $banniere_new_name = uniqid('annonce_banniere_', true) . '.' . $banniere_extension;

        // Déplacer le fichier vers le répertoire img_annonce/
        $banniere_target_path = 'img_annonce/' . $banniere_new_name;
        if (!move_uploaded_file($banniere_tmp_name, $banniere_target_path)) {
            die('Désolé, une erreur s\'est produite lors du téléchargement de votre fichier.');
        }

        // Enregistrer les données de l'annonce dans la base de données
        $stmt = $conn->prepare('INSERT INTO annonces (titre, description, salaire, pro_id, banniere) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssds', $titre, $description, $salaire, $pro_id, $banniere_target_path);
        // Utiliser le nouveau nom de fichier unique de la bannière
        $stmt->execute();
        echo "Chemin de la bannière stocké dans la base de données : " . $banniere_target_path;

        // Récupérer l'ID de la nouvelle annonce
        $annonce_id = $stmt->insert_id;

        // Rediriger l'utilisateur vers la page de profil
        header('Location: profil_pro.php');
        exit;

    } else {

        // Aucun fichier n'a été téléchargé, afficher un message d'erreur
        die('Désolé, aucun fichier n\'a été téléchargé.');

    }

}

// Mes reponses via bdd reponse id	name	email	cv_path	cover_letter_path



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeEmploi</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <style>
        body {
            background-image: url('img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        .card-transparent {
            background-color: rgba(255, 255, 255, 0.4); /* 80% opacity */
            border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border to highlight the card edges */
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<section class="min-h-screen flex flex-col md:flex-row items-center justify-center">
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 mb-5 md:mr-5 md:mb-0">
        <form action="" method="post" enctype="multipart/form-data" class="w-full">
            <h2 class="text-2xl font-bold text-center mb-5">Profil Professionnel</h2>
            <div class="flex flex-col gap-4">
                <label for="nom" class="text-sm">Société</label>
                <input type="text" name="nom" id="nom" class="p-2 mb-4 border border-gray-300 rounded" value="<?php echo $_SESSION['pro']['societe']; ?>" required>
            </div>
            <div class="flex flex-col gap-4">
                <label for="email" class="text-sm">Email Pro</label>
                <input type="email" name="email" id="email" class="p-2 mb-4 border border-gray-300 rounded" value="<?php echo $_SESSION['pro']['email_pro']; ?>" required>
            </div>
            <div class="flex flex-col gap-4">
                <label for="siret" class="text-sm">SIRET</label>
                <input type="text" name="siret" id="siret" class="p-2 border mb-4 border-gray-300 rounded" value="<?php echo $_SESSION['pro']['siret']; ?>" required>
            </div>
            <div class="flex flex-col gap-4">
                <label for="photo_profil" class="text-sm">Photo de profil</label>
                <input type="file" name="photo_profil" id="photo_profil" class="p-2 mb-4 border border-gray-300 rounded">
            </div>
            <input type="hidden" name="id" value="<?php echo $_SESSION['pro']['id']; ?>">
            <button type="submit" name="submit_profile" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enregistrer</button>
        </form>
    </div>
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 md:ml-5 md:mr-0">
        <form action="" method="post" enctype="multipart/form-data" class="w-full">
            <h2 class="text-2xl font-bold text-center mb-5">Créer une annonce</h2>
            <div class="flex flex-col gap-4">
                <label for="titre" class="text-sm">Titre</label>
                <input type="text" name="titre" id="titre" class="p-2 mb-4 border border-gray-300 rounded" required>
            </div>
            <div class="flex flex-col gap-4">
                <label for="description" class="text-sm">Description</label>
                <textarea name="description" id="description" class="p-2 mb-4 border border-gray-300 rounded" required></textarea>
            </div>
            <div class="flex flex-col gap-4">
                <label for="salaire" class="text-sm">Salaire</label>
                <input type="number" name="salaire" id="salaire" class="p-2 mb-4 border border-gray-300 rounded" required>
            </div>
            <div class="flex flex-col gap-4">
                <label for="banniere" class="text-sm">Bannière</label>
                <input type="file" name="banniere" id="banniere" class="p-2 mb-4 border border-gray-300 rounded" required>
                <p class="text-xs mb-4 text-gray-500">Taille recommandée de la bannière : 1400px * 400px</p>
            </div>
            <button type="submit" name="submit_announce" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enregistrer</button>
        </form>
    </div>
</section>

<?php
$stmt = $conn->prepare("SELECT * FROM annonces WHERE pro_id = ?");
$stmt->bind_param("i", $_SESSION['pro']['id']);
$stmt->execute();
$result = $stmt->get_result();
$annonces = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $conn->prepare("SELECT r.*, a.titre FROM reponse r LEFT JOIN annonces a ON r.annonce_id = a.id WHERE r.pro_id = ? AND r.accepted = 0");
$stmt->bind_param("i", $_SESSION['pro']['id']);
$stmt->execute();
$result = $stmt->get_result();
$reponses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Move the $conn->close(); to here, after you're done with all your database operations
$conn->close();

?>
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center my-8">Mes Annonces</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($annonces as $annonce) { ?>
            <div class="bg-white shadow-md rounded p-4 card-transparent">
                <img src="<?php echo $annonce['banniere']; ?>" alt="" class="w-full h-32 object-cover object-center mb-4">
                <h2 class="text-xl font-bold"><?php echo $annonce['titre']; ?></h2>
                <p class="text-gray-500"><?php echo $annonce['description']; ?></p>
                <p class="text-gray-500">Salaire Brut: <?php echo $annonce['salaire']; ?>€</p>
                <p class="text-gray-500">Publié le: <?php echo $annonce['created_at']; ?></p>
                <button onclick="deleteAnnounce('<?php echo $annonce['titre']; ?>')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Supprimer</button>
            </div>
        <?php } ?>
    </div>
</div>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center my-8">Mes Réponses</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($reponses as $reponse) { ?>
            <div class="bg-white shadow-md rounded p-4 card-transparent">
                <h2 class="text-xl font-bold"><?php echo $reponse['name']; ?></h2>
                <p class="text-gray-500"><?php echo $reponse['email']; ?></p>
                <?php if (isset($reponse['titre'])): ?>
                    <p class="text-gray-500">Annonce : <?php echo $reponse['titre']; ?></p>
                <?php endif; ?>
                <a href="<?php echo $reponse['cv_path']; ?>" class="text-blue-500 hover:text-blue-700">CV</a>
                <a href="<?php echo $reponse['cover_letter_path']; ?>" class="text-blue-500 hover:text-blue-700">Lettre de motivation</a>
                <button data-id="<?php echo $reponse['id']; ?>" class="accept-btn bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Accepter</button>
                <button data-id="<?php echo $reponse['id']; ?>" class="refuse-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Refuser</button>
            </div>
        <?php } ?>
    </div>
</div>


<script>
    function deleteAnnounce(titre) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette annonce?")) {
            // Soumettre le formulaire de suppression en utilisant AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Traiter la réponse ici si nécessaire
                    window.location.reload(); // Recharger la page après suppression
                }
            };
            xhr.send("titre=" + encodeURIComponent(titre));
        }
    }

    var refuseButtons = document.querySelectorAll('.refuse-btn');

    // Ajoutez un gestionnaire d'événements 'click' à chaque bouton
    refuseButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Récupérez l'ID de la réponse
            var id = this.getAttribute('data-id');

            // Envoyez une requête AJAX pour supprimer la réponse
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_response.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Rechargez la page après la suppression
                    location.reload();
                }
            };
            xhr.send('id=' + encodeURIComponent(id));
        });
    });

    var acceptButtons = document.querySelectorAll('.accept-btn');

    // Ajoutez un gestionnaire d'événements 'click' à chaque bouton
    acceptButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Récupérez l'ID de la réponse
            var id = this.getAttribute('data-id');

            // Envoyez une requête AJAX pour accepter la réponse
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'accept_response.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Rechargez la page après l'acceptation
                    location.reload();
                }
            };
            xhr.send('id=' + encodeURIComponent(id));
        });
    });
</script>


<?php
include 'footer.php';
?>
</body>
</html>
