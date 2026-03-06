<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $memorial->name }} - Admin Review</title>
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

                <!-- Back Link -->
                <a href="{{ route('admin.memorials.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
                    ← Back to Memorials
                </a>

                <div class="grid grid-cols-3 gap-6">
                    <!-- Left Column - Memorial Info -->
                    <div class="col-span-2">
                        <!-- Photos -->
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Photos</h2>
                            @if ($memorial->photos->count() > 0)
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
                            @else
                                <p class="text-gray-600">No photos uploaded.</p>
                            @endif
                        </div>

                        <!-- Memorial Details -->
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Details</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <p class="text-lg text-gray-900">{{ $memorial->name }}</p>
                                </div>

                                @if ($memorial->date_of_birth || $memorial->date_of_death)
                                    <div class="grid grid-cols-2 gap-4">
                                        @if ($memorial->date_of_birth)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                                <p class="text-gray-900">{{ $memorial->date_of_birth->format('F j, Y') }}</p>
                                            </div>
                                        @endif
                                        @if ($memorial->date_of_death)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Date of Death</label>
                                                <p class="text-gray-900">{{ $memorial->date_of_death->format('F j, Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Location</label>
                                    <p class="text-gray-900">
                                        {{ $memorial->ward->name }}, 
                                        {{ $memorial->ward->union->name }}, 
                                        {{ $memorial->ward->union->thana->name }}, 
                                        {{ $memorial->ward->union->thana->district->name }}
                                    </p>
                                </div>

                                @if ($memorial->bio)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Biography</label>
                                        <div class="bg-gray-50 p-4 rounded text-gray-900 whitespace-pre-wrap">
                                            {{ $memorial->bio }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        @if ($memorial->admin_notes)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-red-900 mb-2">Admin Notes (Rejection Reason)</h3>
                                <p class="text-red-800 whitespace-pre-wrap">{{ $memorial->admin_notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Actions -->
                    <div>
                        <!-- Status Card -->
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                            
                            @if ($memorial->status === 'pending')
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium mb-4 inline-block">
                                    Pending Review
                                </span>
                            @elseif ($memorial->status === 'approved')
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium mb-4 inline-block">
                                    Approved
                                </span>
                            @else
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium mb-4 inline-block">
                                    Rejected
                                </span>
                            @endif

                            <div class="mt-4 space-y-3">
                                <p class="text-sm text-gray-600">
                                    <strong>Submitted by:</strong> {{ $memorial->user->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Created:</strong> {{ $memorial->created_at->format('M d, Y H:i') }}
                                </p>
                                @if ($memorial->updated_by_admin_id)
                                    <p class="text-sm text-gray-600">
                                        <strong>Reviewed by:</strong> {{ $memorial->updatedByAdmin->name }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

                            <!-- Edit Button -->
                            <a href="{{ route('admin.memorials.edit', $memorial) }}" 
                                class="block w-full text-center py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 mb-3">
                                Edit Memorial
                            </a>

                            <!-- Approve Button (if not approved) -->
                            @if ($memorial->status !== 'approved')
                                <form action="{{ route('admin.memorials.approve', $memorial) }}" method="POST" class="mb-3">
                                    @csrf
                                    <button type="submit" class="w-full py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700">
                                        ✓ Approve
                                    </button>
                                </form>
                            @endif

                            <!-- Reject Button (if not rejected) -->
                            @if ($memorial->status !== 'rejected')
                                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                                    class="w-full py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700 mb-3">
                                    ✗ Reject
                                </button>
                            @endif

                            <!-- Delete Button -->
                            <form action="{{ route('admin.memorials.destroy', $memorial) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this memorial?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 px-4 bg-gray-600 text-white rounded hover:bg-gray-700">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Memorial</h3>
            <p class="text-gray-600 mb-4">Provide feedback for the contributor to revise their submission.</p>

            <form action="{{ route('admin.memorials.reject', $memorial) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Notes</label>
                    <textarea name="admin_notes" rows="5" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Explain what needs to be revised..."></textarea>
                    @error('admin_notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="flex-1 py-2 px-4 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
