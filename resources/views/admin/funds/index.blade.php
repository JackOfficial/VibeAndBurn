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
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Funds</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            {{-- Alert Messages --}}
            @if (Session::has('deleteFundSuccess'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><i class="fas fa-check"></i> Success!</strong> {{ Session::get('deleteFundSuccess') }}
                </div>
            @endif

            <div class="card shadow-sm">
              <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> 
                    {{ number_format($fundsCounter) }} Transactions
                </h3>
                <div class="card-tools">
                    <span class="badge badge-success p-2">Total Earned: ${{ number_format($fundsTotal, 2) }}</span>
                </div>
              </div>

              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed text-nowrap">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @forelse ($funds as $fund)
                    <tr> 
                        {{-- Using $fund->id instead of iteration for better tracking --}}
                        <td>#{{ $fund->id }}</td>
                        
                        {{-- Eloquent Relationship Access --}}
                        <td><strong>{{ $fund->user->name ?? 'Unknown User' }}</strong></td>
                        <td><small class="text-muted">{{ $fund->user->email ?? 'N/A' }}</small></td>
                        
                        <td><span class="badge badge-info">{{ strtoupper($fund->method) }}</span></td>
                        <td><b class="text-success">${{ number_format($fund->amount, 2) }}</b></td>
                        
                        {{-- Carbon Date Formatting --}}
                        <td>{{ $fund->created_at->format('M d, Y H:i') }}</td>
                        
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary" href="#">
                                <i class="fa fa-download"></i> Receipt
                            </a>
                        </td>
                    </tr>   
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p>No fund transactions found.</p>
                        </td>
                    </tr>
                    @endforelse 
                  </tbody>
                </table>
              </div>

              {{-- Pagination Links --}}
              <div class="card-footer clearfix">
                <div class="float-right">
                    {!! $funds->links() !!}
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
</div>

@endsection