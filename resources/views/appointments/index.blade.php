@extends('index')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Appointments</h1>
        <a href="{{ route('appointments.create') }}" class="bg-[#0086DA] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + New Appointment
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Appointments Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date & Time</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Patient</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Service</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y - h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $appointment->service->service_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($appointment->status === 'Completed') bg-green-100 text-green-800
                                @elseif($appointment->status === 'Cancelled') bg-red-100 text-red-800
                                @elseif($appointment->status === 'Ongoing') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif
                            ">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('appointments.edit', $appointment) }}" class="text-green-600 hover:text-green-900">Edit</a>
                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this appointment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No appointments found. <a href="{{ route('appointments.create') }}" class="text-blue-600">Create one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($appointments->hasPages())
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
@endsection
