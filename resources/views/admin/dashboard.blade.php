<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Poricito</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-3">
                        {!! logoTransparent('Poricito', ['class' => 'w-10 h-10']) !!}
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Poricito Admin</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 cursor-pointer">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar & Main -->
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
                            <a href="{{ route('admin.contributors.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700">
                                👥 Contributors
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                @if (session('success'))
                    <div class="rounded-lg bg-green-50 border border-green-200 p-4 mb-6">
                        <p class="text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-6 mb-8">
                    <!-- Pending Memorials Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Memorials</h3>
                        <p class="text-4xl font-bold text-blue-600 mt-4">{{ $pendingCount }}</p>
                        <a href="{{ route('admin.memorials.index', ['status' => 'pending']) }}" class="mt-4 text-blue-600 hover:text-blue-700">
                            View Pending →
                        </a>
                    </div>

                    <!-- Approved Memorials Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Approved Memorials</h3>
                        <p class="text-4xl font-bold text-green-600 mt-4">{{ $approvedCount }}</p>
                        <a href="{{ route('admin.memorials.index', ['status' => 'approved']) }}" class="mt-4 text-green-600 hover:text-green-700">
                            View Approved →
                        </a>
                    </div>

                    <!-- Contributors Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Contributors</h3>
                        <p class="text-4xl font-bold text-purple-600 mt-4">{{ $contributorCount }}</p>
                        <a href="{{ route('admin.contributors.index') }}" class="mt-4 text-purple-600 hover:text-purple-700">
                            View Contributors →
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('admin.contributors.create') }}" class="block py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Add New Contributor
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
