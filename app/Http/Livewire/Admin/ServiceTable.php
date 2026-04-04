<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\service as Service;
use Livewire\WithPagination;

class ServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    // Reset pagination when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->status = $service->status == 1 ? 0 : 1;
        $service->save();
        session()->flash('message', 'Status updated successfully.');
    }

    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('message', 'Service deleted successfully.');
    }

    public function render()
    {
        $services = Service::with('category', 'source')
            ->where('service', 'like', '%' . $this->search . '%')
            ->orWhere('id', 'like', '%' . $this->search . '%')
            ->orWhereHas('category', function($query) {
                $query->where('category', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        $servicesCounter = Service::count();

    return view('livewire.admin.service-table', [
            'services' => $services,
            'servicesCounter' => $servicesCounter
        ]);
    }
}
