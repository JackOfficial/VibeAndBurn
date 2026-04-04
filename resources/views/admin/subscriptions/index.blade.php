@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            {{-- This calls your new Livewire component --}}
            <livewire:admin.subscription-component />
        </div>
    </section>
</div>
@endsection