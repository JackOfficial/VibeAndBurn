@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        </section>

    <section class="content">
        <div class="container-fluid">
            <livewire:admin.service-table />
        </div>
    </section>
</div>
@endsection