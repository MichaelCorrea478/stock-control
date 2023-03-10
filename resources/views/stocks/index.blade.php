@extends('layouts.app')

@section('content')
    <div x-init="
        getAllStocks(),
        $watch('buy.quantity', qty => buy.value = buy.stock.close * qty)
    " x-data="component" class="px-3">

        <div class="container-fluid bg-success my-4 p-3 shadow-md rounded">
            <h3>Lista de Ativos disponíveis</h3>
        </div>

        <div class="row">
            <div class="col-4 d-flex justify-content-start py-2 px-4">
                <div class="input-group input-group-sm w-50">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar" x-on:keyup.enter="filterStocks()" x-model="searchValue">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default" x-on:click="filterStocks()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-8 d-flex justify-content-between py-2 px-4">
                <button class="btn btn-sm btn-secondary"
                        x-show="currentPage > 0"
                        x-on:click="--currentPage, paginate()"><< Anterior</button>
                <div>
                    Mostrando de <span x-text="start + 1"></span> a <span x-text="end"></span> de <span x-text="filteredStocks.length"></span> resultados
                </div>
                <button class="btn btn-sm btn-secondary"
                        x-show="currentPage < Math.ceil(filteredStocks.length / pageSize)"
                        x-on:click="++currentPage, paginate()">Próxima >></button>
            </div>
            <template x-for="stock in activeStocks" :key="">
                <div class="card col-sm-12 col-md-6 col-lg-4 px-1 py-3">
                    <div class="card-header row border-0">
                        <div class="col-2 d-sm-block d-md-none d-lg-block">
                            <img :src="stock.logo" class="rounded img-fluid" alt="Stock logo" style="max-width: 56px;">
                        </div>
                        <div class="col-10">
                            <div class="d-flex justify-content-between mb-1">
                                <h5 class="card-title" x-text="stock.stock"></h5>
                                <h4 class="card-text font-weight-bold text-success" x-text="'R$ ' + stock.close.toFixed(2)">
                                </h4>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="card-text" x-text="stock.name"></span>
                                <button class="btn btn-success btn-sm px-1 py-0"
                                    x-on:click="showStockInfo(stock)">Comprar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="modal" tabindex="-1" id="modal-stock-info">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Comprar Ativo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" x-on:click="modal.hide()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Saldo em conta</span>
                                <h4 class="info-box-number" x-text="(wallet) ? 'R$ ' + parseFloat(wallet.balance).toFixed(2) : ' - '"></h4>
                            </div>
                        </div>
                        <div class="card card-widget widget-user">

                            <div class="widget-user-header bg-info" style="height: 160px;">
                                <h3 class="widget-user-username" x-text="buy.stock.stock"></h3>
                                <h5 class="widget-user-desc" x-text="buy.stock.name"></h5>
                                <h5 class="widget-user-desc" x-text="buy.stock.sector"></h5>
                            </div>
                            <div class="widget-user-image" style="top: 110px;">
                                <img class="img-circle elevation-2" :src="buy.stock.logo" alt="Stock Logo">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="text-center text-success">
                                            <span class="description-text">Cotação</span>
                                            <h4 class="mt-2" x-text="(buy.stock.close) ? 'R$ ' + buy.stock.close.toFixed(2) : 'R$ -'"></h4>
                                        </div>

                                    </div>

                                    <div class="col-sm-4 border-right">
                                        <div class="text-center">
                                            <span class="description-text text-primary">Quantidade</span>
                                            <div class="input-group mt-1">
                                                <input type="number" min="0" name="quantity" class="form-control" x-model="buy.quantity">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">
                                        <div class="text-center text-success">
                                            <span class="description-text">Preço</span>
                                            <h4 class="description-header text-success" x-text="buy.value.toFixed(2)"></h4>
                                        </div>

                                    </div>

                                </div>
                                <div class="alert alert-warning alert-dismissible mt-3" role="alert" x-show="buy.error != null">
                                    <strong x-text="buy.error"></strong>
                                    <button type="button" class="close" aria-label="Close" x-on:click="buy.error = null">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div x-show="!sendingBuyRequest">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" x-on:click="modal.hide()">Cancelar</button>
                            <button type="button" class="btn btn-success" x-on:click="buyStock()"><i class="fas fa-shopping-cart"></i> Comprar</button>
                        </div>
                        <div x-show="sendingBuyRequest">
                            <div class="bg-light">
                                <i class="fas fa-2x fa-sync-alt spin fa-spin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const component = {
            currentPage: 0,
            pageSize: 12,
            start: 0,
            end: this.pageSize,
            searchValue: '',

            token: '{{ csrf_token() }}',
            sendingBuyRequest: false,
            stocks: [],
            filteredStocks: [],
            activeStocks: [],
            wallet: {},
            modal: $('#modal-stock-info'),
            buy: {
                stock: {},
                quantity: 0,
                value: 0
            },

            paginate() {
                this.start = this.currentPage * this.pageSize
                this.end = this.start + this.pageSize
                this.activeStocks = this.filteredStocks.slice(this.start, this.end)
            },

            filterStocks() {
                this.filteredStocks = this.stocks.filter((stock) => stock.stock.indexOf(this.searchValue.toUpperCase()) > -1 || stock.name.indexOf(this.searchValue.toUpperCase()) > -1)
                this.currentPage = 0
                this.paginate()
            },

            getAllStocks() {
                axios.get('{{ route('stocks.list') }}')
                    .then((response) => {
                        if (response.data) {
                            this.stocks = response.data
                            this.filteredStocks = response.data
                            this.paginate()
                        }
                    })
            },
            async showStockInfo(stock) {
                await axios.get('{{ route("wallets.get") }}')
                    .then((response) => this.wallet = response.data)
                this.buy.stock = stock
                this.buy.quantity = 0
                this.modal.show()
            },
            buyStock() {
                if (this.buy.value > this.wallet.balance) {
                    this.buy.error = 'Saldo insuficiente!'
                    return
                }
                this.sendingBuyRequest = true
                axios.post('{{ route("stocks.buy") }}', {
                    token: this.token,
                    stock_symbol: this.buy.stock.stock,
                    stock: this.buy.stock,
                    quantity: this.buy.quantity,
                    value: this.buy.value
                }).then((response) => {
                    if (response.data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Compra realizada com sucesso!',
                        })
                        this.modal.hide()
                        this.buy.stock = {}
                        this.buy.quantity = 0
                        this.buy.error = null
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title:'Houve um erro ao tentar realizar a compra'
                        })
                    }
                }).then(() => this.sendingBuyRequest = false)
            }
        }
    </script>
@endsection

@push('page_scripts')
    <script></script>
@endpush
