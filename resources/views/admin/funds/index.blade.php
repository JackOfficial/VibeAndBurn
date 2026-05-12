@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Funds Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Funds</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
     <livewire:admin.funds.index-component />
    </section>
</div>

@endsection