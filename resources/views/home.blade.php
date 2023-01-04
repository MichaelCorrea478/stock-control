@extends('layouts.app')

@section('content')
    <div class="container-fluid" x-cloak x-init="getUserInformation()" x-data="component">
        <div class="row">
            <div class="small-box m-2 p-0 bg-success col-sm-12 col-md-6 col-lg-4">
                <div class="inner">
                    <p class="mb-1">Meu saldo</p>
                    <h3 x-text="(wallet) ? 'R$ ' + parseFloat(wallet.balance).toFixed(2) : ' - '"> R$ 0,00</h3>
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
                    <h3> R$ 0,00</h3>
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
                    </tr>
                </thead>
                <tbody>
                    <template x-for="stock in stocks">
                        <tr>
                            <td><img :src="stock.logo" alt="Stock Logo" class="rounded img-fluid" style="max-width: 40px"></td>
                            <td class="align-middle" x-text="stock.symbol"></td>
                            <td class="align-middle" x-text="stock.sector"></td>
                            <td class="align-middle" x-text="stock.quantity"></td>
                            <td class="align-middle">R$</td>
                            <td class="align-middle">R$</td>
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
                            <td x-text="'R$ ' + parseFloat(transaction.value).toFixed(2)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </div>


    <script>
        const component = {
            token: '{{ csrf_token() }}',
            show: 'stocks',
            wallet: null,
            stocks: [],
            transactions: [],

            async getUserInformation() {
                await axios.get('{{ route('users.information') }}')
                    .then((response) => {
                        this.wallet = response.data.wallet
                        this.stocks = response.data.stocks
                        this.transactions = response.data.transactions
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
