@if($showPatientModal && $patient)
    {{-- Backdrop --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        {{-- Modal Container --}}
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto m-8 flex flex-col max-h-[90vh]">
        
            <div class="flex-none p-8 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Patient ID: {{ $patient->id }}</p>
                    </div>

                    <button 
                        wire:click="closePatientModal" 
                        class="active:outline-2 active:outline-offset-3 active:outline-dashed active:outline-black bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded shadow flex items-center gap-2 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Close
                    </button>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="flex-1 overflow-y-auto px-8 py-6">
                {{-- Edit Button --}}
                @if(!$editingPatient)
                    <div class="mb-6 flex gap-2">
                        <button 
                            wire:click="toggleEditPatientModal"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/></svg>
                            Edit Information
                        </button>
                    </div>
                @endif

                {{-- VIEW MODE --}}
                @if(!$editingPatient)
                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">First Name</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->first_name ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Last Name</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->last_name ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Middle Name</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->middle_name ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Nickname</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->nickname ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Birth Date</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Gender</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->gender ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0086da" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            Contact Information
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Email</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->email_address ?? 'N/A' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Mobile Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->mobile_number ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Home Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->home_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Home Address</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->home_address ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Office Address</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->office_address ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Office Number</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->office_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            Additional Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Civil Status</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->civil_status ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Occupation</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->occupation ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                                <p class="text-xs text-gray-500 font-medium uppercase">Referral</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->referral ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            Emergency Contact
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase">Contact Name</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Contact Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->emergency_contact_number ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Relationship</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->relationship ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Family Information
                        </h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Father's Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->father_name ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Father's Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->father_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Mother's Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->mother_name ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Mother's Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->mother_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Guardian's Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->guardian_name ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Guardian's Number</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $patient->guardian_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- EDIT MODE -->
                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Personal Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" wire:model="editPatientData.first_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" wire:model="editPatientData.last_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                    <input type="text" wire:model="editPatientData.middle_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nickname</label>
                                    <input type="text" wire:model="editPatientData.nickname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                                    <input type="date" wire:model="editPatientData.birth_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                    <select wire:model="editPatientData.gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Contact Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" wire:model="editPatientData.email_address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                                        <input type="text" wire:model="editPatientData.mobile_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Home Number</label>
                                        <input type="text" wire:model="editPatientData.home_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Home Address</label>
                                    <textarea wire:model="editPatientData.home_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Office Address</label>
                                    <textarea wire:model="editPatientData.office_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Office Number</label>
                                    <input type="text" wire:model="editPatientData.office_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Additional Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                                    <input type="text" wire:model="editPatientData.civil_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                    <input type="text" wire:model="editPatientData.occupation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Referral</label>
                                <input type="text" wire:model="editPatientData.referral" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Emergency Contact</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                                    <input type="text" wire:model="editPatientData.emergency_contact_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                        <input type="text" wire:model="editPatientData.emergency_contact_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                                        <input type="text" wire:model="editPatientData.relationship" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Information -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Family Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Father's Name</label>
                                    <input type="text" wire:model="editPatientData.father_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Father's Number</label>
                                    <input type="text" wire:model="editPatientData.father_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Name</label>
                                    <input type="text" wire:model="editPatientData.mother_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Number</label>
                                    <input type="text" wire:model="editPatientData.mother_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Guardian's Name</label>
                                    <input type="text" wire:model="editPatientData.guardian_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Guardian's Number</label>
                                    <input type="text" wire:model="editPatientData.guardian_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200">
                            <button 
                                wire:click="savePatientModal"
                                class="flex-1 px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition">
                                Save Changes
                            </button>
                            <button 
                                wire:click="toggleEditPatientModal"
                                class="flex-1 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
