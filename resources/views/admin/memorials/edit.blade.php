<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Memorial - Admin</title>
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
                <a href="{{ route('admin.memorials.show', $memorial) }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
                    ← Back to Memorial
                </a>

                <div class="bg-white rounded-lg shadow p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Memorial</h1>

                    <form action="{{ route('admin.memorials.update', $memorial) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $memorial->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $memorial->date_of_birth?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('date_of_birth')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Death</label>
                                <input type="date" name="date_of_death" value="{{ old('date_of_death', $memorial->date_of_death?->format('Y-m-d')) }}"
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
                                        @foreach (\App\Models\District::all() as $district)
                                            <option value="{{ $district->id }}" 
                                                @if($memorial->ward->union->thana->district_id == $district->id) selected @endif>
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
                                        <option value="{{ $memorial->ward->union->thana->id }}" selected>
                                            {{ $memorial->ward->union->thana->name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Union -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Union</label>
                                    <select id="union" name="union" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Union</option>
                                        <option value="{{ $memorial->ward->union->id }}" selected>
                                            {{ $memorial->ward->union->name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Ward -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                                    <select id="ward" name="ward_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Ward</option>
                                        <option value="{{ $memorial->ward->id }}" selected>
                                            {{ $memorial->ward->name }}
                                        </option>
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $memorial->bio) }}</textarea>
                            @error('bio')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Existing Photos -->
                        @if($memorial->photos->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Photos</label>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($memorial->photos as $photo)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="deletePhoto({{ $photo->id }})" 
                                        class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Add More Photos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Add More Photos</label>
                            <p class="text-sm text-gray-600 mb-2">Upload up to 10 photos total (max 5MB each, JPG/PNG)</p>
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
                                Save Changes
                            </button>
                            <a href="{{ route('admin.memorials.show', $memorial) }}" class="flex-1 py-3 px-6 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        const districtSelect = document.getElementById('district');
        const thanaSelect = document.getElementById('thana');
        const unionSelect = document.getElementById('union');
        const wardSelect = document.getElementById('ward');

        let allThanas = {};
        let allUnions = {};
        let allWards = {};

        const currentThanaId = {{ $memorial->ward->union->thana->id }};
        const currentUnionId = {{ $memorial->ward->union->id }};
        const currentWardId = {{ $memorial->ward->id }};

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

                // Trigger initial populate if district is selected (without setTimeout)
                if (districtSelect.value) {
                    // Populate thanas
                    const districtId = districtSelect.value;
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

        // Delete photo
        async function deletePhoto(photoId) {
            if (!confirm('Are you sure you want to delete this photo?')) return;
            
            try {
                const response = await fetch(`/admin/memorials/photos/${photoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Failed to delete photo');
                }
            } catch (error) {
                alert('Error deleting photo');
            }
        }
    </script>
</body>
</html>
