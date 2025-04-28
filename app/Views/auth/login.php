<?php

use App\Core\Asset;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?= Asset::url('css/style.css') ?>">
</head>

<body>
    <h1>Login</h1>

    <?php if (!empty($error)) : ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/login">
        <input type="text" name="username" placeholder="Username" required> <br><br>
        <input type="password" name="password" placeholder="Password" required> <br><br>
        <button type="submit">Login</button>
    </form>

    <script src="<?= Asset::url('js/app.js') ?>"></script>
</body>

</html>