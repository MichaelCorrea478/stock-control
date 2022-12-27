@extends('layouts.app')

@section('content')

<div x-init="getAllStocks()" x-data="component">

    <div class="container-fluid bg-success my-4 p-3 shadow-md rounded">
        <h3>Lista de Papéis disponíveis</h3>
    </div>

    <div class="card">
        <div class="card-header row">
            <div class="col-1 d-none d-md-block">
                <img src="https://s3-symbol-logo.tradingview.com/lockheed-martin--big.svg" class="rounded mr-3" alt="Stock logo">
            </div>
            <div class="col-11">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title mr-3">LMTB34</h5>
                    <span class="card-text mr-3">Close: $2569.28</span>
                </div>
                <div class="d-flex justify-content-start">
                    <span class="card-text mr-3">LOCKHEED DRN</span>
                <span class="card-text mr-3">Sector: Electronic Technology</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('page_scripts')
<script>

    const component = {
        stocks: [],

        getAllStocks() {

        },
    }
</script>
@endpush
