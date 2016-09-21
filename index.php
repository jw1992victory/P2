<!doctype html>
<html>
<head>

    <title>Password Generator</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="css/P2.css">

    <?php
        require 'logic.php';
    ?>

</head>
<body>

    <h1>xkcd Password Generator</h1>

    <div class="result"><p class="password"><?php echo $password ?></p></div>

    <form  method='POST' action='index.php'>
        <p># of Words <input type="text" name="length" value="<?php echo isset($_POST['length']) ? $_POST['length'] : '' ?>">(max of 9)</p>
        <p><input type="checkbox" name="contain_number" <?php if (!empty($_POST['contain_number'])): ?> checked="checked"<?php endif; ?>>Add a number</p>
        <p><input type="checkbox" name="contain_symbol" <?php if (!empty($_POST['contain_symbol'])): ?> checked="checked"<?php endif; ?>>Add a symbol</p>

        <?php if(isset($error)): ?>
            <div class='error'>Error: <?php echo $error; ?></div>
        <?php endif ?>

        <?php if(isset($notice)): ?>
            <div class='notice'>Notice: <?php echo $notice; ?></div>
        <?php endif ?>

        <input type="submit" class="button" value="Gimme another">
    </form>

    <img src="img/password_strength.png" alt="password strngth gif">
</body>
</html>