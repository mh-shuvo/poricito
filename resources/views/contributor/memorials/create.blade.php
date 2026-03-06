<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Memorial - Poricito</title>
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
                <a href="{{ route('contributor.memorials.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
                    ← Back to My Memorials
                </a>

                <div class="bg-white rounded-lg shadow p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Memorial</h1>

                    <form action="{{ route('contributor.memorials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Person's full name">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('date_of_birth')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Death</label>
                                <input type="date" name="date_of_death" value="{{ old('date_of_death') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('date_of_death')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Selection -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-4">Location *</h3>
                            <div class="grid grid-cols-4 gap-4">
                                <!-- District -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                    <select id="district" name="district" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select District</option>
                                        @foreach (\App\Models\District::with('thanas.unions.wards')->get() as $district)
                                            <option value="{{ $district->id }}" data-thanas="{{ json_encode($district->thanas) }}">
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Thana -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Thana</label>
                                    <select id="thana" name="thana" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Thana</option>
                                    </select>
                                </div>

                                <!-- Union -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Union</label>
                                    <select id="union" name="union" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Union</option>
                                    </select>
                                </div>

                                <!-- Ward -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                                    <select id="ward" name="ward_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Ward</option>
                                    </select>
                                </div>
                            </div>
                            @error('ward_id')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Biography -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                            <textarea name="bio" rows="6" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Write about the person's life, achievements, and memories...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Photos</label>
                            <p class="text-sm text-gray-600 mb-2">Upload up to 10 photos (max 5MB each, JPG/PNG)</p>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('photos.*')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div id="photo-preview" class="mt-4 grid grid-cols-3 gap-4"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 py-3 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Create Memorial
                            </button>
                            <a href="{{ route('contributor.memorials.index') }}" class="flex-1 py-3 px-6 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium text-center">
                                Cancel
                            </a>
                        </div>

                        <p class="text-sm text-gray-600 text-center">
                            Note: Your memorial will be submitted for admin approval before appearing on the website.
                        </p>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Cascading location selects
        const districtSelect = document.getElementById('district');
        const thanaSelect = document.getElementById('thana');
        const unionSelect = document.getElementById('union');
        const wardSelect = document.getElementById('ward');

        let allThanas = {};
        let allUnions = {};
        let allWards = {};

        // Fetch all data on page load
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
                
                console.log('Lookup tables built:', { thanas: Object.keys(allThanas).length, unions: Object.keys(allUnions).length, wards: Object.keys(allWards).length });

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
                
            } catch (error) {
                console.error('Error loading location data:', error);
                alert('Failed to load location data. Please refresh the page.');
            }
        });

        // Photo preview
        document.getElementById('photos').addEventListener('change', function(e) {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = '';
            const files = Array.from(e.target.files).slice(0, 10);
            
            files.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`${file.name} is larger than 5MB`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>
</html>
