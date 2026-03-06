<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contributor - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Poricito Admin</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <nav class="w-64 bg-gray-800 text-white shadow-lg">
                <div class="p-6">
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700">
                                📊 Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.memorials.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700">
                                🏛️ Memorials
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.contributors.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 bg-gray-700">
                                👥 Contributors
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                <a href="{{ route('admin.contributors.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
                    ← Back to Contributors
                </a>

                <div class="bg-white rounded-lg shadow p-8 max-w-md">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Add New Contributor</h1>

                    <form action="{{ route('admin.contributors.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 py-3 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Create Contributor
                            </button>
                            <a href="{{ route('admin.contributors.index') }}" class="flex-1 py-3 px-6 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium text-center">
                                Cancel
                            </a>
                        </div>

                        <p class="text-sm text-gray-600 text-center">
                            Note: A random password will be generated and the contributor should change it on first login.
                        </p>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
