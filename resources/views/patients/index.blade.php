@extends('index')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Patients</h1>
        <a href="{{ route('patients.create') }}" class="bg-[#0086DA] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + New Patient
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Patients Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Mobile Number</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Appointments</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $patient->mobile_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $patient->email ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $patient->gender ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $patient->appointments()->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('patients.edit', $patient) }}" class="text-green-600 hover:text-green-900">Edit</a>
                            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline" onsubmit="return confirm('Delete this patient?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No patients found. <a href="{{ route('patients.create') }}" class="text-blue-600">Create one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($patients->hasPages())
        <div class="mt-6">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
