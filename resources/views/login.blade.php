<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>Login - Computer Based Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .gradient-bg {
            background: #15803d;
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
    </style>
</head>

<body class="bg-green-50 min-h-screen flex items-center justify-center px-4">

    <div class="relative max-w-5xl w-full bg-white shadow-2xl rounded-3xl flex flex-col lg:flex-row overflow-hidden border border-gray-100">

        <!-- Left Side - Branding -->
        <div class="lg:w-1/2 w-full gradient-bg p-6 sm:p-8 md:p-12 flex flex-col justify-center relative overflow-hidden text-white">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/cbt.png') }}" alt="Logo" class="w-68 md:w-84 mx-auto">
            </div>

            <!-- Bottom Branding -->
            <div class="absolute bottom-4 left-4 right-4">
                <div class="flex flex-wrap gap-3 items-center justify-center text-green-200 text-xs sm:text-sm">
                    <div class="flex items-center space-x-2 glass-effect px-3 py-2 rounded-full">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center">
                            <img src="{{ asset('images/del.png') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="whitespace-normal">Institut Teknologi Del</span>
                    </div>
                    <div class="flex items-center space-x-2 glass-effect px-3 py-2 rounded-full">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center">
                            <img src="{{ asset('images/tut.jpg') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="whitespace-normal">Tut Wuri Handayani</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="lg:w-1/2 w-full p-6 sm:p-8 md:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto px-4 sm:px-6 lg:px-0">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">Masuk</h1>
                    <p class="text-gray-600">Silakan masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Login Form -->
                <form class="space-y-6" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2" for="email">
                            Email Address
                        </label>
                        <div class="relative">
                            <input
                                class="w-full border border-gray-300 rounded-lg py-3 px-4 pr-12 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                id="email" name="identifier" placeholder="Masukkan Email Anda" required type="email" />
                            <i class="fas fa-envelope absolute right-4 top-3.5 text-gray-400"></i>
                        </div>
                        @error('identifier')
                            <span class="alert-danger text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                class="w-full border border-gray-300 rounded-lg py-3 px-4 pr-12 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                id="password" name="password" placeholder="Masukkan Password Anda" required type="password" />
                            <i class="fas fa-lock absolute right-4 top-3.5 text-gray-400"></i>
                        </div>
                        @error('password')
                            <span class="alert-danger text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                        type="submit">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
