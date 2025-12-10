@extends('index')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Create Appointment</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('appointments.store') }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf

        <div class="mb-6">
            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
            <select name="patient_id" id="patient_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('patient_id') border-red-500 @enderror" required>
                <option value="">-- Select Patient --</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->first_name }} {{ $patient->last_name }}
                    </option>
                @endforeach
            </select>
            @error('patient_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
            <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('service_id') border-red-500 @enderror" required>
                <option value="">-- Select Service --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->service_name }}
                    </option>
                @endforeach
            </select>
            @error('service_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date & Time</label>
            <input type="datetime-local" name="appointment_date" id="appointment_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('appointment_date') border-red-500 @enderror" value="{{ old('appointment_date') }}" required>
            @error('appointment_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                <option value="Scheduled" {{ old('status', 'Scheduled') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-[#0086DA] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Appointment
            </button>
            <a href="{{ route('appointment') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
