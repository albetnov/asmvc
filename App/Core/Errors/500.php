<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 | Internal Server Error</title>
    <link rel="stylesheet" href="<?= asset(TW_CSS) ?>">
</head>

<body>
    <main class="container mx-auto py-14 min-h-screen relative flex flex-col lg:flex-row w-full justify-center items-center w-full">
        <div class="mx-auto">
            <h1 class="text-8xl font-bold">500</h1>
        </div>
        <div class="mx-auto">
            <p class="text-4xl font-semibold">Oops! Look live we've got down.</p>
            <p class="text-lg mb-3"><?= $message ?? "Something Went Wrong" ?></p>
        </div>
        <a href="/" class="absolute top-[60%] w-52 lg:top-auto lg:bottom-3 btn btn-outline lg:w-96 mt-10">Back To Home</a>
    </main>
</body>

</html>