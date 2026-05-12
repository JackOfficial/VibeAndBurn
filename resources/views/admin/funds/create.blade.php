@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper" x-data="{ 
    money: 0, 
    currency: 'RWF', 
    rate: {{ $bifRate }},
    get converted() {
        if (this.currency === 'BIF') {
            return (this.money / this.rate).toFixed(2);
        }
        return parseFloat(this.money).toFixed(2);
    }
}">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-md-9 col-sm-12">
                    
                    {{-- Alert Messages --}}
                    @if (Session::has('adminAddFundSuccess'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('adminAddFundSuccess') }}
                        </div>
                    @elseif(Session::has('adminAddFundFail'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('adminAddFundFail') }}
                        </div>
                    @endif

                    <div class="card card-warning card-outline shadow">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-wallet mr-2 text-warning"></i> Deposit Funds to User Account
                            </h3>
                        </div>

                        <form method="POST" action="{{ route('admin.funds.store') }}" id="fundForm">
                            @csrf
                            <div class="card-body">
                                
                                {{-- User Selection with AJAX Select2 --}}
                                <div class="form-group">
                                    <label for="user">Target User <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('user') is-invalid @enderror" 
                                            name="user" id="user" required style="width: 100%;">
                                        <option value="">Search user by name or email...</option>
                                        {{-- Options will be loaded via AJAX --}}
                                    </select>
                                    @error('user')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Currency & Amount --}}
                                <label for="money">Transaction Amount <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-control bg-light font-weight-bold" 
                                                name="currency" 
                                                x-model="currency"
                                                style="border-radius: 4px 0 0 4px; border-right: 0;">
                                            <option value="RWF">RWF</option>
                                            <option value="USD">USD</option>
                                            <option value="BIF">BIF</option>
                                            {{-- Rest of your currencies --}}
                                        </select>
                                    </div>
                                    <input type="number" 
                                           step="0.01" 
                                           name="money" 
                                           id="moneyAmount"
                                           x-model="money"
                                           class="form-control @error('money') is-invalid @enderror" 
                                           placeholder="0.00" 
                                           required />
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white"><i class="fas fa-coins text-warning"></i></span>
                                    </div>
                                </div>

                                {{-- Real-time Conversion Alert (Alpine.js) --}}
                                <template x-if="money > 0">
                                    <div class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn">
                                        <i class="fas fa-calculator mr-2"></i>
                                        Final Wallet Addition: <strong x-text="converted"></strong> USD
                                        <span x-show="currency === 'BIF'" class="small ml-1">(Rate: 1 USD = <span x-text="rate"></span> BIF)</span>
                                    </div>
                                </template>

                                <div class="callout callout-info mt-4">
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        This action will immediately update the user's balance.
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <button type="submit" class="btn btn-warning btn-lg btn-block font-weight-bold shadow-sm" onclick="return confirmAddition()">
                                    <i class="fa fa-plus-circle mr-1"></i> Complete Deposit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Updated Select2 with AJAX to prevent lag
        $('#user').select2({
            theme: 'bootstrap4',
            placeholder: "Search user by name or email...",
            allowClear: true,
            ajax: {
                url: "{{ route('admin.users.search') }}", // Ensure this route is defined
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 2
        });
    });

    function confirmAddition() {
        const amount = document.getElementById('moneyAmount').value;
        const user = $("#user option:selected").text().trim();
        const currency = $('select[name="currency"]').val();
        
        if(amount > 0 && user !== "") {
            return confirm(`Are you sure you want to add ${amount} ${currency} to: ${user}?`);
        }
        return true;
    }
</script>

<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
    .card-warning.card-outline {
        border-top: 3px solid #ffc107;
    }
    #moneyAmount {
        font-size: 1.25rem;
        font-weight: bold;
    }
</style>
@endsection