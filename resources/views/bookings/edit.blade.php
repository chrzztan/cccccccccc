<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            Edit Booking
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-xl">
        <form method="POST" action="{{ route('bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 border border-red-300 rounded-xl p-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-800">Title</label>
                    <input type="text" name="title" value="{{ old('title', $booking->title) }}"
                        class="w-full mt-2 p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors duration-200" required>
                </div>

                <!-- Date Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-800">Date</label>
                    <input type="date" name="date" value="{{ old('date', $booking->date) }}"
                        class="w-full mt-2 p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors duration-200" required>
                </div>

                <!-- Time Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-800">Time</label>
                    <input type="time" name="time" value="{{ old('time', $booking->time) }}"
                        class="w-full mt-2 p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors duration-200" required>
                </div>

                <!-- Duration Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-800">Duration (minutes)</label>
                    <input type="number" name="duration" value="{{ old('duration', $booking->duration) }}"
                        class="w-full mt-2 p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors duration-200" required>
                </div>

                <!-- Description Textarea -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-800">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full mt-2 p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors duration-200">{{ old('description', $booking->description) }}</textarea>
                </div>

                <!-- Status Select -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-800">Status</label>
                    <select name="status"
                        class="w-full mt-2 p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors duration-200">
                        <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="upcoming" {{ old('status', $booking->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                <!-- Cancel Button -->
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Cancel</a>

                <!-- Update Button -->
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg shadow-lg hover:bg-gray-800 transition-all duration-300">
                     Update Booking
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
