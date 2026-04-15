<!-- Interactive Seat Selector -->
<div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-4 sm:p-6 space-y-5" id="seatSelectorContainer">

    <!-- Header: title + show timing select -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <h2 class="text-xl sm:text-2xl font-bold flex-shrink-0">Select Your Seats</h2>
        <div class="sm:ml-auto w-full sm:w-auto">
            <label class="block text-xs text-slate-400 mb-1 sm:hidden">Show Timing</label>
            <select id="showTimingSelect" class="w-full sm:w-auto min-w-0 px-3 py-2.5 rounded-lg bg-slate-800 border border-white/15 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition truncate">
                <option value="">Choose a show timing...</option>
                @if($event->showTimings->isNotEmpty())
                    @foreach($event->showTimings as $showTiming)
                        <option value="{{ $showTiming->id }}">
                            {{ $showTiming->show_date_time->format('M d, Y H:i') }} — {{ $showTiming->venue->name ?? 'Venue' }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <!-- Seat Map -->
    <div id="seatMapContainer" class="hidden">
        <div class="bg-slate-900/60 rounded-xl border border-white/10">
            <!-- Screen indicator -->
            <div class="text-center text-slate-300 text-xs sm:text-sm font-semibold py-3 px-4 border-b border-white/10 tracking-widest uppercase">
                <i class="fas fa-film mr-2 text-amber-400/70"></i>Screen
            </div>

            <!-- Seat Grid — horizontally scrollable on mobile -->
            <div class="overflow-x-auto py-4 px-2 sm:px-4">
                <div id="seatGrid" class="inline-block min-w-full">
                    <!-- Seats dynamically loaded -->
                </div>
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap gap-4 sm:gap-6 text-xs sm:text-sm text-slate-200 py-3 px-4 border-t border-white/10 justify-center">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-emerald-500"></div>
                    <span>Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-slate-600"></div>
                    <span>Booked</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-emerald-500 border-2 border-amber-400"></div>
                    <span>Selected</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Price Info -->
    <div id="categoryPrices" class="hidden">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
            <!-- Dynamically loaded -->
        </div>
    </div>

    <!-- Selected Seats Summary -->
    <div id="selectedSeatsSummary" class="hidden rounded-xl bg-slate-900/60 border border-white/10 p-4 space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-base sm:text-lg font-semibold">Selected Seats</h3>
            <button type="button" id="clearSeatsBtn" class="text-xs sm:text-sm px-3 py-1.5 rounded-lg bg-slate-700 border border-white/10 text-slate-200 hover:bg-slate-600 transition">
                <i class="fas fa-times mr-1"></i>Clear All
            </button>
        </div>
        <div id="selectedSeatsList" class="flex flex-wrap gap-2"></div>
        <div class="pt-3 border-t border-white/10 flex flex-col sm:flex-row sm:items-center gap-3 sm:justify-between">
            <div>
                <p class="text-slate-300 text-xs sm:text-sm">Total Price</p>
                <p class="text-2xl sm:text-3xl font-bold text-amber-300">₹<span id="totalPrice">0</span></p>
            </div>
            <form id="bookingForm" method="POST" action="" class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="show_timing_id" id="showTimingIdInput">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-900 font-bold rounded-xl text-base transition-all duration-200 shadow-lg">
                    <i class="fas fa-check"></i> Confirm Booking
                </button>
            </form>
        </div>
    </div>

    <!-- No Show Timing Message -->
    <div id="noShowTimingMessage" class="text-center py-10 text-slate-400">
        <i class="fas fa-clock text-4xl mb-3 block"></i>
        <p class="text-sm">Please select a show timing to view available seats</p>
    </div>

    @if($event->showTimings->isEmpty())
        <div class="rounded-xl border border-amber-400/30 bg-amber-500/10 text-amber-100 p-4 text-sm">
            <i class="fas fa-info-circle mr-2"></i>
            <span>Show timings will be available soon. Check back later!</span>
        </div>
    @endif
</div>

<style>
    /* Seat buttons — smaller on mobile, normal on desktop */
    .seat-btn {
        width: 1.875rem;
        height: 1.875rem;
        border-radius: 0.25rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 0.6rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    @media (min-width: 640px) {
        .seat-btn {
            width: 2.25rem;
            height: 2.25rem;
            font-size: 0.65rem;
        }
    }

    .seat-available {
        background-color: rgb(16 185 129 / 0.7);
        color: white;
    }
    .seat-available:hover {
        background-color: rgb(16 185 129);
        transform: scale(1.12);
    }
    .seat-booked {
        background-color: rgb(71 85 105 / 0.5);
        color: rgb(148 163 184 / 0.4);
        cursor: not-allowed;
    }
    .seat-selected {
        background-color: rgb(16 185 129);
        border-color: rgb(251 191 36);
        color: white;
        box-shadow: 0 0 0 2px rgb(251 191 36 / 0.4);
    }

    .seat-row {
        display: flex;
        gap: 0.25rem;
        align-items: center;
        margin-bottom: 0.25rem;
        width: max-content;
        margin-left: auto;
        margin-right: auto;
    }
    @media (min-width: 640px) {
        .seat-row { gap: 0.375rem; margin-bottom: 0.375rem; }
    }

    .row-label {
        width: 1.5rem;
        flex-shrink: 0;
        text-align: right;
        font-size: 0.65rem;
        color: rgb(148 163 184);
        font-weight: 700;
    }
    @media (min-width: 640px) {
        .row-label { width: 1.75rem; font-size: 0.75rem; }
    }

    /* Keep the scroll container from overflowing the card on small screens */
    #seatMapContainer { max-width: 100%; overflow: hidden; }
    #seatGrid { display: block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showTimingSelect = document.getElementById('showTimingSelect');
    const seatMapContainer = document.getElementById('seatMapContainer');
    const seatGrid = document.getElementById('seatGrid');
    const categoryPrices = document.getElementById('categoryPrices');
    const selectedSeatsSummary = document.getElementById('selectedSeatsSummary');
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const noShowTimingMessage = document.getElementById('noShowTimingMessage');
    const bookingForm = document.getElementById('bookingForm');
    const showTimingIdInput = document.getElementById('showTimingIdInput');
    const totalPriceSpan = document.getElementById('totalPrice');
    const clearSeatsBtn = document.getElementById('clearSeatsBtn');

    let selectedSeats = new Map(); // Map of seatId -> {id, number, row, category, price}
    let currentSeatsData = [];

    showTimingSelect.addEventListener('change', async function() {
        const showTimingId = this.value;
        
        if (!showTimingId) {
            seatMapContainer.classList.add('hidden');
            categoryPrices.classList.add('hidden');
            selectedSeatsSummary.classList.add('hidden');
            noShowTimingMessage.classList.remove('hidden');
            selectedSeats.clear();
            updateSummary();
            return;
        }

        try {
            const response = await fetch(`/user/show-timings/${showTimingId}/seats`);
            const data = await response.json();

            currentSeatsData = data.seats;
            
            // Update form action and show timing ID
            const eventId = '{{ $event->id }}';
            bookingForm.action = `/user/bookings/${eventId}`;
            showTimingIdInput.value = showTimingId;

            // Clear previous selection
            selectedSeats.clear();

            // Render seat map
            renderSeatMap(data.seats, data.categories);

            // Render category prices
            renderCategoryPrices(data.categories);

            // Show/hide elements
            seatMapContainer.classList.remove('hidden');
            categoryPrices.classList.remove('hidden');
            noShowTimingMessage.classList.add('hidden');
            updateSummary();
        } catch (error) {
            console.error('Error fetching seats:', error);
            alert('Failed to load available seats. Please try again.');
        }
    });

    function renderSeatMap(seatsData, categories) {
        seatGrid.innerHTML = '';

        // Group seats by row
        const seatsByRow = new Map();
        
        seatsData.forEach(category => {
            category.seats.forEach(seat => {
                if (!seatsByRow.has(seat.row)) {
                    seatsByRow.set(seat.row, []);
                }
                seatsByRow.get(seat.row).push({
                    ...seat,
                    category_id: category.category_id,
                    category_name: category.category_name,
                    category_color: category.category_color
                });
            });
        });

        // Sort rows and render
        const sortedRows = Array.from(seatsByRow.keys()).sort((a, b) => a - b);

        sortedRows.forEach((row, rowIndex) => {
            const rowSeats = seatsByRow.get(row).sort((a, b) => a.column - b.column);
            const rowDiv = document.createElement('div');
            rowDiv.className = 'seat-row';

            // Row label — use sequential index (A, B, C...) regardless of stored row_number
            const rowLetter = rowIndex < 26
                ? String.fromCharCode(65 + rowIndex)
                : String.fromCharCode(65 + Math.floor(rowIndex / 26) - 1) + String.fromCharCode(65 + (rowIndex % 26));
            const rowLabel = document.createElement('span');
            rowLabel.className = 'row-label';
            rowLabel.textContent = rowLetter;
            rowDiv.appendChild(rowLabel);

            // Seats in row
            rowSeats.forEach(seat => {
                const seatBtn = document.createElement('button');
                seatBtn.type = 'button';
                    const isAvailable = seat.status === 'available';
                    const statusClass = isAvailable ? 'seat-available' : 'seat-booked';
                    seatBtn.className = `seat-btn ${statusClass}`;
                seatBtn.textContent = seat.column;
                seatBtn.title = `${seat.category_name} - ₹${Math.round(seat.price)} (${seat.status})`;
                seatBtn.dataset.seatId = seat.id;
                seatBtn.dataset.seatNumber = seat.seat_number;
                seatBtn.dataset.categoryName = seat.category_name;
                seatBtn.dataset.price = seat.price;
                seatBtn.dataset.row = row;
                seatBtn.dataset.rowLabel = rowLetter;
                seatBtn.dataset.column = seat.column;

                    if (isAvailable) {
                        seatBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            toggleSeat(seatBtn);
                        });
                    } else {
                        seatBtn.disabled = true;
                    }

                rowDiv.appendChild(seatBtn);
            });

            seatGrid.appendChild(rowDiv);
        });
    }

    function renderCategoryPrices(categories) {
        // Target the inner grid div, not the outer wrapper
        const grid = categoryPrices.querySelector('div');
        grid.innerHTML = '';

        categories.forEach(category => {
            const priceDiv = document.createElement('div');
            priceDiv.className = 'rounded-lg bg-slate-900/60 border border-white/10 p-3 flex items-center justify-between gap-2';
            priceDiv.innerHTML = `
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-3 h-3 rounded flex-shrink-0" style="background-color: ${category.color}60;"></span>
                    <span class="text-xs sm:text-sm truncate">${category.name}</span>
                </div>
                <span class="font-semibold text-amber-300 text-sm flex-shrink-0">₹${Math.round(category.base_price)}</span>
            `;
            grid.appendChild(priceDiv);
        });
    }

    function toggleSeat(seatBtn) {
        const seatId = seatBtn.dataset.seatId;
        const price = parseFloat(seatBtn.dataset.price);

        if (selectedSeats.has(seatId)) {
            // Deselect
            selectedSeats.delete(seatId);
            seatBtn.classList.remove('seat-selected');
            seatBtn.classList.add('seat-available');
        } else {
            // Select
            selectedSeats.set(seatId, {
                id: seatId,
                number: seatBtn.dataset.seatNumber,
                row: seatBtn.dataset.row,
                rowLabel: seatBtn.dataset.rowLabel,
                column: seatBtn.dataset.column,
                category: seatBtn.dataset.categoryName,
                price: price
            });
            seatBtn.classList.remove('seat-available');
            seatBtn.classList.add('seat-selected');
        }

        updateSummary();
    }

    function updateSummary() {
        if (selectedSeats.size === 0) {
            selectedSeatsSummary.classList.add('hidden');
            // Clear all existing seat_ids inputs
            document.querySelectorAll('input[name="seat_ids[]"]').forEach(input => input.remove());
        } else {
            selectedSeatsSummary.classList.remove('hidden');
            
            // Update selected seats list
            selectedSeatsList.innerHTML = '';
            let totalPrice = 0;

            const sortedSeats = Array.from(selectedSeats.values())
                .sort((a, b) => a.row - b.row || a.column - b.column);

            // Clear existing seat_ids inputs
            document.querySelectorAll('input[name="seat_ids[]"]').forEach(input => input.remove());

            // Create individual hidden inputs for each seat
            sortedSeats.forEach(seat => {
                const tag = document.createElement('span');
                tag.className = 'px-3 py-1 rounded-full text-xs bg-emerald-500/20 border border-emerald-400/30 text-emerald-100';
                tag.textContent = `${seat.rowLabel || seat.row}${seat.column} (${seat.category})`;
                selectedSeatsList.appendChild(tag);
                
                // Create hidden input for this seat ID
                const seatInput = document.createElement('input');
                seatInput.type = 'hidden';
                seatInput.name = 'seat_ids[]';
                seatInput.value = seat.id;
                bookingForm.appendChild(seatInput);
                
                totalPrice += seat.price;
            });

            totalPriceSpan.textContent = Math.round(totalPrice);
        }
    }

    clearSeatsBtn.addEventListener('click', function() {
        selectedSeats.forEach((seat, seatId) => {
            const seatBtn = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (seatBtn) {
                seatBtn.classList.remove('seat-selected');
                seatBtn.classList.add('seat-available');
            }
        });
        selectedSeats.clear();
        updateSummary();
    });

    // Prevent form submission if no seats selected
    bookingForm.addEventListener('submit', function(e) {
        if (selectedSeats.size === 0) {
            e.preventDefault();
            alert('Please select at least one seat');
            return false;
        }
    });
});
</script>
