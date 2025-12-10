<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonInterval;

class AppointmentCalendar extends Component
{
    // ... (All other properties and methods are unchanged) ...
    public $currentDate;
    public $viewType = 'week';
    public $weekDates = [];
    public $timeSlots = [];
    /** @var \Illuminate\Support\Collection */
    public $appointments = [];
    public $showAppointmentModal = false;
    /** @var \Illuminate\Support\Collection */
    public $servicesList = [];
    public $firstName = '';
    public $lastName = '';
    public $middleName = '';
    public $recordNumber = '';
    public $contactNumber = '';
    public $birthDate = null;  
    public $selectedService = '';
    public $selectedDate = null;
    public $selectedTime = null;
    public $endTime = null;
    public $isViewing = false; 
    public $viewingAppointmentId = null; 
    public $appointmentStatus = '';     
    public $searchQuery = '';
    public $patientSearchResults = [];

    protected $rules = [
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'middleName' => 'nullable|string|max:100',
        'contactNumber' => 'required|string|max:20',
        'selectedService' => 'required',
        'selectedDate' => 'required',
        'selectedTime' => 'required',
        'endTime' => 'required',
        'birthDate' => 'required'
    ];

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->generateWeekDates(); 
        $this->generateTimeSlots();
        $this->loadAppointments();
        
