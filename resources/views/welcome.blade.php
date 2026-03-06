<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poricito - Memorial Archive</title>
    {!! faviconLinks() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <!-- Header -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    {!! logoTransparent('Poricito', ['class' => 'w-10 h-10']) !!}
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Poricito</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('memorials.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Browse Memorials</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
                                Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('contributor.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
                                My Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:border-blue-600 hover:text-blue-600 transition-all">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                Remember & Honor<br/>
                <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Those We've Known
                </span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                A digital memorial archive preserving memories and celebrating the lives of our loved ones across Bangladesh.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('memorials.index') }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-xl transform hover:-translate-y-1 transition-all font-medium">
                    Explore Memorials
                </a>
                @guest
                <a href="{{ route('login') }}" class="px-8 py-3 border-2 border-gray-300 rounded-lg hover:border-blue-600 hover:text-blue-600 transition-all font-medium">
                    Get Started
                </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 shadow-lg text-center">
                    <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                        {{ \App\Models\Memorial::where('status', 'approved')->count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Memorials</div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 shadow-lg text-center">
                    <div class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                        {{ \App\Models\User::where('role', 'contributor')->count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Contributors</div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 shadow-lg text-center">
                    <div class="text-4xl font-bold bg-gradient-to-r from-pink-600 to-blue-600 bg-clip-text text-transparent mb-2">
                        {{ \App\Models\District::count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Districts</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Why Poricito?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Photo Galleries</h3>
                    <p class="text-gray-600">Upload multiple photos to create beautiful memorial galleries.</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Location-Based</h3>
                    <p class="text-gray-600">Organized by Districts, Thanas, Unions, and Wards of Bangladesh.</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Quality Reviewed</h3>
                    <p class="text-gray-600">All memorials are reviewed by admins before publication.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    @guest
    <section class="py-20 px-4">
        <div class="max-w-4xl mx-auto text-center bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-12 shadow-2xl">
            <h2 class="text-3xl font-bold text-white mb-4">Join Our Community</h2>
            <p class="text-white/90 text-lg mb-8">Start preserving memories and celebrating lives today.</p>
            <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-blue-600 rounded-lg hover:shadow-xl transform hover:-translate-y-1 transition-all font-medium inline-block">
                Login to Begin
            </a>
        </div>
    </section>
    @endguest

    <!-- Footer -->
    <footer class="py-8 px-4 bg-white/50 backdrop-blur-sm mt-20">
        <div class="max-w-6xl mx-auto text-center text-gray-600">
            <p>&copy; {{ date('Y') }} Poricito. Preserving memories with love.</p>
        </div>
    </footer>
</body>
</html>