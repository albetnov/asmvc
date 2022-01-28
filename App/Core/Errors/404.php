<!doctype html>
<html lang="en">

<head>
    <title>404</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if (!empty(BS5_CSS)) {
    ?>
        <link rel="stylesheet" href="<?= asset(BS5_CSS) ?>">
    <?php
    } else {
    ?>
        <style>
            .card {
                padding: 10px;
                border: 1px solid whitesmoke;
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            }

            .card-header {
                font-size: 30px;
                color: red;
            }

            a {
                color: royalblue;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }

            button {
                padding: 10px;
                border: none;
                background-color: rgb(82, 81, 81);
                color: white;
            }

            button:hover {
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
                background-color: whitesmoke;
                color: black;
                cursor: pointer;
            }

            .justify-content-center {
                margin: 20em auto 0px auto;
                width: 50%;
            }

            .card-body {
                margin-top: 1em;
            }
        </style>
    <?php } ?>
</head>

<body>
    <div class="container d-flex 100-vh align-items-center justify-content-center">
        <div class="card">
            <div class="card-header">
                Page Not Found
            </div>
            <div class="card-body">
                <button class="btn btn-sm btn-primary" onclick="location.href='<?= url(); ?>'">Go Home</button>
            </div>
        </div>
    </div>
</body>

</html>