        // Cache services list for better performance
        $this->servicesList = Cache::remember('services_list', 3600, function () {
            return DB::table('services')->get();
        });
    }

    public function generateWeekDates()
    {
        $this->weekDates = [];
        $startOfWeek = $this->currentDate->copy()->startOfWeek();
        
        for ($i = 0; $i < 7; $i++) {
            $this->weekDates[] = $startOfWeek->copy()->addDays($i);
        }
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];
        for ($hour = 9; $hour <= 19; $hour++) {
            $this->timeSlots[] = sprintf('%02d:00', $hour);
            if ($hour != 19) {
                $this->timeSlots[] = sprintf('%02d:30', $hour);
            }        
        }
    }

    public function loadAppointments()
    {
        $startOfWeek = $this->weekDates[0]->startOfDay();
        $endOfWeek = $this->weekDates[6]->endOfDay();

        $this->appointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->select(
                'appointments.*', 
                'patients.first_name', 
                'patients.last_name', 
                'patients.middle_name', 
                'services.service_name', 
                'services.duration'
            )
            ->get()
            // THIS IS WTF
            ->map(function($appointment) {
                
                sscanf($appointment->duration, '%d:%d:%d', $h, $m, $s);
                $appointment->duration_in_minutes = ($h * 60) + $m;
                
                $carbonDate = Carbon::parse($appointment->appointment_date);
                $appointment->start_date = $carbonDate->toDateString();
                $appointment->start_time = $carbonDate->format('H:i');
                
                $appointment->end_time = $carbonDate->copy()
                                 ->addMinutes($appointment->duration_in_minutes)
                                 ->format('H:i');
                return $appointment;
            });
    }
    

    public function getAppointmentsForDay($date)
    {
        return $this->appointments->where('start_date', $date->toDateString());
    }

    public function previousWeek()
    {
        $this->currentDate = $this->currentDate->subWeek();
        $this->generateWeekDates();
        $this->loadAppointments();
    }

    public function nextWeek()
    {
        $this->currentDate = $this->currentDate->addWeek();
        $this->generateWeekDates();
        $this->loadAppointments();
    }

    public function changeView($type)
    {
        $this->viewType = $type;
    }
    
    protected function resetForm()
    {
        $this->resetValidation();
        $this->firstName = '';
        $this->lastName = '';
        $this->middleName = '';
        $this->recordNumber = '';
        $this->contactNumber = '';
        $this->birthDate = null; // <--- ADD THIS
        $this->selectedService = '';
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->endTime = null;
        $this->isViewing = false; // <-- Add this line
        $this->viewingAppointmentId = null;
        $this->appointmentStatus = '';
        $this->searchQuery = '';
        $this->patientSearchResults = [];   
        
    }

    public function openAppointmentModal($date, $time)
    {
        $this->resetForm();
        $this->selectedDate = $date;
        $this->selectedTime = $time;
        $this->showAppointmentModal = true;
    }

    public function closeAppointmentModal()
    {
        $this->showAppointmentModal = false;
        $this->resetForm();
    }

    public function updatedSelectedService($serviceId)
    {
        $service = $this->servicesList->firstWhere('id', $serviceId);

        if ($service) {
            // This logic was already correct, as it's used in the modal
            list($hours, $minutes, $seconds) = explode(':', $service->duration);
            $this->endTime = Carbon::parse($this->selectedTime)
                                   ->addHours((int)$hours)
                                   ->addMinutes((int)$minutes)
                                   ->format('H:i');
        } else {
            $this->endTime = null;
        }
    }

    public function saveAppointment()
    {
        try {
            // 1. Validate required fields
            $validated = $this->validate([
                'firstName' => 'required|string|max:100',
                'lastName' => 'required|string|max:100',
                'contactNumber' => 'required|string|max:20',
                'birthDate' => 'nullable|date',
                'selectedService' => 'required|numeric',
                'selectedDate' => 'required|date_format:Y-m-d',
                'selectedTime' => 'required|date_format:H:i',
            ]);

            // 2. Get service details
            $service = DB::table('services')->find($this->selectedService);
            if (!$service) {
                $this->addError('selectedService', 'Invalid service selected.');
                return;
            }

            // Parse service duration (format: HH:MM:SS)
            list($hours, $minutes, $seconds) = explode(':', $service->duration);
            $durationInMinutes = (intval($hours) * 60) + intval($minutes);

            // 3. Calculate proposed appointment times
            $proposedStart = Carbon::parse($this->selectedDate . ' ' . $this->selectedTime);
            $proposedEnd = $proposedStart->copy()->addMinutes($durationInMinutes);

            // 4. Check for time conflicts with existing appointments
            // Using simple date comparison instead of TIME_TO_SEC for better compatibility
            $conflicts = DB::table('appointments')
                ->where('appointments.status', '!=', 'Cancelled')
                ->whereDate('appointment_date', $this->selectedDate)
                ->where(function ($query) use ($proposedStart, $proposedEnd) {
                    // Get appointments and check in PHP instead of SQL for duration calculation
                    $this->appointments->each(function ($apt) use ($proposedStart, $proposedEnd, &$conflicts) {
                        $aptStart = Carbon::parse($apt->appointment_date);
                        // Estimate end time based on service duration if available
                        $aptEnd = $aptStart->copy()->addMinutes(60); // Default 1 hour
                        
                        if ($aptStart < $proposedEnd && $aptEnd > $proposedStart) {
                            return false; // Mark as conflicted
                        }
                    });
                })
                ->count();

            // Alternative: Direct PHP check through loaded appointments
            $conflicts = 0;
            foreach ($this->appointments as $apt) {
                if ($apt->appointment_date !== $this->selectedDate) continue;
                
                $aptStart = Carbon::parse($apt->appointment_date);
                $aptEnd = $aptStart->copy()->addMinutes($durationInMinutes);
                
                if ($aptStart < $proposedEnd && $aptEnd > $proposedStart) {
                    $conflicts++;
                    break;
                }
            }

            if ($conflicts > 0) {
                $this->addError('conflict', 'This time slot conflicts with an existing appointment. Please select a different time.');
                return;
            }

            // 5. Find or create patient
            $patient = DB::table('patients')
                ->where('mobile_number', $this->contactNumber)
                ->first();

            if ($patient) {
                $patientId = $patient->id;
                DB::table('patients')
                    ->where('id', $patientId)
                    ->update([
                        'first_name' => $this->firstName,
                        'last_name' => $this->lastName,
                        'middle_name' => $this->middleName ?? '',
                        'birth_date' => $this->birthDate,
                        'updated_at' => now(),
                    ]);
            } else {
                $patientId = DB::table('patients')->insertGetId([
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'middle_name' => $this->middleName ?? '',
                    'mobile_number' => $this->contactNumber,
                    'birth_date' => $this->birthDate,
                    'modified_by' => 'SYSTEM',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 6. Create appointment with exact datetime
            $appointmentDateTime = Carbon::parse($this->selectedDate . ' ' . $this->selectedTime);

            DB::table('appointments')->insert([
                'patient_id' => $patientId,
                'service_id' => $this->selectedService,
                'appointment_date' => $appointmentDateTime->toDateTimeString(),
                'status' => 'Scheduled',
                'modified_by' => 'SYSTEM',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 7. Reset state and reload data
            $this->loadAppointments();
            $this->resetAppointmentForm();
            $this->closeAppointmentModal();
            
            // Dispatch event to update dashboard in real-time
            $this->dispatch('appointmentCreated');
            
            // Flash success message
            session()->flash('success', 'Appointment booked successfully for ' . $this->firstName . ' ' . $this->lastName . '!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically displayed by Livewire
            throw $e;
        } catch (\Throwable $th) {
            $this->addError('general', 'Error saving appointment: ' . $th->getMessage());
            Log::error('Appointment Save Error', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
        }
    }

    private function resetAppointmentForm()
    {
        $this->firstName = '';
        $this->lastName = '';
        $this->middleName = '';
        $this->recordNumber = '';
        $this->contactNumber = '';
        $this->birthDate = null;
        $this->selectedService = '';
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->endTime = null;
        $this->searchQuery = '';
        $this->patientSearchResults = [];
        $this->resetValidation();
    }
    public function isSlotOccupied($date, $time)
    {
        // 1. Kunin ang start time ng slot na tinitingnan (e.g., "16:30")
        $slotStart = Carbon::parse($date . ' ' . $time);

        // Convert sa "total minutes from start of day" (e.g., 16:30 -> 16*60 + 30 = 990)
        $slotStartInMinutes = $slotStart->hour * 60 + $slotStart->minute;
        
        // Ang bawat slot ay 30 minutes ang haba
        $slotEndInMinutes = $slotStartInMinutes + 30;

        // 2. Loop sa lahat ng appointments
        foreach ($this->appointments as $appointment) 
        {
            if (Carbon::parse($appointment->start_date)->isSameDay($slotStart)) {

                $existingStart = Carbon::parse($appointment->start_date . ' ' . $appointment->start_time);
                
                // Convert sa "total minutes from start of day"
                $existingStartInMinutes = $existingStart->hour * 60 + $existingStart->minute;
                $existingEndInMinutes = $existingStartInMinutes + $appointment->duration_in_minutes;

                $isOverlapping = (
                    $slotStartInMinutes < $existingEndInMinutes && 
                    $slotEndInMinutes > $existingStartInMinutes
                );

                if ($isOverlapping) {
                    return true; 
                }
            }
        }
        
        return false;
    }

    public function viewAppointment($appointmentId)
    {
        // 1. Fetch the appointment with patient and service details
        $appointment = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(
                'appointments.*',
                'patients.first_name',
                'patients.last_name',
                'patients.middle_name',
                'patients.mobile_number',
                'patients.birth_date', 
                'services.service_name',
                'services.duration'
            )
            ->where('appointments.id', $appointmentId)
            ->first();

        if ($appointment) {
            $this->resetForm(); 

            // 2. Populate the form fields
            $this->firstName = $appointment->first_name;
            $this->lastName = $appointment->last_name;
            $this->middleName = $appointment->middle_name;
            $this->contactNumber = $appointment->mobile_number;
            $this->selectedService = $appointment->service_id;
            $this->viewingAppointmentId = $appointment->id;    
            $this->appointmentStatus = $appointment->status;   

            // 3. Format Dates and Times
            $dt = Carbon::parse($appointment->appointment_date);
            $this->selectedDate = $dt->toDateString();
            $this->selectedTime = $dt->format('H:i:s');

            // Calculate End Time for display
            sscanf($appointment->duration, '%d:%d:%d', $h, $m, $s);
            $durationInMinutes = ($h * 60) + $m;
            $this->endTime = $dt->copy()->addMinutes($durationInMinutes)->format('H:i A');

            // 4. Set mode to Viewing and open modal
            $this->isViewing = true;
            $this->showAppointmentModal = true;
        }
    }
    public function updateStatus($newStatus)
    {
        if ($this->viewingAppointmentId) {
            DB::table('appointments')
                ->where('id', $this->viewingAppointmentId)
                ->update([
                    'status' => $newStatus,
                ]);

            // Refresh the calendar to show changes (e.g. color coding if you add it later)
            $this->loadAppointments();

            // Close the modal
            $this->closeAppointmentModal();

            // Dispatch event to update dashboard in real-time
            $this->dispatch('appointmentUpdated');

            // Optional: Flash a success message
            // session()->flash('message', "Appointment marked as $newStatus");
        }
    }

    // --- SEARCH FUNCTIONALITY ---

    // This runs automatically whenever $searchQuery changes (as you type)
    public function updatedSearchQuery()
    {
        // Don't search if empty or too short
        if (strlen($this->searchQuery) < 2) {
            $this->patientSearchResults = [];
            return;
        }

        // Search by First Name, Last Name, or Mobile Number
        $this->patientSearchResults = DB::table('patients')
            ->select('id', 'first_name', 'last_name', 'middle_name', 'mobile_number', 'birth_date')
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('mobile_number', 'like', '%' . $this->searchQuery . '%');
            })
            ->limit(5)
            ->get();
    }

    // This runs when you click a name from the dropdown
    public function selectPatient($patientId)
    {
        $patient = DB::table('patients')->find($patientId);

        if ($patient) {
            // Auto-fill the form
            $this->firstName = $patient->first_name;
            $this->lastName = $patient->last_name;
            $this->middleName = $patient->middle_name;
            $this->contactNumber = $patient->mobile_number;
            $this->birthDate = $patient->birth_date; // <--- ADD THIS

            // Optional: If you store record_number in DB, map it here too
            // $this->recordNumber = $patient->record_number; 

            // Clear the search so the dropdown disappears
            $this->searchQuery = '';
            $this->patientSearchResults = [];
        }
    }

    public function render()
    {
        return view('livewire.appointment-calendar');
    }
}