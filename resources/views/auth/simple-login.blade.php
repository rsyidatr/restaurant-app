<!DOCTYPE html>
<html>
<head>
    <title>Simple Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Simple Login Test</h1>
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="/simple-login">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Login
                </button>
            </form>
            
            <div class="mt-4 text-sm">
                <p>Test Accounts:</p>
                <ul class="list-disc ml-5">
                    <li>admin@restaurant.com / password</li>
                    <li>customer@test.com / password</li>
                    <li>pelayan@restaurant.com / password</li>
                    <li>koki@restaurant.com / password</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
