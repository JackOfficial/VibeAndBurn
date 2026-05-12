<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\service as Service;
use App\Models\category as Category;
use App\Models\Source; // Imported for provider filtering
use App\Models\Rate;
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
        // Eager load everything to keep the UI fast
        $services = Service::with(['category.socialmedia', 'source'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $term = '%' . $this->search . '%';

                    // --- Table Columns ---
                    $q->where('service', 'like', $term)
                      ->orWhere('id', 'like', $term)
                      ->orWhere('serviceId', 'like', $term)
                      ->orWhere('rate_per_1000', 'like', $term)
                      ->orWhere('min_order', 'like', $term)
                      ->orWhere('max_order', 'like', $term)
                      ->orWhere('quality', 'like', $term)
                      ->orWhere('description', 'like', $term)
                      ->orWhere('Average_completion_time', 'like', $term)
                      ->orWhere('start', 'like', $term)
                      ->orWhere('speed', 'like', $term)
                      ->orWhere('refill', 'like', $term)
                      ->orWhere('state', 'like', $term);

                    // --- Relationship: Category Name ---
                    $q->orWhereHas('category', function($cat) use ($term) {
                        $cat->where('category', 'like', $term);
                    });

                    // --- Relationship: Social Media Platform ---
                    $q->orWhereHas('category.socialmedia', function($sm) use ($term) {
                        $sm->where('socialmedia', 'like', $term);
                    });

                    // --- Relationship: Source API Name ---
                    $q->orWhereHas('source', function($src) use ($term) {
                        $src->where('api_source', 'like', $term);
                    });
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
            ->paginate(20);

        return view('livewire.admin.service-table', [
            'services' => $services,
            'categories' => Category::orderBy('category', 'asc')->get(),
            'sources' => Source::all(),
            'servicesCounter' => Service::count(),
            'rate' => Rate::first()
        ]);
    }
}