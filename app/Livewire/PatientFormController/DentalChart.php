<?php
namespace App\Livewire\PatientFormController;

use Livewire\Component;
use Livewire\Attributes\On;

class DentalChart extends Component
{
    public $teeth = [];
    public $selectedTooth = null;
    public $selectedSurface = null;

    public function mount($data = [])
    {
        // Initialize all 32 teeth with 5 surfaces each (top, right, bottom, left, center)
        for ($i = 11; $i <= 48; $i++) {
            // Skip invalid numbers
            if (in_array($i % 10, [4, 5, 9])) continue;
            if ($i > 38 && $i < 41) continue;
            
            $this->teeth[$i] = [
                'top' => 'white',
                'right' => 'white',
                'bottom' => 'white',
                'left' => 'white',
                'center' => 'white',
            ];
        }

        if (!empty($data)) {
            $this->teeth = array_merge($this->teeth, $data);
        }
    }

    public function toggleSurface($tooth, $surface)
    {
        if (!isset($this->teeth[$tooth])) {
            return;
        }

        $colors = ['white', 'red', 'blue', 'green'];
        $currentColor = $this->teeth[$tooth][$surface] ?? 'white';
        $currentIndex = array_search($currentColor, $colors);
        $nextIndex = ($currentIndex + 1) % count($colors);
        
        $this->teeth[$tooth][$surface] = $colors[$nextIndex];
        
        // Dispatch event to parent component
        $this->dispatch('dentalChartUpdated', teeth: $this->teeth);
    }

    #[On('resetDentalChart')]
    public function resetChart()
    {
        for ($i = 11; $i <= 48; $i++) {
            if (isset($this->teeth[$i])) {
                foreach ($this->teeth[$i] as &$surface) {
                    $surface = 'white';
                }
            }
        }
    }

    public function render() {
        return view('livewire.PatientFormViews.dental-chart', [
            'teeth' => $this->teeth
        ]);
    }
}