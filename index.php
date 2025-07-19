<?php
session_start();
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$msg = '';


// Ajout d'un contact
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contact'])) {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($prenom && $nom && $tel && $email) {
        $fp = fopen('contact.csv', 'a');
        fputcsv($fp, [$prenom, $nom, $tel, $email]);
        fclose($fp);
        $msg = 'Contact ajouté !';
    } else {
        $msg = 'Tous les champs sont obligatoires.';
    }
}

// Lecture des contacts
$contacts = [];
if (file_exists('contact.csv')) {
    $fp = fopen('contact.csv', 'r');
    while (($data = fgetcsv($fp)) !== false) {
        if (count($data) === 4) {
            $contacts[] = $data;
        }
    }
    fclose($fp);
}

// Recherche
$search = trim($_GET['search'] ?? '');
if (isset($_GET['search'])) {
    if ($search !== '') {
        $contacts = array_filter($contacts, function($c) use ($search) {
            return stripos($c[0], $search) !== false || stripos($c[1], $search) !== false;
        });
    } else {
        // Si la recherche est vide, on réaffiche tous les contacts
        if (file_exists('contact.csv')) {
            $contacts = [];
            $fp = fopen('contact.csv', 'r');
            while (($data = fgetcsv($fp)) !== false) {
                if (count($data) === 4) {
                    $contacts[] = $data;
                }
            }
            fclose($fp);
        }
    }
    // Après la recherche, on vide la barre de recherche
    $search = '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carnet de contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div style="position: absolute; top: 20px; right: 56px;">
        <a href="?logout=1" style="color: #380e0eff;">Déconnexion</a>
    </div>
    <div class="container container-custom bg-white rounded shadow p-4 mt-4">
        <h2 class="text-center text-dark mb-4">Ajouter un contact</h2>
        <?php if (!empty($msg)) echo '<p class="success">'.htmlspecialchars($msg).'</p>'; ?>
        <!-- Formulaire d'ajout de contact   -->
        <form method="post">
            <input type="hidden" name="add_contact" value="1">
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="tel" name="tel" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Ajouter</button>
        </form>
    </div>
    <!-- barre de recherche -->
    <div class="container container-custom bg-white rounded shadow p-4 mt-4">
        <h2 class="text-center text-dark mb-4">Rechercher un contact</h2>
        <form method="get" class="row g-2 justify-content-center">
            <div class="col-8">
                <input type="text" class="form-control" name="search" placeholder="Nom ou prénom" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Rechercher</button>
            </div>
        </form>
    </div>
    <div class="container container-custom bg-white rounded shadow p-4 mt-4 mb-5">
        <h2 class="text-center text-dark mb-4">Liste des contacts</h2>
        <div class="table-responsive">
            <!-- tableau des contacts -->
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr><th>Prénom</th><th>Nom</th><th>Téléphone</th><th>Email</th></tr>
                </thead>
                <tbody>
                <?php foreach ($contacts as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c[0]) ?></td>
                        <td><?= htmlspecialchars($c[1]) ?></td>
                        <td><?= htmlspecialchars($c[2]) ?></td>
                        <td><?= htmlspecialchars($c[3]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
  
        

</body>
</html>




<!-- style -->
     <style>
        body {
            background: linear-gradient(135deg, #376868ff 0%, #6ea8a8ff 100%);
            color: #fff;
            min-height: 100vh;
        }
        .container-custom {
            max-width: 500px;
            margin: 30px auto 0 auto;
        }
        .table thead th {
            background: #1f6444ff;
            color: #fff;
        }
        .table-striped > tbody > tr:nth-of-type(even) {
            background-color: #f1f7fa;
        }
        .success {
            color: #fff;
            background: #7ed6a7;
            padding: 8px 12px;
            border-radius: 7px;
            margin-bottom: 10px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(100,120,180,0.08);
        }
        a {
            color: #7ed6a7;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin: 20px auto 10px auto;
        }
    </style>





<?php   
// Déconnexion : destruction de la session si ?logout=1
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>