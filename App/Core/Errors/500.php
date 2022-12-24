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
    <main class="container mx-auto p-14 min-h-screen w-full border-red-500 flex items-center justify-between text-zinc-800 flex-col lg:flex-row">
        <div>
            <h1 class="text-8xl font-bold">500</h1>
            <p class="text-4xl font-semibold">Oops!</p>
            <p class="text-lg"><?= $message ?? "Something Went Wrong" ?></p>
            <div class="block border border-zinc-600 py-3 px-10 mt-4 shadow-lg shadow-zinc-300 transition-all delay-150 hover:cursor-wait active:opacity-80">Comeback Soon</div>
        </div>
        <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-695.jpg?w=826&t=st=1671811502~exp=1671812102~hmac=d3f7c7f0597f5edf3f73ddca8ef740c9925fe97e6adc34987508f928a5924999" alt="severr">
    </main>
</body>

</html>