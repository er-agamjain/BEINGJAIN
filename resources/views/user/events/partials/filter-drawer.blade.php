<div id="filter-drawer" class="fixed inset-0 z-50 hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
  <!-- Background backdrop -->
  <div class="fixed inset-0 bg-gray-900/75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="toggleFilterDrawer()"></div>

  <div class="fixed inset-0 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="pointer-events-auto w-screen max-w-md">
          <div class="flex h-full flex-col overflow-y-scroll bg-slate-900 shadow-xl border-l border-white/10">
            <div class="px-4 py-6 sm:px-6 border-b border-white/10">
              <div class="flex items-start justify-between">
                <h2 class="text-xl font-semibold leading-6 text-white" id="slide-over-title">Filters</h2>
                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" class="relative rounded-md bg-white/10 border border-white/20 px-2 py-1 text-gray-300 hover:bg-white/20 hover:text-white focus:outline-none transition" onclick="toggleFilterDrawer()">
                    <span class="absolute -inset-2.5"></span>
                    <span class="sr-only">Close panel</span>
                    <i class="fas fa-times text-xl"></i>
                  </button>
                </div>
              </div>
            </div>
            
            <form action="{{ route('user.events.index') }}" method="GET" class="flex flex-col flex-1">
                <!-- Keep search if exists -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="relative mt-6 flex-1 px-4 sm:px-6 space-y-8">
                    
                    <!-- Location -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i> Location
                        </h3>
                        <div class="space-y-3">
                            
                            @if(isset($featuredCities) && $featuredCities->isNotEmpty())
                                <div class="grid grid-cols-4 gap-2 mb-2">
                                    @foreach($featuredCities as $city)
                                        <div onclick="selectCity('{{ $city->name }}')" 
                                             class="featured-city-item cursor-pointer group flex flex-col items-center {{ request('city') == $city->name ? 'selected' : '' }}" 
                                             data-city="{{ $city->name }}">
                                            <div class="city-ring w-12 h-12 rounded-full overflow-hidden border-2 {{ request('city') == $city->name ? 'border-amber-400' : 'border-transparent' }} group-hover:border-amber-400 transition-all mb-1 relative">
                                                <img src="{{ $city->image }}" alt="{{ $city->name }}" class="w-full h-full object-cover">
                                                 <!-- Selection indicator overlay -->
                                                 <div class="selection-indicator absolute inset-0 bg-amber-400/50 {{ request('city') == $city->name ? 'flex' : 'hidden' }} items-center justify-center">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                 </div>
                                            </div>
                                            <span class="text-[10px] text-slate-300 group-hover:text-white text-center leading-tight">{{ $city->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button type="button" onclick="detectLocation()" class="w-full flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-emerald-400 py-2 rounded-lg transition text-sm">
                                <i class="fas fa-crosshairs"></i> Auto Detect My Location
                            </button>
                            <div class="relative">
                                <select name="city" id="citySelect" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent appearance-none">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->name }}" {{ request('city') == $city->name ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-calendar"></i> Date
                        </h3>
                        <div class="space-y-2">
                            @php
                                $dateOptions = [
                                    'any' => 'Any Date',
                                    'today' => 'Today',
                                    'tomorrow' => 'Tomorrow',
                                    'this_week' => 'This Week',
                                    'this_weekend' => 'This Weekend',
                                    'custom' => 'Custom Range'
                                ];
                            @endphp
                            @foreach($dateOptions as $value => $label)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="date_filter" value="{{ $value }}" 
                                        {{ request('date_filter', 'any') == $value ? 'checked' : '' }}
                                        onchange="toggleCustomDate(this.value)"
                                        class="form-radio h-4 w-4 text-purple-600 border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                    <span class="text-slate-300 group-hover:text-white transition">{{ $label }}</span>
                                </label>
                            @endforeach
                            
                            <!-- Custom Date Range -->
                            <div id="custom-date-range" class="{{ request('date_filter') == 'custom' ? 'block' : 'hidden' }} pl-7 pt-2 space-y-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-800 border border-slate-700 text-white rounded px-3 py-1 text-sm">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-800 border border-slate-700 text-white rounded px-3 py-1 text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Event Category -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-tags"></i> Category
                        </h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center space-x-3 cursor-pointer group border-b border-white/10 pb-2 mb-2">
                                <input type="checkbox" id="all-categories" data-group="categories" onchange="toggleAllFilters('categories')"
                                    {{ empty(request('categories', [])) || count(request('categories', [])) == count($categories) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 text-purple-600 rounded border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                <span class="text-white font-medium">Default All</span>
                            </label>
                            @foreach($categories as $id => $name)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $id }}" data-item-group="categories" onchange="syncAllFilterOption('categories')"
                                        {{ empty(request('categories', [])) || in_array($id, request('categories', [])) ? 'checked' : '' }}
                                        class="form-checkbox h-4 w-4 text-purple-600 rounded border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                    <span class="text-slate-300 group-hover:text-white transition">{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Community -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-users"></i> Community
                        </h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center space-x-3 cursor-pointer group border-b border-white/10 pb-2 mb-2">
                                <input type="checkbox" id="all-communities" data-group="communities" onchange="toggleAllFilters('communities')"
                                    {{ empty(request('communities', [])) || count(request('communities', [])) == count($communities) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 text-purple-600 rounded border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                <span class="text-white font-medium">Default All</span>
                            </label>
                            @forelse($communities as $community)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="communities[]" value="{{ $community }}" data-item-group="communities" onchange="syncAllFilterOption('communities')"
                                        {{ empty(request('communities', [])) || in_array($community, request('communities', [])) ? 'checked' : '' }}
                                        class="form-checkbox h-4 w-4 text-purple-600 rounded border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                    <span class="text-slate-300 group-hover:text-white transition">{{ $community }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-slate-500 italic">No communities found</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Gacchh -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-place-of-worship"></i> Gacchh
                        </h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center space-x-3 cursor-pointer group border-b border-white/10 pb-2 mb-2">
                                <input type="checkbox" id="all-gacchhs" data-group="gacchhs" onchange="toggleAllFilters('gacchhs')"
                                    {{ empty(request('gacchhs', [])) || count(request('gacchhs', [])) == count($gacchhs) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 text-purple-600 rounded border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                <span class="text-white font-medium">Default All</span>
                            </label>
                            @forelse($gacchhs as $gacchh)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="gacchhs[]" value="{{ $gacchh }}" data-item-group="gacchhs" onchange="syncAllFilterOption('gacchhs')"
                                        {{ empty(request('gacchhs', [])) || in_array($gacchh, request('gacchhs', [])) ? 'checked' : '' }}
                                        class="form-checkbox h-4 w-4 text-purple-600 rounded border-gray-600 bg-slate-800 focus:ring-purple-500 focus:ring-offset-slate-900">
                                    <span class="text-slate-300 group-hover:text-white transition">{{ $gacchh }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-slate-500 italic">No gacchhs found</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Event Type -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-globe"></i> Event Type
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-slate-300 group-hover:text-white transition">Online</span>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="event_type" value="online" onchange="toggleEventType('online')" {{ request('event_type') == 'online' ? 'checked' : '' }} class="event-type-checkbox toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                    <label class="toggle-label block overflow-hidden h-5 rounded-full bg-slate-700 cursor-pointer"></label>
                                </div>
                            </label>
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-slate-300 group-hover:text-white transition">Offline</span>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="event_type" value="offline" onchange="toggleEventType('offline')" {{ request('event_type') == 'offline' ? 'checked' : '' }} class="event-type-checkbox toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                    <label class="toggle-label block overflow-hidden h-5 rounded-full bg-slate-700 cursor-pointer"></label>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Booking Type -->
                    <div>
                        <h3 class="text-amber-400 font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-ticket-alt"></i> Booking Type
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-slate-300 group-hover:text-white transition">Free Events</span>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="booking_type" value="free" onchange="toggleBookingType('free')" {{ request('booking_type') == 'free' ? 'checked' : '' }} class="booking-type-checkbox toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                    <label class="toggle-label block overflow-hidden h-5 rounded-full bg-slate-700 cursor-pointer"></label>
                                </div>
                            </label>
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-slate-300 group-hover:text-white transition">Paid Events</span>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="booking_type" value="paid" onchange="toggleBookingType('paid')" {{ request('booking_type') == 'paid' ? 'checked' : '' }} class="booking-type-checkbox toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                    <label class="toggle-label block overflow-hidden h-5 rounded-full bg-slate-700 cursor-pointer"></label>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="border-t border-white/10 px-4 py-6 sm:px-6">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 rounded-md bg-gradient-to-r from-purple-600 to-indigo-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:from-purple-500 hover:to-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('user.events.index') }}" class="mt-3 w-full flex justify-center items-center gap-2 rounded-md bg-white/5 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-white/10 ring-1 ring-inset ring-white/10 transition-all">
                        Reset All
                    </a>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Toggle Switch */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #68D391;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #68D391;
    }
    .toggle-checkbox {
        right: 0;
        z-index: 1;
        transition: all 0.3s;
    }
    .toggle-label {
        width: 100%;
        height: 100%;
        background-color: #4A5568;
    }
</style>

<script>
    function toggleAllFilters(groupName) {
        const allCheckbox = document.getElementById(`all-${groupName}`);
        const itemCheckboxes = document.querySelectorAll(`input[data-item-group="${groupName}"]`);

        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = allCheckbox.checked;
        });
    }

    function syncAllFilterOption(groupName) {
        const allCheckbox = document.getElementById(`all-${groupName}`);
        const itemCheckboxes = Array.from(document.querySelectorAll(`input[data-item-group="${groupName}"]`));

        if (!allCheckbox || itemCheckboxes.length === 0) {
            return;
        }

        allCheckbox.checked = itemCheckboxes.every(checkbox => checkbox.checked);
    }

    function toggleEventType(selectedType) {
        const checkboxes = document.querySelectorAll('.event-type-checkbox');
        checkboxes.forEach(cb => {
            if (cb.value !== selectedType) {
                cb.checked = false;
            }
        });
    }

    function selectCity(cityName) {
        const select = document.getElementById('citySelect');
        let newSelection = cityName;
        
        if (select) {
            // If already selected, deselect it
            if (select.value === cityName) {
                newSelection = '';
            }
            select.value = newSelection;
        }
        
        // Update visual selection state
        document.querySelectorAll('.featured-city-item').forEach(el => {
            if (el.dataset.city === newSelection) {
                el.classList.add('selected');
                el.querySelector('.selection-indicator').classList.remove('hidden');
                el.querySelector('.selection-indicator').classList.add('flex');
                el.querySelector('.city-ring').classList.add('border-amber-400');
                el.querySelector('.city-ring').classList.remove('border-transparent');
            } else {
                el.classList.remove('selected');
                el.querySelector('.selection-indicator').classList.add('hidden');
                el.querySelector('.selection-indicator').classList.remove('flex');
                el.querySelector('.city-ring').classList.remove('border-amber-400');
                el.querySelector('.city-ring').classList.add('border-transparent');
            }
        });
    }

    function toggleBookingType(selectedType) {
        const checkboxes = document.querySelectorAll('.booking-type-checkbox');
        checkboxes.forEach(cb => {
            if (cb.value !== selectedType) {
                cb.checked = false;
            }
        });
    }

    function toggleFilterDrawer() {
        const drawer = document.getElementById('filter-drawer');
        if (drawer.classList.contains('hidden')) {
            drawer.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        } else {
            drawer.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore background scrolling
        }
    }

    function toggleCustomDate(value) {
        const customDateRange = document.getElementById('custom-date-range');
        if (value === 'custom') {
            customDateRange.classList.remove('hidden');
        } else {
            customDateRange.classList.add('hidden');
        }
    }

    function detectLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        
        // Use OpenStreetMap Nominatim API for reverse geocoding
        const geocodingUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;
        
        fetch(geocodingUrl)
            .then(response => response.json())
            .then(data => {
                let cityName = null;
                
                // Try to extract city name from various possible fields
                if (data.address) {
                    cityName = data.address.city || 
                               data.address.town || 
                               data.address.village ||
                               data.address.county;
                }
                
                console.log('Detected city from API:', cityName);
                console.log('Full address data:', data.address);
                
                if (cityName) {
                    // Check if city exists in our dropdown
                    const select = document.getElementById('citySelect');
                    const options = Array.from(select.options);
                    
                    console.log('Available cities:', options.map(opt => opt.value));
                    
                    // Exact match first
                    let matchingOption = options.find(opt => 
                        opt.value.toLowerCase().trim() === cityName.toLowerCase().trim()
                    );
                    
                    // If no exact match, try partial match
                    if (!matchingOption) {
                        matchingOption = options.find(opt => {
                            const optValue = opt.value.toLowerCase().trim();
                            const detectedCity = cityName.toLowerCase().trim();
                            return optValue.includes(detectedCity) || detectedCity.includes(optValue);
                        });
                    }
                    
                    // If still no match, try fuzzy matching (first few characters)
                    if (!matchingOption && cityName.length > 3) {
                        const prefix = cityName.toLowerCase().substring(0, 4);
                        matchingOption = options.find(opt => 
                            opt.value.toLowerCase().startsWith(prefix)
                        );
                    }
                    
                    console.log('Matching option:', matchingOption ? matchingOption.value : 'None found');
                    
                    if (matchingOption) {
                        selectCity(matchingOption.value);
                        alert(`✓ Location detected!\nCity: ${matchingOption.value}`);
                    } else {
                        alert(`Location detected!\nFound: ${cityName}\n\nThis city is not in our list. Please select manually from dropdown.`);
                    }
                } else {
                    alert("Could not determine city from your location. Please select manually.");
                }
            })
            .catch(error => {
                console.error('Geocoding error:', error);
                alert("Error detecting city from location. Please try again.");
            });
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation. Please enable location access in your browser settings.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out. Please try again.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred while detecting location.");
                break;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncAllFilterOption('categories');
        syncAllFilterOption('communities');
        syncAllFilterOption('gacchhs');
    });
</script>