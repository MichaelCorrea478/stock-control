@extends('layouts.app')

@section('content')
    <div class="container-fluid" x-cloak
                                x-init="getUserInformation(),
                                        $watch('sell.quantity', qty => sell.value = sell.stock.current_price * qty)"
                                x-data="component">
        <div class="row">
            <div class="small-box m-2 p-0 bg-success col-sm-12 col-md-6 col-lg-4">
                <div class="inner">
                    <p class="mb-1">Meu saldo</p>
                    <h3 x-text="(wallet) ? moneyFormat(wallet.balance) : ' - '"> R$ 0,00</h3>
                    <button class="btn btn-sm btn-primary border-white shadow px-1 py-0 mr-1"
                        x-on:click="makeDeposit()">Depositar</button>
                    <button class="btn btn-sm btn-success border-white shadow px-1 py-0 mr-1"
                        x-on:click="makeWithdraw()">Sacar</button>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" class="small-box-footer" x-on:click="show = 'transactions'">
                    Ver extrato <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>

            <div class="small-box m-2 p-0 bg-primary col-sm-12 col-md-6 col-lg-4">
                <div class="inner">
                    <p class="mb-1">Meu patrimônio</p>
                    <h3 x-text="moneyFormat(patrimony)"></h3>
                </div>
                <div class="icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <a href="#" class="small-box-footer mt-4" x-on:click="show = 'stocks'">
                    Ver ativos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <hr>

        <div class="container-fluid" x-show="show == 'stocks'" x-transition>
            <h3>Carteira de Ações</h3>
            <table class="table table-sm table-responsive-sm table-dark table-hover rounded">
                <thead>
                    <tr>
                        <th></th>
                        <th>Ação</th>
                        <th>Setor</th>
                        <th>Quantidade</th>
                        <th>Preço atual</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="stock in stocks">
                        <tr>
                            <td><img :src="stock.logo" alt="Stock Logo" class="rounded img-fluid" style="max-width: 40px"></td>
                            <td class="align-middle" x-text="stock.symbol"></td>
                            <td class="align-middle" x-text="stock.sector"></td>
                            <td class="align-middle" x-text="stock.quantity"></td>
                            <td class="align-middle" x-text="moneyFormat(stock.current_price)"></td>
                            <td class="align-middle" x-text="moneyFormat(stock.total)"></td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-primary" x-on:click="openSellForm(stock)">Vender</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="container-fluid" x-show="show == 'transactions'" x-transition>
            <h3>Extrato</h3>
            <table class="table table-sm table-responsive-sm table-secondary table-hover rounded">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Ação</th>
                        <th>Quantidade</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="transaction in transactions">
                        <tr :class="{
                            'text-success': ['1', '4'].includes(transaction.transaction_type_id),
                            'text-danger': ['2', '3'].includes(transaction.transaction_type_id)
                        }">
                            <td x-text="transaction.date"></td>
                            <td x-text="transaction.transaction_type"></td>
                            <td x-text="transaction.stock_symbol"></td>
                            <td x-text="transaction.quantity"></td>
                            <td x-text="moneyFormat(transaction.value)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="modal" tabindex="-1" id="modal-stock-info">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vender Ativo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" x-on:click="modal.hide()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Quantidade na carteira</span>
                                <h4 class="info-box-number" x-text="sell.stock.quantity"></h4>
                            </div>
                        </div>
                        <div class="card card-widget widget-user">

                            <div class="widget-user-header bg-info">
                                <h3 class="widget-user-username" x-text="sell.stock.symbol"></h3>
                                <h5 class="widget-user-desc" x-text="sell.stock.sector"></h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2" :src="sell.stock.logo" alt="Stock Logo">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="text-center text-success">
                                            <span class="description-text">Cotação</span>
                                            <h4 class="mt-2" x-text="(sell.stock.current_price) ? moneyFormat(sell.stock.current_price) : 'R$ -'"></h4>
                                        </div>

                                    </div>

                                    <div class="col-sm-4 border-right">
                                        <div class="text-center">
                                            <span class="description-text text-primary">Quantidade</span>
                                            <div class="input-group mt-1">
                                                <input type="number" min="0" name="quantity" class="form-control" x-model="sell.quantity">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">
                                        <div class="text-center text-success">
                                            <span class="description-text">Preço</span>
                                            <h4 class="description-header text-success" x-text="moneyFormat(sell.value)"></h4>
                                        </div>

                                    </div>

                                </div>
                                <div class="alert alert-warning alert-dismissible mt-3" role="alert" x-show="sell.error != null">
                                    <strong x-text="sell.error"></strong>
                                    <button type="button" class="close" aria-label="Close" x-on:click="sell.error = null">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div x-show="!sendingSellRequest">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" x-on:click="modal.hide()">Cancelar</button>
                            <button type="button" class="btn btn-success" x-on:click="sellStock()"><i class="fas fa-shopping-cart"></i> Vender</button>
                        </div>
                        <div x-show="sendingSellRequest">
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
            token: '{{ csrf_token() }}',
            show: 'stocks',
            patrimony: 0,
            wallet: null,
            stocks: [],
            transactions: [],
            modal: $('#modal-stock-info'),
            sendingSellRequest: false,
            sell: {
                stock: {},
                quantity: 0,
                value: 0
            },

            async getUserInformation() {
                await axios.get('{{ route('users.information') }}')
                    .then((response) => {
                        this.wallet = response.data.wallet
                        this.stocks = response.data.stocks
                        this.transactions = response.data.transactions
                    }).then(() => {
                        this.getStockPrices()
                        setInterval(() => {
                            this.getStockPrices()
                        }, 120000);
                    })
            },
            getStockPrices() {
                axios.get('{{ route("stocks.current_prices") }}')
                    .then((response) => {
                        this.stocks.forEach((stock) => {
                            let current = response.data.find((st) => st.symbol == stock.symbol)
                            stock.current_price = current.regularMarketPrice
                            stock.total = stock.current_price * stock.quantity
                        })
                    }).then(() => {
                        this.patrimony = this.stocks.reduce((sum, stock) => sum + stock.total, 0)
                    })
            },
            makeDeposit() {
                Swal.fire({
                    title: 'Digite o valor para fazer o depósito',
                    input: 'number',
                    showCancelButton: true,
                    confirmButtonText: 'Depositar',
                    confirmButtonColor: '#28a745',
                    showLoaderOnConfirm: true,
                    preConfirm: (value) => {
                        return axios.post('{{ route('wallets.deposit') }}', {
                                token: this.token,
                                value: value
                            })
                            .then((response) => {
                                if (!response.data.success) {
                                    throw new Error(response.statusText)
                                }
                                return response.data
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Erro na requisição: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    this.wallet = result.value.wallet
                    this.transactions = result.value.transactions
                    Swal.fire({
                        icon: 'success',
                        title: 'Deposito realizado com sucesso!',
                    })
                })
            },
            makeWithdraw() {
                Swal.fire({
                    title: 'Digite o valor para fazer o saque',
                    input: 'number',
                    showCancelButton: true,
                    confirmButtonText: 'Sacar',
                    confirmButtonColor: '#28a745',
                    showLoaderOnConfirm: true,
                    preConfirm: (value) => {
                        return axios.post('{{ route('wallets.withdraw') }}', {
                                token: this.token,
                                value: value
                            })
                            .then((response) => response.data)
                            .catch(error => {
                                if (error.response.status == 422) {
                                    Swal.showValidationMessage(
                                        ` ${error.response.data.errors.value[0]}`
                                    )
                                } else {
                                    Swal.showValidationMessage(
                                        `Erro na requisição: ${error.data}`
                                    )
                                }
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    console.log(result);
                    if (result.value.success) {
                        this.wallet = result.value.wallet
                        this.transactions = result.value.transactions
                        Swal.fire({
                            icon: 'success',
                            title: 'Saque realizado com sucesso!',
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Houve um erro ao tentar realizar o saque'
                        })
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Erro: ${error}`
                    )
                })
            },
            moneyFormat(value) {
                return parseFloat(value).toLocaleString('pt-BR', {
                                            style: 'currency',
                                            currency: 'BRL'
                                        })
            },
            openSellForm(stock) {
                this.sell.stock = stock
                this.sell.quantity = 0
                this.modal.show()
            },
            sellStock() {
                if (this.sell.quantity > this.sell.stock.quantity) {
                    this.sell.error = 'Quantidade insuficiente em carteira!'
                    return
                }
                this.sendingSellRequest = true
                axios.post('{{ route("stocks.sell") }}', {
                    token: this.token,
                    stock_symbol: this.sell.stock.symbol,
                    stock: this.sell.stock,
                    quantity: this.sell.quantity,
                    value: this.sell.value
                }).then((response) => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Venda realizada com sucesso!',
                        })
                        this.modal.hide()
                        this.sell.stock = {}
                        this.sell.quantity = 0
                        this.sell.error = null
                        this.wallet = response.data.wallet
                        this.updateStock(response.data.stock)
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title:'Houve um erro ao tentar realizar a venda'
                        })
                    }
                }).then(() => this.sendingSellRequest = false)
            },
            updateStock(newStock) {
                this.stocks.forEach((stock, index) => {
                    if (stock.symbol == newStock.symbol) {
                        this.stocks[index] = newStock
                    }
                })
            }

        }
    </script>
@endsection

@push('page_scripts')
    <script>
        $(function() {

        });
    </script>
@endpush
