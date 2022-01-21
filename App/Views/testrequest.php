<?php

use Albet\Ppob\Core\Validator;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Request</title>
</head>

<body>
    <form action="<?= url('/kirim') ?>" method="POST">
        <?= csrf()->field() ?>
        <input type="text" name="test">
        <button type="submit">KIRIM</button>
        <?= old('test') ?>
        <?= Validator::validMsg('test') ?>
    </form>
</body>

</html>