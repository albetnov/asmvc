<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Page Not Found</title>
    <link rel="stylesheet" href="<?= asset(TW_CSS) ?>">
</head>

<body>
    <main class="container mx-auto p-14 min-h-screen w-full border-red-500 flex items-center justify-between text-zinc-800 flex-col lg:flex-row">
        <div>
            <h1 class="text-8xl font-bold">404</h1>
            <p class="text-4xl font-semibold">Oops!</p>
            <p class="text-lg"><?= $message ?? "Page Not Found" ?></p>
            <a href="/" class="block border border-zinc-600 py-3 px-10 mt-4 shadow-lg shadow-zinc-300 transition-all delay-150 hover:-translate-y-1 active:opacity-80">Back To Home</a>
        </div>
        <img src="https://img.freepik.com/free-vector/empty-concept-illustration_114360-1233.jpg?w=826&t=st=1671810472~exp=1671811072~hmac=e77c998e11017ad2e15c4c5388128635c01e82934a2dd6a018d4c68957e20abb" alt="notfound">
    </main>
</body>

</html>