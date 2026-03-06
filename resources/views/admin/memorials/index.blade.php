<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorials Management - Admin</title>
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
                            <a href="{{ route('admin.memorials.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 bg-gray-700">
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

                <h1 class="text-3xl font-bold text-gray-900 mb-6">Memorials</h1>

                <!-- Filter Tabs -->
                <div class="bg-white rounded-lg shadow mb-6 p-4">
                    <div class="flex gap-4 border-b">
                        <a href="{{ route('admin.memorials.index') }}" 
                            class="pb-2 px-4 border-b-2 @if(!request('status')) border-blue-600 text-blue-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                            All ({{ \App\Models\Memorial::count() }})
                        </a>
                        <a href="{{ route('admin.memorials.index', ['status' => 'pending']) }}" 
                            class="pb-2 px-4 border-b-2 @if(request('status') == 'pending') border-yellow-600 text-yellow-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                            Pending ({{ \App\Models\Memorial::where('status', 'pending')->count() }})
                        </a>
                        <a href="{{ route('admin.memorials.index', ['status' => 'approved']) }}" 
                            class="pb-2 px-4 border-b-2 @if(request('status') == 'approved') border-green-600 text-green-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                            Approved ({{ \App\Models\Memorial::where('status', 'approved')->count() }})
                        </a>
                        <a href="{{ route('admin.memorials.index', ['status' => 'rejected']) }}" 
                            class="pb-2 px-4 border-b-2 @if(request('status') == 'rejected') border-red-600 text-red-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                            Rejected ({{ \App\Models\Memorial::where('status', 'rejected')->count() }})
                        </a>
                    </div>
                </div>

                <!-- Memorials Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Contributor</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Location</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Created</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($memorials as $memorial)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $memorial->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $memorial->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $memorial->ward->union->thana->district->name }}, 
                                        {{ $memorial->ward->union->thana->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($memorial->status === 'pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                                        @elseif ($memorial->status === 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Approved</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $memorial->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        <a href="{{ route('admin.memorials.show', $memorial) }}" class="text-blue-600 hover:text-blue-700">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No memorials found.
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
