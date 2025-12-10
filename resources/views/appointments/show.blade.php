@extends('index')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Appointment Details</h1>

    <div class="bg-white rounded-lg shadow p-8">
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Patient</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm font-semibold">Contact</p>
                <p class="text-lg text-gray-800">
                    {{ $appointment->patient->mobile_number ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm font-semibold">Service</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $appointment->service->service_name ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm font-semibold">Duration</p>
                <p class="text-lg text-gray-800">
                    {{ $appointment->service->duration ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm font-semibold">Date & Time</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y - h:i A') }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm font-semibold">Status</p>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                    @if($appointment->status === 'Completed') bg-green-100 text-green-800
                    @elseif($appointment->status === 'Cancelled') bg-red-100 text-red-800
                    @elseif($appointment->status === 'Ongoing') bg-yellow-100 text-yellow-800
                    @else bg-blue-100 text-blue-800
                    @endif
                ">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>

        @if($appointment->patient)
            <div class="border-t pt-6 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Patient Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="text-gray-800">{{ $appointment->patient->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Gender</p>
                        <p class="text-gray-800">{{ $appointment->patient->gender ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Birth Date</p>
                        <p class="text-gray-800">
                            {{ $appointment->patient->birth_date ? \Carbon\Carbon::parse($appointment->patient->birth_date)->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Occupation</p>
                        <p class="text-gray-800">{{ $appointment->patient->occupation ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex gap-4">
            <a href="{{ route('appointments.edit', $appointment) }}" class="bg-[#0086DA] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this appointment?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </form>
            <a href="{{ route('appointment') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                Back
            </a>
        </div>
    </div>
</div>
@endsection
