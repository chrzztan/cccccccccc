<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-black leading-tight">
            Booking Dashboard
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-3 space-y-8">
                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-500 text-red-700 p-4 rounded-lg mb-6">
                        <ul class="list-disc ml-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Booking Table --}}
                <div class="overflow-x-auto bg-white border border-gray-300 rounded-2xl shadow-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-6 py-4">Title</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Time</th>
                                <th class="px-6 py-4">Duration</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr class="border-t hover:bg-gray-100">
                                    <td class="px-6 py-4">{{ $booking->title }}</td>
                                    <td class="px-6 py-4">{{ $booking->date }}</td>
                                    <td class="px-6 py-4">{{ $booking->time }}</td>
                                    <td class="px-6 py-4">{{ $booking->duration }} min</td>
                                    <td class="px-6 py-4 capitalize">{{ $booking->status }}</td>
                                    <td class="px-6 py-4 flex gap-4">
                                        @can('update', $booking)
                                            <a href="{{ route('bookings.edit', $booking) }}" class="text-blue-600 hover:text-blue-700 transition-all">Edit</a>
                                        @endcan
                                        @can('delete', $booking)
                                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Delete this booking?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 transition-all">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-gray-500 p-6">No bookings yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Calendar --}}
                <div id="calendar" class="bg-gray-100 border border-gray-300 p-6 rounded-2xl shadow-xl mt-8"></div>
            </div>

            {{-- Sidebar Booking Summary --}}
            <div class="space-y-6">
                @foreach (['total' => 'Total Bookings', 'upcoming' => 'Upcoming', 'pending' => 'Pending', 'completed' => 'Completed', 'users' => 'Total Users'] as $key => $label)
                    <div class="bg-white border border-gray-300 shadow-xl rounded-xl p-6">
                        <div class="text-sm text-gray-600">{{ $label }}</div>
                        <div class="text-3xl font-bold text-black">{{ $stats[$key] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- FullCalendar Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                height: "auto",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                select: function (info) {
                    Swal.fire({
                        title: '<span class="text-lg font-semibold text-gray-800">New Booking</span>',
                        width: 600,
                        background: '#000000ff',
                        showCancelButton: true,
                        confirmButtonText: 'Save Booking',
                        cancelButtonText: 'Cancel',
                        focusConfirm: false,
                        html: ` 
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-800">Booking Title <span class="text-red-500">*</span></label>
                                    <input type="text" id="swal-title" class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-gray-400" placeholder="e.g., Project Discussion">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-800">Time <span class="text-red-500">*</span></label>
                                        <input type="time" id="swal-time" class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-800">Duration (minutes) <span class="text-red-500">*</span></label>
                                        <input type="number" id="swal-duration" class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-gray-400" min="1" placeholder="30">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-800">Description</label>
                                    <textarea id="swal-description" class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-gray-400" rows="4" placeholder="Additional notes"></textarea>
                                </div>
                            </div>
                        `,
                        preConfirm: () => {
                            const title = document.getElementById('swal-title').value.trim();
                            const time = document.getElementById('swal-time').value.trim();
                            const duration = document.getElementById('swal-duration').value.trim();
                            const description = document.getElementById('swal-description').value.trim();

                            if (!title || !time || !duration) {
                                Swal.showValidationMessage('All required fields must be filled.');
                                return false;
                            }

                            return { title, time, duration, description };
                        }
                    }).then(result => {
                        if (result.isConfirmed && result.value) {
                            fetch("{{ route('bookings.store') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                                body: JSON.stringify({
                                    title: result.value.title,
                                    date: info.startStr,
                                    time: result.value.time,
                                    duration: result.value.duration,
                                    description: result.value.description
                                })
                            })
                            .then(async res => {
                                if (!res.ok) {
                                    const err = await res.json();
                                    throw new Error(err.message || 'Failed to create booking.');
                                }
                                return res.json();
                            })
                            .then(data => {
                                Swal.fire("Success", "Booking has been created!", "success");
                                calendar.addEvent({
                                    title: data.booking.title,
                                    start: `${data.booking.date}T${data.booking.time}`,
                                    url: `/bookings/${data.booking.id}/edit`
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire("Error", err.message || "Something went wrong", "error");
                            });
                        }
                    });
                },
                events: [
                    @foreach ($bookings as $booking)
                        {
                            title: '{{ $booking->title }}',
                            start: '{{ $booking->date }}T{{ $booking->time }}',
                            url: '{{ route('bookings.edit', $booking) }}'
                        },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
</x-app-layout>
