<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contributor->name }} - Admin</title>
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

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $contributor->name }}</h1>
                        <p class="text-gray-600">{{ $contributor->email }}</p>
                        <p class="text-sm text-gray-500 mt-2">Joined {{ $contributor->created_at->format('F j, Y') }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700">Total Memorials</p>
                            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $memorials->total() }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700">Approved</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">
                                {{ $memorials->where('status', 'approved')->count() }}
                            </p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700">Pending</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-1">
                                {{ $memorials->where('status', 'pending')->count() }}
                            </p>
                        </div>
                    </div>

                    <!-- Memorials Table -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Memorials</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900">Name</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900">Status</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900">Created</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($memorials as $memorial)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $memorial->name }}</td>
                                        <td class="px-4 py-3">
                                            @if ($memorial->status === 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Pending</span>
                                            @elseif ($memorial->status === 'approved')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Approved</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $memorial->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.memorials.show', $memorial) }}" class="text-blue-600 hover:text-blue-700">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                            No memorials yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($memorials->count() > 0)
                        <div class="mt-6">
                            {{ $memorials->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
