<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Lab Exam</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-purple-50 via-blue-100 to-purple-200 min-h-screen flex items-center justify-center font-sans text-gray-800">

    <div class="w-full max-w-3xl p-8 bg-white border border-gray-200 rounded-3xl shadow-xl text-center">
        <h1 class="text-4xl font-extrabold mb-4 text-purple-700">Welcome to My Booking System</h1>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="bg-purple-600 text-white font-semibold py-3 px-6 rounded-full shadow hover:bg-purple-700 transition-all duration-300">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="bg-indigo-500 text-white font-semibold py-3 px-6 rounded-full shadow hover:bg-indigo-600 transition-all duration-300">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="bg-white border-2 border-indigo-300 text-indigo-600 font-semibold py-3 px-6 rounded-full hover:bg-indigo-50 transition-all duration-300">
                    Register
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
