<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>tipe</th>
                <th>harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $data) : ?>
                <tr>
                    <td><?= $data->id ?></td>
                    <td><?= $data->tipe ?></td>
                    <td><?= $data->harga ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>