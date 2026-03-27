@extends('admin.layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Edit Service</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
            <!-- general form elements -->

            @if (Session::has('updateServiceSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('updateServiceSuccess') }} </div>
          @elseif(Session::has('updateServiceFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('updateServiceFail') }} </div> 
            @endif

           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-edit"></i> Edit Service</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{ route('admin.service.update', $service->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
               
                <div class="card-body">
              <div class="row">
<div class="col-md-6">
  <div class="form-group">
    <label for="category">Category</label>
    <select name="category" class="form-control @error('category') is-invalid @enderror" required>
     @foreach ($categories as $category)
     <option value="{{ $category->id }}" {{ ($service->category_id == $category->id) ? 'selected' : '' }}>{{ $category->socialmedia }} ({{ $category->category }})</option>    
     @endforeach
   </select>
   @error('category')
                   <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                      </span>
               @enderror 
 </div>

<div class="row">
   <div class="col-md-6">
        <div class="form-group">
  <label for="serviceId">Service ID</label>
  <input type="text" value="{{ $service->serviceId }}" class="form-control @error('serviceId') is-invalid @enderror" id="serviceId" name="serviceId" placeholder="Enter service ID" />
  @error('serviceId')
  <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
     </span>
@enderror  
</div>
   </div> 
   <div class="col-md-6">
         <div class="form-group">
  <label for="speed">rate_per_1000</label>
  <input type="text" value="{{ $service->rate_per_1000 }}" class="form-control @error('rateper1000') is-invalid @enderror" id="rateper1000" name="rateper1000" placeholder="Rate Per 1000" required>
  @error('rateper1000')
  <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
     </span>
@enderror  
</div>
   </div> 
</div>

  <div class="form-group">
    <label for="start">Start</label>
    <input type="text" value="{{ $service->start }}" class="form-control @error('start') is-invalid @enderror" id="start" name="start" placeholder="Start">
    @error('start')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>
 
 <div class="row">
   <div class="col-md-6">
       <div class="form-group">
    <label for="min_order">Min Order</label>
    <input type="text" value="{{ $service->min_order }}" class="form-control @error('min_order') is-invalid @enderror" id="min_order" name="min_order" placeholder="Set The Minimum Order">
    @error('min_order')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>
   </div> 
   <div class="col-md-6">
       <div class="form-group">
    <label for="max_order">Max Order</label>
    <input type="text" min="1" value="{{ $service->max_order }}" class="form-control @error('max_order') is-invalid @enderror" id="max_order" name="max_order" placeholder="Enter max_order" required>
    @error('max_order')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>
   </div> 
</div>

  <div class="form-group">
    <label for="quality">Quality</label>
    <input type="text" value="{{ $service->quality }}" class="form-control @error('quality') is-invalid @enderror" id="quality" name="quality" placeholder="Quality">
    @error('quality')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>
 

</div>
<div class="col-md-6">
  <div class="form-group">
  <label for="service">service</label>
  <input type="text" value="{{ $service->service }}" class="form-control @error('service') is-invalid @enderror" id="service" name="service" placeholder="Enter a service" required>
  @error('service')
  <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
     </span>
@enderror  
</div>

<div class="form-group">
    <label for="source">Source</label>
   <select class="form-control @error('source') is-invalid @enderror" id="source" name="source" required>
       @foreach($sources as $source)
       <option value="{{ $source->id }}" {{ ($source->id == $service->source_id) ? 'selected' : '' }}>{{ $source->api_source }}</option>
       @endforeach
   </select>
    @error('source')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>


<div class="form-group">
    <label for="average_completion_time">Average completition time</label>
    <input type="text" value="{{ $service->Average_completion_time }}" class="form-control @error('average_completion_time') is-invalid @enderror" id="average_completion_time" name="average_completion_time" placeholder="Enter average completion time">
    @error('average_completion_time')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>

  <div class="form-group">
    <label for="speed">Speed</label>
    <input type="text" value="{{ $service->speed }}" class="form-control @error('speed') is-invalid @enderror" id="speed" name="speed" placeholder="Enter Speed">
    @error('speed')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>

  <div class="form-group">
    <label for="refill">Refill</label>
    <input type="text" value="{{ $service->refill }}" class="form-control @error('refill') is-invalid @enderror" id="refill" name="refill" placeholder="Enter refill">
    @error('refill')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
       </span>
  @enderror  
  </div>

</div>
              </div>

              <div class="row">
                <div class="col-md-12">

                    

                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" >{{ $service->description }}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                       </span>
                @enderror
                </div> 
                </div>
              </div>
            
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning"><span><i class="fa fa-edit"></i> Update</span></button>
                </div>
              </form>
            </div>
            <!-- /.card -->

         
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection