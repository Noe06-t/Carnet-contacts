<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Identifiants invalides';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #376868ff 0%, #6ea8a8ff  100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            color: #4a587b;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            padding: 32px 32px 24px 32px;
            min-width: 320px;
            max-width: 350px;
            margin: 0 auto;
        }
        h2 {
            color: #4a587b;
            background: #e9eef7;
            padding: 10px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .btn-primary {
            background: #5e6fa3;
            border: none;
        }
        .btn-primary:hover {
            background: #4a587b;
        }
        .error {
            color: #fff;
            background: #e57373;
            padding: 8px 12px;
            border-radius: 7px;
            margin-bottom: 10px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(100,120,180,0.08);
        }
    </style>
</head>
<body>
    <div class="login-container mt-5">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <p class="error mb-3"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</body>
</html>
