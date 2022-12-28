@extends('layouts.app')

@section('content')
    <div class="container-fluid" x-cloak x-init="getUserInformation()" x-data="component">
        <div class="row">
            <div class="small-box m-2 p-0 bg-success col-sm-12 col-md-6 col-lg-4">
                <div class="inner">
                    <p class="mb-1">Meu saldo</p>
                    <h3 x-text="(wallet) ? 'R$ ' + parseFloat(wallet.balance).toFixed(2) : ' - '"> R$ 0,00</h3>
                    <button class="btn btn-sm btn-primary border-white shadow px-1 py-0 mr-1">Depositar</button>
                    <button class="btn btn-sm btn-success border-white shadow px-1 py-0 mr-1">Sacar</button>
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
