@extends('layouts.app')

@section('content')

<div x-init="getAllStocks()" x-data="component">

    <div class="container-fluid bg-success my-4 p-3 shadow-md rounded">
        <h3>Lista de Papéis disponíveis</h3>
    </div>

    <div class="row">
        <template x-for="stock in stocks">
            <div class="card col-sm-12 col-md-6 col-lg-4 mx-1">
                <div class="card-header row">
                    <div class="col-2 d-none d-md-block">
                        <img :src="stock.logo" class="rounded mr-3" alt="Stock logo" style="max-width: 56px">
                    </div>
                    <div class="col-10">
                        <div class="d-flex justify-content-between mb-1">
                            <h5 class="card-title mr-3" x-text="stock.stock"></h5>
                            <h4 class="card-text font-weight-bold text-success mr-3" x-text="'R$ ' + stock.close.toFixed(2)"></h4>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="card-text mr-3" x-text="stock.name"></span>
                            <span class="card-text mr-3" x-text="'Setor: ' + stock.sector"></span>
                            <button class="btn btn-success btn-sm px-1 py-0">Comprar</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

</div>

@endsection

@push('page_scripts')
<script>

    const component = {
        stocks: [],

        getAllStocks() {
            axios.get('{{ route("stocks.list") }}')
                .then((response) => {
                    if (response.data) {
                        this.stocks = response.data
                    }
                })
        },
    }
</script>
@endpush
