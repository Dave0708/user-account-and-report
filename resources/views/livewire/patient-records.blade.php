{{-- 
    MODIFIED: Added 'h-full flex flex-col'
    This makes the component fill its parent and become a flex container
--}}
<div class="h-full flex flex-col">
        
    <!-- Header (No change) -->
    <div class="flex flex-col gap-4 mb-6">
        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800">Patient Records</h1>
        
        <div class="flex  items-center  gap-3">
            <div class="relative w-full sm:w-auto bg-white">
                <input type="text" placeholder="Search by name" class=" w-96 pl-10 pr-4 py-2.5 border border-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    wire:model.live.debounce.300ms="search">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            
            <!-- Recent Button -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    @click.away="open = false"
                    class="flex shrink-0 items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 w-full sm:w-40 justify-center transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-down h-4 w-4 text-gray-600">
                        <path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/>
                    </svg>
                    <span>
                        @switch($sortOption)
                            @case('oldest') Oldest @break
                            @case('a_z') Name (A-Z) @break
                            @case('z_a') Name (Z-A) @break
                            @default Recent
                        @endswitch
                    </span>
                </button>

                <!-- Dropdown Menu -->
                <div 
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50"
                    style="display: none;"
                >
                    <button wire:click="setSort('recent')" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $sortOption === 'recent' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                        Recent (Newest)
                    </button>
                    <button wire:click="setSort('oldest')" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $sortOption === 'oldest' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                        Oldest First
                    </button>
                    <button wire:click="setSort('a_z')" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $sortOption === 'a_z' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                        Name (A-Z)
                    </button>
                    <button wire:click="setSort('z_a')" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $sortOption === 'z_a' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                        Name (Z-A)
                    </button>
                </div>
            </div>

            <!-- Add Patient Button -->
            <button 
                wire:click="$dispatch('openAddPatientModal')"
                type="button"
                class="active:outline-2 active:outline-offset-3 active:outline-dashed active:outline-black flex shrink-0 items-center gap-2 px-4 py-2.5 bg-[#0086da] text-white rounded-lg shadow-sm text-sm font-medium hover:bg-blue-00 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4">
                    <path d="M5 12h14"/><path d="M12 5v14"/>
                </svg>
                Add new patient
            </button>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 flex-1 overflow-hidden">
        <!-- Left Column: Patient List -->
        <div class="flex flex-col overflow-hidden">
            <!-- List Container -->
            <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-[#ccebff] scrollbar-thumb-[#0086da]">
                @forelse($patients as $patient)
                    <div class="group w-full px-5 py-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-between border-l-4
                        @if($patient->id == $selectedPatient?->id) border-[#0086da] bg-blue-50 @else border-transparent @endif">

                        <!-- LEFT SIDE: Clickable Patient Info -->
                        <button wire:click="selectPatient({{ $patient->id }})" class="flex-1 flex items-center gap-3">

                            <!-- Avatar Circle -->
                            <div class="w-10 h-10 rounded-full @if($patient->id == $selectedPatient?->id) bg-[#0086da] @else bg-blue-100 @endif flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="@if($patient->id == $selectedPatient?->id) white @else #0086da @endif" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>

                            <!-- Text Content -->
                            <div class="text-left flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 text-sm truncate">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </div>
                                <div class="text-xs text-gray-500 truncate">{{ $patient->mobile_number ?? 'No phone' }}</div>
                            </div>

                        </button>

                        <!-- RIGHT SIDE: Delete Button -->
                        <button wire:click="deletePatient({{ $patient->id }})" 
                            wire:confirm="Are you sure? This will delete all patient records and appointments."
                            class="ml-2 p-2 text-red-500 hover:bg-red-50 rounded-lg transition opacity-0 group-hover:opacity-100 flex-shrink-0"
                            title="Delete patient">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>

                    </div>

                @empty
                    <div class="p-4 text-center text-gray-500 text-sm">
                        No patients found for "{{ $search }}".
                    </div>
                @endforelse
            </div>
            <!-- Pagination links -->
            <div class="mt-4">
                {{ $patients->links() }}
            </div>
        </div>

        <!-- Right Column: Patient Quick View -->
        <div class="overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-[#ccebff] scrollbar-thumb-[#0086da]">
            @if ($selectedPatient)
                <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
                    <!-- Patient Name Header -->
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold text-gray-900">
                                {{ $selectedPatient->first_name }} {{ $selectedPatient->last_name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Patient ID: {{ $selectedPatient->id }}</p>
                        </div>
                        <span class="mt-1 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0">
                            Active
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 flex-wrap">
                        <button 
                            wire:click="$dispatch('open-history-modal', { patientId: {{ $selectedPatient->id }} })"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            History
                        </button>
                        <button 
                            wire:click="$dispatch('openPatientModal', {{ $selectedPatient->id }})"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-[#0086da] hover:bg-blue-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/></svg>
                            View/Edit
                        </button>
                        <button 
                            wire:click="deletePatient({{ $selectedPatient->id }})"
                            wire:confirm="Are you sure? This will delete the patient and all related appointments."
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Delete
                        </button>
                    </div>

                    <!-- Divider -->
                    <hr class="border-gray-200">

                    <!-- Quick Info Display -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Quick Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <p class="text-xs text-gray-500 font-medium uppercase">Phone</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->mobile_number ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <p class="text-xs text-gray-500 font-medium uppercase">Email</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->email_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <p class="text-xs text-gray-500 font-medium uppercase">Address</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->home_address ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="border-gray-200">

                    <!-- Last Visit -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-xs text-gray-600 font-medium uppercase">Last Visit</p>
                        @if ($lastVisit)
                            <p class="text-lg font-bold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($lastVisit->appointment_date)->format('M d, Y \a\t H:i') }}</p>
                        @else
                            <p class="text-sm text-gray-600 mt-1">No completed visits yet</p>
                        @endif
                    </div>

                    <!-- Info Button -->
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-sm text-gray-600">Click <span class="font-semibold">"View/Edit"</span> to see complete patient information</p>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-8 flex items-center justify-center h-full">
                    <p class="text-gray-500">Please select a patient to view details.</p>
                </div>
            @endif

        </div>
                        <!-- Divider -->
                        <hr class="border-gray-200">

                        <!-- Personal Information Section -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                Personal Information
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">First Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->first_name ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Last Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->last_name ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Middle Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->middle_name ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Nickname</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->nickname ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Birth Date</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->birth_date ? \Carbon\Carbon::parse($selectedPatient->birth_date)->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Gender</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->gender ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0086da" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                Contact Information
                            </h4>
                            <div class="space-y-3">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Email</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->email_address ?? 'N/A' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Mobile Number</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->mobile_number ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Home Number</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->home_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Home Address</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->home_address ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Office Address</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->office_address ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Office Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->office_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                Additional Information
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Civil Status</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->civil_status ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Occupation</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->occupation ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Referral</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->referral ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                Emergency Contact
                            </h4>
                            <div class="space-y-3">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Contact Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->emergency_contact_name ?? 'N/A' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Contact Number</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->emergency_contact_number ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Relationship</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->relationship ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Information Section -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Family Information
                            </h4>
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Father's Name</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->father_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Father's Number</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->father_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Mother's Name</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->mother_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Mother's Number</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->mother_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Guardian's Name</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->guardian_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-xs text-gray-500 font-medium uppercase">Guardian's Number</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $selectedPatient->guardian_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Last Visit -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                            <p class="text-xs text-gray-600 font-medium uppercase">Last Visit</p>
                            @if ($lastVisit)
                                <p class="text-lg font-bold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($lastVisit->appointment_date)->format('M d, Y \a\t H:i') }}</p>
                            @else
                                <p class="text-sm text-gray-600 mt-1">No completed visits yet</p>
                            @endif
                        </div>
                    @else
                        <!-- EDIT MODE -->
                        <hr class="border-gray-200">
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900">Edit Patient Information</h4>
                            
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h5 class="font-medium text-gray-700">Personal Information</h5>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" wire:model="editData.first_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" wire:model="editData.last_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                        <input type="text" wire:model="editData.middle_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nickname</label>
                                        <input type="text" wire:model="editData.nickname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                                        <input type="date" wire:model="editData.birth_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                        <select wire:model="editData.gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-4">
                                <h5 class="font-medium text-gray-700">Contact Information</h5>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" wire:model="editData.email_address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                                            <input type="text" wire:model="editData.mobile_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Home Number</label>
                                            <input type="text" wire:model="editData.home_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Home Address</label>
                                        <textarea wire:model="editData.home_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Office Address</label>
                                        <textarea wire:model="editData.office_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Office Number</label>
                                        <input type="text" wire:model="editData.office_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-4">
                                <h5 class="font-medium text-gray-700">Additional Information</h5>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                                        <input type="text" wire:model="editData.civil_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                        <input type="text" wire:model="editData.occupation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Referral</label>
                                    <input type="text" wire:model="editData.referral" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="space-y-4">
                                <h5 class="font-medium text-gray-700">Emergency Contact</h5>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                                        <input type="text" wire:model="editData.emergency_contact_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                            <input type="text" wire:model="editData.emergency_contact_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                                            <input type="text" wire:model="editData.relationship" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Information -->
                            <div class="space-y-4">
                                <h5 class="font-medium text-gray-700">Family Information</h5>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Father's Name</label>
                                        <input type="text" wire:model="editData.father_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Father's Number</label>
                                        <input type="text" wire:model="editData.father_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Name</label>
                                        <input type="text" wire:model="editData.mother_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Number</label>
                                        <input type="text" wire:model="editData.mother_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian's Name</label>
                                        <input type="text" wire:model="editData.guardian_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian's Number</label>
                                        <input type="text" wire:model="editData.guardian_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Save/Cancel Buttons -->
                            <div class="flex gap-2 pt-4 border-t border-gray-200">
                                <button 
                                    wire:click="savePatientInfo"
                                    class="flex-1 px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition">
                                    Save Changes
                                </button>
                                <button 
                                    wire:click="toggleEditMode"
                                    class="flex-1 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 transition">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-8 flex items-center justify-center h-full">
                    <p class="text-gray-500">Please select a patient to view details.</p>
                </div>
            @endif

        </div>

    </div>
</div>

<!-- Patient Info Modal Component -->
<livewire:patient-info-modal />