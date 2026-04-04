<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\service as Service;
use App\Models\category as Category;
use App\Models\Source; // Imported for provider filtering
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;

class ServiceTable extends Component
{
    use WithPagination;

    // Filter Properties
    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    public $filterSource = ''; // Added to filter by Provider/API Source

    protected $paginationTheme = 'bootstrap';

    /**
     * Reset pagination when any filter changes
     */
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCategory() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterSource() { $this->resetPage(); }

    /**
     * Clear all filters at once
     */
    public function resetFilters()
    {
        $this->reset(['search', 'filterCategory', 'filterStatus', 'filterSource']);
        $this->resetPage();
    }

    public function toggleMention($id)
    {
        $service = Service::findOrFail($id);
        if ($service->status == 0) {
            $this->dispatchBrowserEvent('toastr:error', ['message' => 'Cannot mention a disabled service!']);
            return;
        }
        $newStatus = ($service->status == 2) ? 1 : 2;
        $service->update(['status' => $newStatus]);
        $this->dispatchBrowserEvent('toastr:success', ['message' => ($newStatus == 2) ? 'Service Mentioned!' : 'Mention Removed!']);
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $newStatus = ($service->status > 0) ? 0 : 1;
        $service->update(['status' => $newStatus]);
        $this->dispatchBrowserEvent('toastr:success', ['message' => 'Service visibility updated!']);
    }

    public function refreshBtn()
    {
        Artisan::call('update:price');
        $this->dispatchBrowserEvent('toastr:success', ['message' => Artisan::output() ?: 'Prices updated!']);
        $this->emit('serviceTableUpdated');
    }

    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('toastr:success', ['message' => 'Service deleted.']);
    }

    public function render()
    {
        $services = Service::with(['category', 'source'])
            // Enhanced Search: checks ID, Name, and API Service ID
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('service', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%')
                      ->orWhere('serviceId', 'like', '%' . $this->search . '%');
                });
            })
            // Category Filter
            ->when($this->filterCategory, function($query) {
                $query->where('category_id', $this->filterCategory);
            })
            // Provider/Source Filter
            ->when($this->filterSource, function($query) {
                $query->where('source_id', $this->filterSource);
            })
            // Status Filter
            ->when($this->filterStatus !== '', function($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(20); // Increased per-page for better management

        return view('livewire.admin.service-table', [
            'services' => $services,
            'categories' => Category::orderBy('category', 'asc')->get(),
            'sources' => Source::all(), // For the provider filter dropdown
            'servicesCounter' => Service::count()
        ]);
    }
}