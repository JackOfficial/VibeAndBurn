@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid pt-3">
            @livewire('admin.user-management')
        </div>
    </section>
</div>
@endsection