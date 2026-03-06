<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Memorials - Poricito</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Poricito Contributor</h1>
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
                            <a href="{{ route('contributor.dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700">
                                📊 Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contributor.memorials.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 bg-gray-700">
                                🏛️ My Memorials
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('memorials.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700">
                                👁️ View Website
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

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">My Memorials</h1>
                    <a href="{{ route('contributor.memorials.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        + Add New Memorial
                    </a>
                </div>

                <!-- Memorials Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Created</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($memorials as $memorial)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $memorial->name }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($memorial->status === 'pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                                ⏳ Pending
                                            </span>
                                        @elseif ($memorial->status === 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                ✓ Approved
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                ✗ Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $memorial->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right space-x-3">
                                        <a href="{{ route('contributor.memorials.show', $memorial) }}" class="text-blue-600 hover:text-blue-700">
                                            View
                                        </a>
                                        @if ($memorial->status !== 'approved')
                                            <a href="{{ route('contributor.memorials.edit', $memorial) }}" class="text-blue-600 hover:text-blue-700">
                                                Edit
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        <p class="mb-4">You haven't created any memorials yet.</p>
                                        <a href="{{ route('contributor.memorials.create') }}" class="text-blue-600 hover:text-blue-700">
                                            Create your first memorial →
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($memorials->count() > 0)
                    <div class="mt-8">
                        {{ $memorials->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
