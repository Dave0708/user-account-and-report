@extends('index')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Patient Reports</h1>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Filters</h3>
        <form action="{{ route('reports.patients') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Patient</label>
                <input type="text" name="search" id="search" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Name, email, or phone..." value="{{ request('search') }}">
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">-- All --</option>
                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#0086DA] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
                <a href="{{ route('reports.patients') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded text-center">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Mobile Number</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Appointments</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                        <td class="px-6 py-4">{{ $patient->mobile_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $patient->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $patient->gender ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $patient->appointments()->count() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No patients found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($patients->hasPages())
        <div class="mb-4">
            {{ $patients->links() }}
        </div>
    @endif

    <div class="flex gap-4">
        <a href="{{ route('reports.export-patients') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Export as CSV
        </a>
        <a href="{{ route('reports.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
