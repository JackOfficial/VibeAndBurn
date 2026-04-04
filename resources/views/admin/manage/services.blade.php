@extends('admin.layouts.app')
@section('content')

 <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Services</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
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

            @if (Session::has('deleteServiceSuccess'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong><i class="fas fa-check"></i></strong> {{ Session::get('deleteServiceSuccess') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @elseif(Session::has('deleteServiceFail'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>FAILED:</strong> {{ Session::get('deleteServiceFail') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div> 
            @endif

            <div class="card shadow-sm">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    {{ number_format($servicesCounter) }} Total Services 
                    <livewire:admin.refresh-price-component />
                </h3>
              </div>
              
              <div class="card-body p-0">
                <table class="table table-hover table-sm table-striped mb-0">
                  <thead class="bg-light">
                  <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>API ID</th>
                    <th>Service Details</th>
                    <th>Rate</th>
                    <th>Avg Time</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                   @foreach ($services as $service)
                   <tr> 
                    <td>{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
                    <td>
                        {{ $service->id }}
                        <livewire:admin.mention-component :serviceID="$service->id" :status="$service->status" :key="'mention-'.$service->id" />
                    </td>
                    <td>
                        {{-- Accessing the related category model name --}}
                        <span class="font-weight-bold text-primary">{{ $service->category->category ?? 'Uncategorized' }}</span>
                    </td>
                    <td><code class="text-secondary">{{ $service->serviceId ?? 'MANUAL' }}</code></td>
                    <td>
                        <div class="font-weight-bold">{{ $service->service }}</div>
                        <small class="text-muted">
                            {{ $service->start }} | {{ $service->speed }} | {{ $service->quality }} | {{ $service->refill }}
                        </small>
                        <div class="small mt-1">Min: <b>{{ $service->min_order }}</b> &nbsp; Max: <b>{{ $service->max_order }}</b></div>
                        
                        <div class="mt-1">
                            @php
                                $source = $service->source;
                                $badgeStyle = match($service->source_id) {
                                    2 => 'background-color: #7946E9; color: white;',
                                    3 => 'background-color: #3AE3A4; color: black;',
                                    4 => 'background-color: #007bff; color: white;',
                                    default => 'background-color: #dc3545; color: white;'
                                };
                            @endphp
                            <span class="badge" style="{{ $badgeStyle }}">
                                {{ $source->api_source ?? 'Unknown' }}
                            </span>
                        </div>
                    </td>
                    <td class="{{ ($service->state == 0) ? 'text-danger font-weight-bold' : '' }}">
                        ${{ number_format($service->rate_per_1000, 4) }}
                    </td>
                    <td>{{ $service->Average_completion_time }}</td>
                    <td>
                        <button type="button" class="btn btn-xs btn-outline-info" data-toggle="popover" title="Description" data-content="{{ strip_tags($service->description) }}">View</button>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-xs btn-success" href="{{ route('admin.service.edit', $service->id) }}"><i class="fa fa-edit"></i></a>
                            
                            <a class="btn btn-xs {{ $service->status == 1 ? 'btn-warning' : 'btn-primary' }}" href="{{ route('admin.service.show', $service->id) }}">
                                <i class="fa {{ $service->status == 1 ? 'fa-times' : 'fa-check' }}"></i>
                            </a>

                            @if($service->serviceId)
                                <a class="btn btn-xs btn-secondary" href="{{ url('admin/toggle-service/'.$service->id) }}">
                                    <i class="fas {{ $service->state == 1 ? 'fa-hand-paper' : 'fa-robot' }}"></i>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('admin.service.destroy', $service->id) }}" onsubmit="return confirm('Delete this service?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                  </tr>   
                   @endforeach 
                  </tbody>
                </table>
              </div>

              <div class="card-footer clearfix bg-white">
                <div class="float-right">
                    {{ $services->links() }}
                </div>
                <p class="text-muted small mt-2">Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} services.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection