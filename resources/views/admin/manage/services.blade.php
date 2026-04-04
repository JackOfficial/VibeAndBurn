@extends('admin.layouts.app')
@section('content')

 <div class="content-wrapper" x-data="{ 
    search: '',
    items: [],
    init() {
        // We capture the row data on load to filter instantly
        this.items = Array.from($refs.tableBody.querySelectorAll('tr'));
    },
    filterTable() {
        this.items.forEach(tr => {
            tr.style.display = tr.innerText.toLowerCase().includes(this.search.toLowerCase()) ? '' : 'none';
        });
    }
 }">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-6">
            <h1 class="font-weight-bold">Services Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right text-sm">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Services</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">

            {{-- Alert Section --}}
            @if (Session::has('deleteServiceSuccess') || Session::has('deleteServiceFail'))
                <div class="alert {{ Session::has('deleteServiceSuccess') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show shadow-sm border-0" role="alert">
                  <i class="fas {{ Session::has('deleteServiceSuccess') ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-2"></i>
                  {{ Session::get('deleteServiceSuccess') ?? Session::get('deleteServiceFail') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            @endif

            <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
              <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-list-ul mr-2 text-primary"></i>
                            {{ number_format($servicesCounter) }} Total Services 
                        </h3>
                    </div>
                    <div class="col-md-4 text-center">
                         <livewire:admin.refresh-price-component />
                    </div>
                    {{-- Alpine Search Bar --}}
                    <div class="col-md-4">
                        <div class="input-group input-group-sm shadow-sm" style="border-radius: 20px; overflow: hidden; border: 1px solid #e0e0e0;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" 
                                   x-model="search" 
                                   @input="filterTable()"
                                   class="form-control border-0 py-4" 
                                   placeholder="Quick search on this page...">
                        </div>
                    </div>
                </div>
              </div>
              
              <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light text-uppercase text-muted small">
                  <tr>
                    <th class="pl-4">#</th>
                    <th>ID / Status</th>
                    <th>Category</th>
                    <th>Details</th>
                    <th>Pricing</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody x-ref="tableBody">
                   @foreach ($services as $service)
                   <tr class="align-middle"> 
                    <td class="pl-4 font-weight-bold text-muted">{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-light border px-2 py-1 mr-2">#{{ $service->id }}</span>
                            <livewire:admin.mention-component :serviceID="$service->id" :status="$service->status" :key="'mention-'.$service->id" />
                        </div>
                        <div class="mt-2">
                             <code class="small px-2 py-1 bg-light rounded text-secondary">API ID: {{ $service->serviceId ?? 'Manual' }}</code>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            {{-- Placeholder for Social Media Icon based on your previous data --}}
                            <div class="mr-2 rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fas fa-share-alt text-primary small"></i>
                            </div>
                            <span class="font-weight-bold text-dark">{{ $service->category->category ?? 'Uncategorized' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="font-weight-bold text-truncate" style="max-width: 250px;">{{ $service->service }}</div>
                        <div class="text-muted text-xs mt-1">
                             <span class="mr-2"><i class="far fa-clock mr-1"></i> {{ $service->start }}</span>
                             <span><i class="fas fa-bolt mr-1"></i> {{ $service->speed }}</span>
                        </div>
                        
                        <div class="mt-2">
                            @php
                                $badgeStyle = match($service->source_id) {
                                    2 => 'bg-purple',
                                    3 => 'bg-teal',
                                    4 => 'bg-blue',
                                    default => 'bg-gray'
                                };
                            @endphp
                            <span class="badge {{ $badgeStyle }}-soft text-uppercase px-2" style="font-size: 10px; letter-spacing: 0.5px;">
                                {{ $service->source->api_source ?? 'Direct' }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark font-weight-bold" style="font-size: 1.1rem;">
                            ${{ number_format($service->rate_per_1000, 4) }}
                        </div>
                        <div class="small text-muted">Per 1k orders</div>
                        @if($service->state == 0)
                            <span class="badge badge-danger-soft mt-1">Maintenance</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                            <a title="Edit" class="btn btn-sm btn-white text-success border-right" href="{{ route('admin.service.edit', $service->id) }}"><i class="fa fa-edit"></i></a>
                            
                            <a title="{{ $service->status == 1 ? 'Disable' : 'Enable' }}" class="btn btn-sm btn-white {{ $service->status == 1 ? 'text-warning' : 'text-primary' }} border-right" href="{{ route('admin.service.show', $service->id) }}">
                                <i class="fa {{ $service->status == 1 ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </a>

                            @if($service->serviceId)
                                <a title="Toggle Auto/Manual" class="btn btn-sm btn-white text-secondary border-right" href="{{ url('admin/toggle-service/'.$service->id) }}">
                                    <i class="fas {{ $service->state == 1 ? 'fa-robot' : 'fa-hand-paper' }}"></i>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('admin.service.destroy', $service->id) }}" onsubmit="return confirm('Delete this service?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button title="Delete" type="submit" class="btn btn-sm btn-white text-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                  </tr>   
                   @endforeach 
                  </tbody>
                </table>
              </div>

              <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted small mb-0">
                        Showing <b>{{ $services->firstItem() }}</b> to <b>{{ $services->lastItem() }}</b> of {{ $services->total() }} results.
                    </p>
                    <div class="pagination-sm">
                        {{ $services->links() }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <style>
    /* Professional SMM Panel UI Enhancements */
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .bg-purple-soft { background-color: #f3e8ff; color: #7946E9; }
    .bg-teal-soft { background-color: #e6fffa; color: #319795; }
    .bg-blue-soft { background-color: #ebf8ff; color: #3182ce; }
    .bg-gray-soft { background-color: #f7fafc; color: #4a5568; }
    .badge-danger-soft { background-color: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }
    
    .table thead th { border-top: none; letter-spacing: 1px; font-weight: 600; }
    .table td { vertical-align: middle !important; padding: 1rem 0.75rem; }
    
    .btn-white { background: #fff; border: 1px solid #f0f0f0; }
    .btn-white:hover { background: #f8f9fa; }
    
    [x-cloak] { display: none !important; }
    
    /* Smooth transition for Alpine filtering */
    tr { transition: opacity 0.2s ease-in-out; }
  </style>

@endsection