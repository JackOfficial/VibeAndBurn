<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\service as Service; // Ensure the case matches your Model file
use App\Models\category as Category; // Imported to populate the filter dropdown
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;

class ServiceTable extends Component
{
    use WithPagination;

    // Filter Properties
    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';

    protected $paginationTheme = 'bootstrap';

    /**
     * Reset pagination when any filter changes
     */
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCategory() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    /**
     * Clear all filters at once
     */
    public function resetFilters()
    {
        $this->reset(['search', 'filterCategory', 'filterStatus']);
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
        $newStatus = ($service->status > 0) ? 0 : 1;
        
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
        $services = Service::with('category', 'source')
            // Apply Search
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('service', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function($catQ) {
                          $catQ->where('category', 'like', '%' . $this->search . '%');
                      });
                });
            })
            // Apply Category Filter
            ->when($this->filterCategory, function($query) {
                $query->where('category_id', $this->filterCategory);
            })
            // Apply Status Filter (Check for exact string match because 0 is a valid status)
            ->when($this->filterStatus !== '', function($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.service-table', [
            'services' => $services,
            'categories' => Category::orderBy('category', 'asc')->get(),
            'servicesCounter' => Service::count()
        ]);
    }
}