<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Dapur Negriku</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-lime-900 text-white p-6 hidden md:block">
        <h2 class="text-2xl font-semibold mb-8">Dapur Negriku</h2>
        <nav class="space-y-4">
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-lime-800">Dashboard</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-lime-800">Data Produk</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-lime-800">Pesanan</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-lime-800">Pengguna</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-lime-800">Pengaturan</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6">
        <!-- Navbar top -->
        <header class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
            <h1 class="text-xl font-semibold">Dashboard</h1>
            <div class="flex items-center gap-4">
                <span class="hidden sm:block text-gray-700">Admin</span>
                <img src="/images/user.png" class="w-10 h-10 rounded-full" />
            </div>
        </header>

        <!-- Cards section -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total Produk</p>
                <h2 class="text-2xl font-semibold mt-1">120</h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Pesanan Masuk</p>
                <h2 class="text-2xl font-semibold mt-1">48</h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Pengguna Terdaftar</p>
                <h2 class="text-2xl font-semibold mt-1">350</h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Pendapatan Bulan Ini</p>
                <h2 class="text-2xl font-semibold mt-1">Rp 12.450.000</h2>
            </div>
        </section>
    </main>

</body>
</html>
