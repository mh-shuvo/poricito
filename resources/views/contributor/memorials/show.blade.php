<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $memorial->name }} - My Memorial</title>
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
                            <a href="{{ route('contributor.memorials.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700">
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
                <a href="{{ route('contributor.memorials.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
                    ← Back to My Memorials
                </a>

                <div class="grid grid-cols-3 gap-6">
                    <!-- Left - Memorial Info -->
                    <div class="col-span-2">
                        <!-- Title & Status -->
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $memorial->name }}</h1>
                                    @if ($memorial->date_of_birth || $memorial->date_of_death)
                                        <p class="text-gray-600 mt-2">
                                            @if ($memorial->date_of_birth){{ $memorial->date_of_birth->format('F j, Y') }}@endif
                                            - 
                                            @if ($memorial->date_of_death){{ $memorial->date_of_death->format('F j, Y') }}@endif
                                        </p>
                                    @endif
                                </div>
                                @if ($memorial->status === 'pending')
                                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium">
                                        ⏳ Pending Approval
                                    </span>
                                @elseif ($memorial->status === 'approved')
                                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">
                                        ✓ Approved
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium">
                                        ✗ Rejected
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Rejection Notes -->
                        @if ($memorial->admin_notes)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-red-900 mb-2">Admin Feedback</h3>
                                <p class="text-red-800 whitespace-pre-wrap mb-4">{{ $memorial->admin_notes }}</p>
                                <p class="text-sm text-red-700">
                                    Please revise your memorial according to this feedback and resubmit.
                                </p>
                            </div>
                        @endif

                        <!-- Location -->
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Location</h2>
                            <p class="text-gray-900">
                                <strong>Ward:</strong> {{ $memorial->ward->name }}<br>
                                <strong>Union:</strong> {{ $memorial->ward->union->name }}<br>
                                <strong>Thana:</strong> {{ $memorial->ward->union->thana->name }}<br>
                                <strong>District:</strong> {{ $memorial->ward->union->thana->district->name }}
                            </p>
                        </div>

                        <!-- Biography -->
                        @if ($memorial->bio)
                            <div class="bg-white rounded-lg shadow p-6 mb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Biography</h2>
                                <div class="whitespace-pre-wrap text-gray-900">{{ $memorial->bio }}</div>
                            </div>
                        @endif

                        <!-- Photos -->
                        @if ($memorial->photos->count() > 0)
                            <div class="bg-white rounded-lg shadow p-6 mb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Photos</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($memorial->photos as $photo)
                                        <div class="rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                alt="{{ $photo->caption }}" 
                                                class="w-full h-40 object-cover">
                                            @if ($photo->caption)
                                                <p class="p-2 bg-gray-50 text-sm text-gray-700">{{ $photo->caption }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right - Actions -->
                    <div>
                        <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                            
                            <div class="space-y-3">
                                @if ($memorial->status !== 'approved')
                                    <a href="{{ route('contributor.memorials.edit', $memorial) }}" 
                                        class="block w-full text-center py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        ✎ Edit Memorial
                                    </a>
                                @endif

                                <form action="{{ route('contributor.memorials.destroy', $memorial) }}" method="POST"
                                    onsubmit="return confirm('Are you sure? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>

                            @if ($memorial->status === 'approved')
                                <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-green-800 text-sm font-medium">
                                        ✓ This memorial is published on the website!
                                    </p>
                                    <a href="{{ route('memorials.show', $memorial) }}" class="text-green-600 hover:text-green-700 text-sm mt-2 inline-block">
                                        View public page →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
