<?php
if (!empty(BS5_CSS) && !empty(BS5_JS)) {
?>
    <!doctype html>
    <html lang="en">

    <head>
        <title>ASMVC</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="<?= asset(BS5_CSS) ?>">
    </head>

    <body>
        <div class="card">
            <div class="card-header">Hi!</div>
            <div class="card-body">
                <h4 class="card-title">A Simple MVC</h4>
                <p class="card-text">Created by Albet Novendo.</p>
                <p>Follow me:</p>
                <a href="https://github.com/albetnov">Github</a> / <a href="https://instagram.com/al_nv4">Instagram</a>
            </div>
        </div>
    </body>

    </html>
<?php } else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .card {
                padding: 10px;
                border: 1px solid whitesmoke;
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            }

            .card-header {
                font-size: 30px;
                color: rgb(82, 81, 81);
            }

            a {
                color: royalblue;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
        <title>ASMVC</title>
    </head>

    <body>
        <div class="card">
            <div class="card-header">A Simple MVC</div>
            <hr>
            <h4 class="card-title">A Simple MVC</h4>
            <p>Created by Albet Novendo.</p>
            <p>Follow me:</p>
            <a href="https://github.com/albetnov">Github</a> / <a href="https://instagram.com/al_nv4">Instagram</a>
        </div>

    </body>

    </html>


<?php } ?>