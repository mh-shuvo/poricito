<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorials - Poricito</title>
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
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-black">Home</a>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-700">Admin</a>
                        @else
                            <a href="{{ route('contributor.dashboard') }}" class="text-blue-600 hover:text-blue-700">Dashboard</a>
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

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Search & Filter</h2>
            <form method="GET" action="{{ route('memorials.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search by Name</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Person's name..." class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                        <select name="district_id" id="district_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" 
                                    @if(request('district_id') == $district->id) selected @endif>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Thana -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thana</label>
                        <select name="thana_id" id="thana_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select Thana</option>
                        </select>
                    </div>

                    <!-- Union -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Union</label>
                        <select name="union_id" id="union_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select Union</option>
                        </select>
                    </div>

                    <!-- Ward -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                        <select name="ward_id" id="ward_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select Ward</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Search
                    </button>
                    <a href="{{ route('memorials.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="mb-4">
            <p class="text-gray-700">Found <strong>{{ $memorials->total() }}</strong> memorial(s)</p>
        </div>

        <!-- Memorial Grid -->
        @if ($memorials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($memorials as $memorial)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <!-- Image -->
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if ($memorial->photos->count() > 0)
                                <img src="{{ asset('storage/' . $memorial->photos->first()->photo_path) }}" 
                                    alt="{{ $memorial->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-600">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $memorial->name }}</h3>
                            
                            @if ($memorial->date_of_birth || $memorial->date_of_death)
                                <p class="text-sm text-gray-600 mt-1">
                                    @if ($memorial->date_of_birth){{ $memorial->date_of_birth->format('Y-m-d') }}@endif
                                    - 
                                    @if ($memorial->date_of_death){{ $memorial->date_of_death->format('Y-m-d') }}@endif
                                </p>
                            @endif

                            <!-- Location -->
                            <p class="text-sm text-gray-500 mt-2">
                                📍 {{ $memorial->ward->union->thana->district->name }}, 
                                {{ $memorial->ward->union->thana->name }}
                            </p>

                            <!-- View Button -->
                            <a href="{{ route('memorials.show', $memorial) }}" class="mt-4 block w-full text-center py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                View Memorial
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $memorials->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-600 text-lg">No memorials found. Try adjusting your filters.</p>
            </div>
        @endif
    </div>

    <script>
        // Cascading location filters
        const districtSelect = document.getElementById('district_id');
        const thanaSelect = document.getElementById('thana_id');
        const unionSelect = document.getElementById('union_id');
        const wardSelect = document.getElementById('ward_id');

        let allThanas = {};
        let allUnions = {};
        let allWards = {};

        // Save current filter values for restoration after API load
        const currentThanaId = '{{ request("thana_id") }}';
        const currentUnionId = '{{ request("union_id") }}';
        const currentWardId = '{{ request("ward_id") }}';

        // Fetch all location data on page load
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('/api/locations');
                if (!response.ok) {
                    throw new Error('Failed to load location data');
                }
                const locations = await response.json();
                
                console.log('Location data loaded:', locations);
                
                // Build lookup tables
                if (locations.thanas && Array.isArray(locations.thanas)) {
                    locations.thanas.forEach(thana => {
                        if (thana.district_id) {
                            if (!allThanas[thana.district_id]) allThanas[thana.district_id] = [];
                            allThanas[thana.district_id].push(thana);
                        }
                    });
                }

                if (locations.unions && Array.isArray(locations.unions)) {
                    locations.unions.forEach(union => {
                        if (union.thana_id) {
                            if (!allUnions[union.thana_id]) allUnions[union.thana_id] = [];
                            allUnions[union.thana_id].push(union);
                        }
                    });
                }

                if (locations.wards && Array.isArray(locations.wards)) {
                    locations.wards.forEach(ward => {
                        if (ward.union_id) {
                            if (!allWards[ward.union_id]) allWards[ward.union_id] = [];
                            allWards[ward.union_id].push(ward);
                        }
                    });
                }
                
                console.log('Lookup tables built:', { 
                    thanas: Object.keys(allThanas).length, 
                    unions: Object.keys(allUnions).length, 
                    wards: Object.keys(allWards).length 
                });

                // Register event listeners AFTER data is loaded
                districtSelect.addEventListener('change', function() {
                    thanaSelect.innerHTML = '<option value="">Select Thana</option>';
                    unionSelect.innerHTML = '<option value="">Select Union</option>';
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';

                    const districtId = this.value;
                    console.log('District changed to:', districtId);
                    
                    if (districtId && allThanas[districtId]) {
                        console.log('Found', allThanas[districtId].length, 'thanas for district', districtId);
                        allThanas[districtId].forEach(thana => {
                            const option = document.createElement('option');
                            option.value = thana.id;
                            option.textContent = thana.name;
                            thanaSelect.appendChild(option);
                        });
                    }
                });

                thanaSelect.addEventListener('change', function() {
                    unionSelect.innerHTML = '<option value="">Select Union</option>';
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';

                    const thanaId = this.value;
                    console.log('Thana changed to:', thanaId);
                    
                    if (thanaId && allUnions[thanaId]) {
                        console.log('Found', allUnions[thanaId].length, 'unions for thana', thanaId);
                        allUnions[thanaId].forEach(union => {
                            const option = document.createElement('option');
                            option.value = union.id;
                            option.textContent = union.name;
                            unionSelect.appendChild(option);
                        });
                    }
                });

                unionSelect.addEventListener('change', function() {
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';

                    const unionId = this.value;
                    console.log('Union changed to:', unionId);
                    
                    if (unionId && allWards[unionId]) {
                        console.log('Found', allWards[unionId].length, 'wards for union', unionId);
                        allWards[unionId].forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.id;
                            option.textContent = ward.name;
                            wardSelect.appendChild(option);
                        });
                    }
                });

                // Restore filter values if district is selected (for maintaining filters after search)
                if (districtSelect.value) {
                    const districtId = districtSelect.value;
                    
                    // Populate thanas
                    if (allThanas[districtId]) {
                        allThanas[districtId].forEach(thana => {
                            const option = document.createElement('option');
                            option.value = thana.id;
                            option.textContent = thana.name;
                            thanaSelect.appendChild(option);
                        });
                    }
                    
                    // Set current thana value
                    if (currentThanaId) {
                        thanaSelect.value = currentThanaId;
                        
                        // Populate unions
                        if (allUnions[currentThanaId]) {
                            allUnions[currentThanaId].forEach(union => {
                                const option = document.createElement('option');
                                option.value = union.id;
                                option.textContent = union.name;
                                unionSelect.appendChild(option);
                            });
                        }
                        
                        // Set current union value
                        if (currentUnionId) {
                            unionSelect.value = currentUnionId;
                            
                            // Populate wards
                            if (allWards[currentUnionId]) {
                                allWards[currentUnionId].forEach(ward => {
                                    const option = document.createElement('option');
                                    option.value = ward.id;
                                    option.textContent = ward.name;
                                    wardSelect.appendChild(option);
                                });
                            }
                            
                            // Set current ward value
                            if (currentWardId) {
                                wardSelect.value = currentWardId;
                            }
                        }
                    }
                }
                
            } catch (error) {
                console.error('Error loading location data:', error);
                alert('Failed to load location data. Please refresh the page.');
            }
        });
    </script>
</body>
</html>
