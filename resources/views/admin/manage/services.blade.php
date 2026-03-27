@extends('admin.layouts.app')
@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">

            @if (Session::has('deleteServiceSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('deleteServiceSuccess') }} </div>
          @elseif(Session::has('deleteServiceFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('deleteServiceFail') }} </div> 
            @endif

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $servicesCounter }} Services <livewire:admin.refresh-price-component /></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-sm table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Category</th>
                     <th>Service ID</th>
                    <th>Service</th>
                    <th>Rate Per 1000</th>
                    <th>Average Time</th>
                    <th>Description</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @foreach ($services as $service)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->id }}
                    <livewire:admin.mention-component :serviceID="$service->id" :status="$service->status" />
                    </td>
                    <td>{{ $service->category }}</td>
                     <td>{{ $service->serviceId ?? 'No API' }}</td>
                    <td>
                        {{ $service->service }} | {{ $service->start }} | {{ $service->speed }} | {{ $service->quality }} | {{ $service->refill }}
                        <div>Min order: {{ $service->min_order }} &nbsp; Max order: {{ $service->max_order }}</div>
                        <div>
                            @if($service->source_id == 2)
                        <span class="badge" style="background-color: #7946E9;"><a href="https://bulkfollows.org" target="_blank" style="color:white">{{ $service->api_source }}</a></span>
                        @elseif($service->source_id == 3)
                        <span class="badge" style="background-color: #3AE3A4;"><a href="https://amazingsmm.com" target="_blank" style="color:white">{{ $service->api_source }}</a></span>
                        @elseif($service->source_id == 4)
                        <span class="badge badge-primary"><a href="https://bulkmedya.org/" target="_blank" style="color:white">{{ $service->api_source }}</a></span>
                        @else
                        <span class="badge badge-danger">{{ $service->api_source }}</span>
                        @endif
                        </div>
                        </td>
                    <td class="{{ ($service->state == 0) ? 'text-danger' : '' }}">{{ $service->rate_per_1000 }}</td>
                    <td>{{ $service->Average_completion_time }}</td>
                    <td>{!! $service->description !!}</td>
                    <td>{{ $service->created_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.service.destroy', $service->id) }}">
                          @csrf
                            @method('DELETE')
                            <a class="btn btn-sm btn-success m-1" href="{{ route('admin.service.edit', $service->id) }}"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                            @if($service->status == 1)
                            <a class="btn btn-sm btn-warning m-1" href="{{ route('admin.service.show', $service->id) }}"><i class="fa fa-times"></i> Disable</a>&nbsp;
                            @else
                            <a class="btn btn-sm btn-warning m-1" href="{{ route('admin.service.show', $service->id) }}"><i class="fa fa-check"></i> Enable</a>&nbsp;
                            @endif
                            <a class="btn btn-sm btn-secondary m-1 {{ ($service->serviceId == "" || $service->serviceId == null) ? 'd-none' : '' }}" href="admin/toggle-service/{{$service->id}}"><i class="fa fa-edit"></i> {{ ($service->state == 1) ? "Set Manual" : "Set Auto" }} </a>
                           <button type="submit" class="btn btn-sm btn-danger"><span><i class="fa fa-trash"></i></span> Delete</button>
                        </form>
                        </td>
                  </tr>   
                   @endforeach 
    
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Category</th>
                     <th>Service ID</th>
                    <th>Service</th>
                    <th>Rate Per 1000</th>
                    <th>Average Time</th>
                    <th>Description</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection