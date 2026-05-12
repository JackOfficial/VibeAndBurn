@extends('admin.layouts.app')

@push('styles')
<style>
    /* Cleaned up styles - only keeping what the Livewire component needs */
    .card-warning.card-outline {
        border-top: 3px solid #ffc107;
    }
    
    /* Ensure the number input stands out */
    input[type="number"] {
        font-size: 1.25rem;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    {{-- Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">Financial Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item active">Add Fund</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="content">
        <livewire:admin.funds.create-component />
    </section>
</div>
@endsection