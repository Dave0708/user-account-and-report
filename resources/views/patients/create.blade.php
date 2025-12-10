@extends('index')

@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Create Patient</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('patients.store') }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf

        <h2 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h2>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror" value="{{ old('last_name') }}" required>
                @error('last_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                <input type="text" name="mobile_number" id="mobile_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mobile_number') border-red-500 @enderror" value="{{ old('mobile_number') }}" required>
                @error('mobile_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email_address" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email_address" id="email_address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email_address') border-red-500 @enderror" value="{{ old('email_address') }}">
                @error('email_address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror" value="{{ old('birth_date') }}">
                @error('birth_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gender') border-red-500 @enderror">
                    <option value="">-- Select --</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
            <input type="text" name="occupation" id="occupation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('occupation') border-red-500 @enderror" value="{{ old('occupation') }}">
            @error('occupation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-8">
            <label for="home_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea name="home_address" id="home_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('home_address') border-red-500 @enderror">{{ old('home_address') }}</textarea>
            @error('home_address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-[#0086DA] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Patient
            </button>
            <a href="{{ route('patients.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
