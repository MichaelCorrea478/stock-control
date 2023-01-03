@extends('layouts.app')

@section('content')
    <div class="container-fluid" x-cloak x-init="getUserInformation()" x-data="component">
        <div class="row">
            <div class="small-box m-2 p-0 bg-success col-sm-12 col-md-6 col-lg-4">
                <div class="inner">
                    <p class="mb-1">Meu saldo</p>
                    <h3 x-text="(wallet) ? 'R$ ' + parseFloat(wallet.balance).toFixed(2) : ' - '"> R$ 0,00</h3>
                    <button class="btn btn-sm btn-primary border-white shadow px-1 py-0 mr-1" x-on:click="makeDeposit()">Depositar</button>
                    <button class="btn btn-sm btn-success border-white shadow px-1 py-0 mr-1" x-on:click="makeWithdraw()">Sacar</button>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver extrato <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <hr>



    </div>

    <script>
        const component = {
            token: '{{ csrf_token() }}',
            wallet: null,
            stocks: [],

            async getUserInformation() {
                await axios.get('{{ route("users.information") }}')
                    .then((response) => {
                        this.wallet = response.data.wallet
                        this.stocks = response.data.stocks
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
                            return axios.post('{{ route("wallets.deposit") }}', {
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
                            return axios.post('{{ route("wallets.withdraw") }}', {
                                token: this.token,
                                value: value
                            })
                            .then((response) => response.data )
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Saque realizado com sucesso!',
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title:'Houve um erro ao tentar realizar o saque'
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
