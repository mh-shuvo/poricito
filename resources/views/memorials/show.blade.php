<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $memorial->name }} - Poricito</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    {!! logoTransparent('Poricito', ['class' => 'w-10 h-10']) !!}
                    <h1 class="text-2xl font-bold text-gray-900">Poricito</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('memorials.index') }}" class="text-gray-700 hover:text-black">← Back</a>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.memorials.edit', $memorial) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Gallery -->
            <div class="relative bg-gray-900">
                @if ($memorial->photos->count() > 0)
                    <div class="max-h-96 overflow-hidden flex items-center justify-center">
                        <img id="mainImage" 
                            src="{{ asset('storage/' . $memorial->photos->first()->photo_path) }}" 
                            alt="{{ $memorial->name }}" 
                            class="w-full h-96 object-cover">
                    </div>

                    <!-- Thumbnail Navigation -->
                    @if ($memorial->photos->count() > 1)
                        <div class="bg-gray-800 p-4 flex overflow-x-auto gap-2">
                            @foreach ($memorial->photos as $photo)
                                <button onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $photo->photo_path) }}'"
                                    class="flex-shrink-0 h-20 w-20 rounded border-2 border-gray-700 hover:border-blue-500 overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="h-96 flex items-center justify-center bg-gray-300 text-gray-600">
                        No photos available
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Title & Dates -->
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $memorial->name }}</h1>
                
                @if ($memorial->date_of_birth || $memorial->date_of_death)
                    <div class="flex items-center gap-4 text-lg text-gray-700 mb-6">
                        @if ($memorial->date_of_birth)
                            <span>
                                <strong>Born:</strong> {{ $memorial->date_of_birth->format('F j, Y') }}
                            </span>
                        @endif
                        @if ($memorial->date_of_death)
                            <span>
                                <strong>Passed:</strong> {{ $memorial->date_of_death->format('F j, Y') }}
                            </span>
                        @endif
                    </div>
                @endif

                <!-- Location -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="font-semibold text-gray-900 mb-2">📍 Location</h3>
                    <p class="text-gray-700">
                        <strong>Ward:</strong> {{ $memorial->ward->name }}<br>
                        <strong>Union:</strong> {{ $memorial->ward->union->name }}<br>
                        <strong>Thana:</strong> {{ $memorial->ward->union->thana->name }}<br>
                        <strong>District:</strong> {{ $memorial->ward->union->thana->district->name }}
                    </p>
                </div>

                <!-- Biography -->
                @if ($memorial->bio)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Biography</h2>
                        <div class="prose prose-lg max-w-none">
                            {!! nl2br(e($memorial->bio)) !!}
                        </div>
                    </div>
                @endif

                <!-- Photo Gallery with Captions -->
                @if ($memorial->photos->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Photo Gallery</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($memorial->photos as $photo)
                                <div class="rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                        alt="{{ $photo->caption ?? $memorial->name }}" 
                                        class="w-full h-64 object-cover">
                                    @if ($photo->caption)
                                        <p class="p-3 bg-gray-50 text-sm text-gray-700">{{ $photo->caption }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="mt-12 pt-8 border-t border-gray-200 text-sm text-gray-600">
                    <p>Submitted by: <strong>{{ $memorial->user->name }}</strong></p>
                    <p>Added on: <strong>{{ $memorial->created_at->format('F j, Y') }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('memorials.index') }}" class="inline-block px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                ← Back to Memorials
            </a>
        </div>
    </div>
</body>
</html>
