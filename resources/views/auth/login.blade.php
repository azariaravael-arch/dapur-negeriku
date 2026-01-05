<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dapur Negriku</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-white to-lime-950 relative p-4">

    <!-- FRAME LUAR -->
    <div class="absolute inset-2 sm:inset-4 md:inset-5 border border-white/40 rounded-3xl pointer-events-none"></div>

    <!-- CARD LOGIN -->
    <div
        class="w-full max-w-sm sm:max-w-md bg-white rounded-3xl shadow-xl px-5 sm:px-8 md:px-10 py-8 sm:py-10 md:py-12 relative z-10">

        <!-- LOGO -->
        <div class="text-center mb-10">
            <img src="/images/LogoDapurNegeriku.png" alt="Logo" class="mx-auto h-24 sm:h-28 md:h-32">
        </div>

        <!-- ALERT ERROR (PASTI MUNCUL) -->
        @if($errors->any())
        <div class="mb-6 p-4 border border-red-300 bg-red-50 text-red-700
            rounded-xl flex items-start gap-3 shadow-sm
            text-xs sm:text-sm md:text-base animate-fadeIn">

            <!-- ICON -->
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 sm:h-7 sm:w-7 md:h-8 md:w-8 flex-shrink-0 text-red-600"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856
                    c1.54 0 2.502-1.667 1.732-3L13.732 4
                    c-.77-1.333-2.694-1.333-3.464 0L4.21 16
                    c-.77 1.333.192 3 1.732 3z" />
            </svg>

            <!-- TEXT -->
            <div class="leading-snug">
                <p class="font-semibold text-red-700 text-sm sm:text-base">
                    Login gagal
                </p>
                <p class="text-[10px] sm:text-xs md:text-sm">
                    {{ $errors->first() }}
                </p>
            </div>
        </div>
        @endif

        <!-- TEKS PETUNJUK -->
        <p class="text-center text-gray-600 mb-6 text-xs sm:text-sm md:text-base">
            Masukkan email dan kata sandi
        </p>

        <!-- FORM LOGIN -->
        <form action="{{ route('login.show') }}" method="POST" class="space-y-6">
            @csrf

            <!-- EMAIL -->
            <div class="space-y-2">
                <label class="block text-gray-700 font-medium text-xs sm:text-sm md:text-base">
                    Email
                </label>
                <div
                    class="flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-xl focus-within:ring-2 focus-within:ring-lime-700 shadow-sm transition
                    @error('email_pengguna') border-red-400 @enderror">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-lime-900" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input type="email" name="email_pengguna" value="{{ old('email_pengguna') }}"
                        class="w-full bg-transparent px-3 py-2 focus:outline-none text-gray-700 placeholder-gray-400 text-xs sm:text-sm md:text-base"
                        placeholder="email@gmail.com" required>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="space-y-2">
                <label class="block text-gray-700 font-medium text-xs sm:text-sm md:text-base">
                    Kata Sandi
                </label>
                <div
                    class="relative flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-xl focus-within:ring-2 focus-within:ring-lime-700 shadow-sm transition
                    @error('password_admin') border-red-400 @enderror">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-lime-900" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0v4" />
                    </svg>

                    <input id="passwordField" type="password" name="password_admin"
                        class="w-full bg-transparent px-3 py-2 focus:outline-none text-gray-700 placeholder-gray-400 text-xs sm:text-sm md:text-base"
                        placeholder="••••••••" required>

                    <!-- TOGGLE -->
                    <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 absolute right-3 cursor-pointer text-gray-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5
                               c4.477 0 8.268 2.943 9.542 7
                               -1.274 4.057-5.065 7-9.542 7
                               -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>

            <!-- REMEMBER -->
            <label class="flex items-center gap-2 text-gray-700 text-xs sm:text-sm select-none">
                <input type="checkbox" class="h-4 w-4 text-lime-700 rounded border-gray-300">
                Ingat saya
            </label>

            <!-- BUTTON -->
            <button
                class="w-full bg-lime-800 hover:bg-lime-700 active:scale-[0.98] transition-all
                text-white py-2.5 rounded-xl font-semibold text-xs sm:text-sm md:text-base shadow">
                Masuk
            </button>
        </form>
    </div>

    <!-- FOOTER -->
    <div class="absolute bottom-3 sm:bottom-5 text-white/80 text-[10px] sm:text-xs md:text-sm text-center w-full px-2">
        © 2025 <span class="font-semibold bg-blue-500 bg-clip-text text-transparent">coding.site</span>
        — Created for PT. Berkat Untuk Sesama.
    </div>

    <!-- TOGGLE PASSWORD SCRIPT -->
    <script>
        const passwordField = document.getElementById("passwordField");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {
            passwordField.type =
                passwordField.type === "password" ? "text" : "password";

            this.classList.toggle("text-gray-600");
            this.classList.toggle("text-lime-700");
        });
    </script>

</body>

</html>
