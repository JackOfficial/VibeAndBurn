@extends('admin.layouts.app')

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

                        <form method="POST" action="{{ route('admin.clientOrders.store') }}" id="fundForm">
                            @csrf
                            <div class="card-body">
                                
                                {{-- User Selection with Select2 --}}
                                <div class="form-group">
                                    <label for="user">Target User <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('user') is-invalid @enderror" 
                                            name="user" id="user" required style="width: 100%;">
                                        <option value="">Search user by name or email...</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" data-name="{{ $user->name }}">
                                                {{ ucfirst($user->name) }} — ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Currency & Amount --}}
                                <label for="money">Transaction Amount <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-control bg-light font-weight-bold" name="currency" style="border-radius: 4px 0 0 4px; border-right: 0;">
                                            <option value="RWF" selected>RWF</option>
                                           <option value="USD">USD</option>
                <option value="ARS">ARS</option>
                <option value="AUD">AUD</option>
                <option value="BIF">BIF</option>
                <option value="BGN">BGN</option>
                <option value="BRL">BRL</option>
                <option value="BWP">BWP</option>
                <option value="CAD">CAD</option>
                <option value="CFA">CFA</option>
                <option value="CHF">CHF</option>
                <option value="CNY">CNY</option>
                <option value="COP">COP</option>
                <option value="CRC">CRC</option>
                <option value="CZK">CZK</option>
                <option value="DKK">DKK</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
                <option value="GHS">GHS</option>
                <option value="HKD">HKD</option>
                <option value="HUF">HUF</option>
                <option value="ILS">ILS</option>
                <option value="INR">INR</option>
                <option value="JPY">JPY</option>
                <option value="KES">KES</option>
                <option value="MAD">MAD</option>
                <option value="MOP">MOP</option>
                <option value="MUR">MUR</option>
                <option value="MWK">MWK</option>
                <option value="MXN">MXN</option>
                <option value="MYR">MYR</option>
                <option value="NGN">NGN</option>
                <option value="NOK">NOK</option>
                <option value="NZD">NZD</option>
                <option value="PEN">PEN</option>
                <option value="PHP">PHP</option>
                <option value="PLN">PLN</option>
                <option value="RUB">RUB</option>
                <option value="SAR">SAR</option>
                <option value="SEK">SEK</option>
                <option value="SGD">SGD</option>
                <option value="SLL">SLL</option>
                <option value="THB">THB</option>
                <option value="TRY">TRY</option>
                <option value="TWD">TWD</option>
                <option value="TZS">TZS</option>
                <option value="UGX">UGX</option>
                <option value="VEF">VEF</option>
                <option value="XAF">XAF</option>
                <option value="XOF">XOF</option>
                <option value="ZAR">ZAR</option>
                <option value="ZMK">ZMK</option>
                <option value="ZMW">ZMW</option>
                <option value="ZWD">ZWD</option>
                                        </select>
                                    </div>
                                    {{-- Use type="number" with step="0.01" for money --}}
                                    <input type="number" 
                                           step="0.01" 
                                           name="money" 
                                           id="moneyAmount"
                                           class="form-control form-control-lg @error('money') is-invalid @enderror" 
                                           placeholder="0.00" 
                                           required />
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white"><i class="fas fa-coins text-warning"></i></span>
                                    </div>
                                </div>
                                @error('money')
                                    <span class="text-danger small"><strong>{{ $message }}</strong></span>
                                @enderror

                                <div class="callout callout-info mt-4">
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        This action will immediately update the user's balance. Ensure the correct user is selected.
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

                {{-- Side Info/Stats (Optional) --}}
                <div class="col-lg-5 col-md-3 d-none d-lg-block">
                    <div class="info-box bg-gradient-warning shadow">
                        <span class="info-box-icon"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Users</span>
                            <span class="info-box-number">{{ count($users) }} Registered Clients</span>
                        </div>
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
        // Initialize Select2 for better user searching
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Type to search...",
            allowClear: true
        });
    });

    // Added a simple confirmation to prevent misclicks
    function confirmAddition() {
        const amount = document.getElementById('moneyAmount').value;
        const user = $("#user option:selected").text().trim();
        
        if(amount > 0 && user !== "") {
            return confirm(`Are you sure you want to add ${amount} to account: ${user}?`);
        }
        return true;
    }
</script>

<style>
    /* Professional tweaks */
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
    .input-group-text {
        border-left: 0;
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