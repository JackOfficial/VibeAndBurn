<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Service; // Ensure Model is Capitalized

class MentionComponent extends Component
{
    public $serviceID;
    public $status; // This will track the state in real-time

    public function mount($serviceID, $status)
    {
        $this->serviceID = $serviceID;
        $this->status = $status;
    }

    public function mention()
    {
        // 1. Determine the new status logic
        // If it's active (1) or disabled (0), set to mentioned (2). 
        // If it's already mentioned (2), set back to active (1).
        $newStatus = ($this->status == 2) ? 1 : 2;

        // 2. Perform the update
        $updated = Service::where('id', $this->serviceID)->update([
            'status' => $newStatus
        ]);

        if ($updated) {
            // 3. Update the local variable so the UI changes immediately
            $this->status = $newStatus;

            $this->dispatchBrowserEvent('toastr:success', [
                'message' => ($newStatus == 2) ? 'Service Mentioned!' : 'Mention Removed!',
            ]);
        } else {
            $this->dispatchBrowserEvent('toastr:error', [ // Use error for failures
                'message' => 'Could not update status!',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.mention-component');
    }
}