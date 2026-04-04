<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\service as Service; // Ensure the case matches your Model file (Service vs service)
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;

class ServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    /**
     * Reset pagination when searching to avoid "No results found" 
     * on hidden pages.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Handles the "Mention" logic (Status 2)
     */
    public function toggleMention($id)
    {
        $service = Service::findOrFail($id);

        if ($service->status == 0) {
            $this->dispatchBrowserEvent('toastr:error', [
                'message' => 'Cannot mention a disabled service!',
            ]);
            return;
        }

        // Logic: If currently Mentioned (2), set to Active (1). Otherwise, set to 2.
        $newStatus = ($service->status == 2) ? 1 : 2;
        
        $service->update(['status' => $newStatus]);

        $this->dispatchBrowserEvent('toastr:success', [
            'message' => ($newStatus == 2) ? 'Service Mentioned!' : 'Mention Removed!',
        ]);
    }

    /**
     * Toggles visibility (Active vs Disabled)
     */
    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        
        // If it is 1 (Active) or 2 (Mentioned), we disable it (0).
        // If it is 0 (Disabled), we enable it (1).
        $newStatus = ($service->status == 1 || $service->status == 2) ? 0 : 1;
        
        $service->update(['status' => $newStatus]);

        $this->dispatchBrowserEvent('toastr:success', [
            'message' => 'Service visibility updated!',
        ]);
    }

    /**
     * Runs the price update command via Artisan
     */
    public function refreshBtn()
    {
        Artisan::call('update:price');
        $feedback = Artisan::output();

        $this->dispatchBrowserEvent('toastr:success', [
            'message' => $feedback ?: 'Prices updated successfully!',
        ]);

        // In Livewire 2, we use emit to notify other components if needed
        $this->emit('serviceTableUpdated');
    }

    /**
     * Deletes a service record
     */
    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        
        $this->dispatchBrowserEvent('toastr:success', [
            'message' => 'Service deleted successfully.',
        ]);
    }

    public function render()
    {
        // Grouped search query to keep OR logic clean
        $services = Service::with('category', 'source')
            ->where(function($query) {
                $query->where('service', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function($q) {
                          $q->where('category', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.service-table', [
            'services' => $services,
            'servicesCounter' => Service::count()
        ]);
    }
}