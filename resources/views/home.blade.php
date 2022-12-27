@extends('layouts.app')

@section('content')
    <div class="container-fluid" x-cloak x-init="getUserInformation()" x-data="component">
        <div class="row">
            <div class="small-box m-2 p-0 bg-success col-sm-12 col-md-6 col-lg-4">
                <div class="inner">
                    <h3 x-text="(wallet) ? 'R$ ' + parseFloat(wallet.balance).toFixed(2) : ' - '"> R$ 0,00</h3>

                    <p>Meu saldo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver extrato <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <hr>

        <div class="row mx-1">
            <div class="card mb-2 col-sm-6 col-md-4 col-lg-3" data-symbol="AAPL">
                <!-- Step 3: Use the Bootstrap 4 card component to display the stock information -->
                <div class="card-header d-flex justify-content-between align-items-baseline pb-0">
                    <h3>Apple Inc.</h3>
                    <p class="symbol">AAPL</p>
                </div>
                <div class="card-body d-flex justify-content-between pb-0">
                    <p class="fw-bold fs-3">$120.50</p>
                    <p class="text-success">+2.50 (+2.10%)</p>
                </div>
            </div>
        </div>


    </div>


@endsection

@push('page_scripts')
<script>

    const component = {
        wallet: null,
        stocks: [],

        async getUserInformation() {
            await axios.get('{{ route("users.information") }}')
                .then((response) => {
                    this.wallet = response.data.wallet
                    this.stocks = response.data.stocks
                })
        },

    }
</script>
@endpush
