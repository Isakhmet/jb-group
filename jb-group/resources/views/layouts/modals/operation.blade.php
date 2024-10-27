<link href="{{asset('assets/plugins/images/bootstrap/4.1.1/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
<script src="{{asset('assets/plugins/images/bootstrap/4.1.1/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/plugins/images/jquery/3.2.1/jquery.min.js')}}"></script>


<link rel="stylesheet" href="{{asset('assets/plugins/images/magnific-popup.css')}}"/>
<script src="{{asset('assets/plugins/images/isotope.pkgd.js')}}"></script>
<script src="{{asset('assets/plugins/images/jquery.magnific-popup.js')}}"></script>

<link href="{{asset('fonts/material-design-icons/material-icon.css')}}" rel="stylesheet" type="text/css"/>

<style>
    .card-head {
        display: block;
        unicode-bidi: isolate;
        border-radius: 2px 2px 0 0;
        border-bottom: 1px dotted rgba(0, 0, 0, 0.2);
        padding: 2px;
        text-transform: uppercase;
        color: #3a405b;
        font-size: 14px;
        font-weight: 600;
        line-height: 40px;
        min-height: 40px;
    }

    .card-box {
        background: #fff;
        min-height: 50px;
        box-shadow: none;
        position: relative;
        margin-bottom: 20px;
        transition: 0.5s;
        border: 1px solid #f2f2f2;
    }

    .card-head:before, .card-head:after {
        content: " ";
        display: table;
    }

    .card-head header {
        display: inline-block;
        padding: 11px 20px;
        vertical-align: middle;
        line-height: 17px;
        font-size: 20px;
    }

    .pos-modal {
        position: absolute;
        top: 25%;
        left: 25%;
    }

    .material-icons {
        cursor: pointer;
    }
</style>
<div id="overlay" class="overlay">
    <div class="col-md-6 col-sm-6 pos-modal">
        <div class="card card-box">
            <div class="card-head">
                <header id="header-title">Horizontal Form</header>
                <div class="float-right mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                    <div class="icon-holder">
                        <i id="close-icon" class="material-icons f-left">cancel</i>
                    </div>
                </div>
            </div>
            <div class="card-body " id="bar-parent1">
                <form method="post" action="{{route('operations.store')}}" class="form-horizontal">
                    @csrf
                    <div class="form-group row">
                        <label for="client" class="col-sm-2 control-label">Клиент</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="client" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amount" class="col-sm-2 control-label">Сумма</label>
                        <div class="col-sm-10">
                            <input name="amount" type="number" class="form-control" id="amount" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group row buy-rate">
                        <label for="rate" class="col-sm-2 control-label">Курс</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="buy_rate" id="buy_rate" placeholder="" readonly required>
                        </div>
                    </div>
                    <div class="form-group row sell-rate">
                        <label for="rate" class="col-sm-2 control-label">Курс</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="sell_rate" id="sell_rate" placeholder="" readonly required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amountKzt" class="col-sm-2 control-label">Сумма в тг</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="amount_kzt" id="amountKzt" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="offset-md-3 col-md-9">
                            <button id="actionBtn" type="submit" class="btn btn-info">Submit</button>
                            <button type="button" class="btn btn-danger">Отмена</button>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="type" id="operationType">
                    <input type="hidden" class="form-control" name="currency_id" id="currency_id">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const amountInput = document.getElementById('amount');
    const buyRateInput = document.getElementById('buy_rate');
    const sellRateInput = document.getElementById('sell_rate');
    const amountKztInput = document.getElementById('amountKzt');
    const typeOperation = document.getElementById('operationType');
    let rate;

    amountInput.addEventListener('input', function() {

        if(typeOperation.value === 'buy') {
            rate = parseFloat(buyRateInput.value) || 0;
        } else {
            rate = parseFloat(buyRateInput.value) || 0;
        }

        const amount = parseFloat(amountInput.value) || 0; // Получаем значение `amount`

        amountKztInput.value = amount * rate;
    });

    $('.btn-danger').click(function () {
        $('.overlay').css("display", "none");
        $('.buy-rate').css("display", "flex");
        $('.sell-rate').css("display", "flex");
    });

    $('#close-icon').click(function () {
        $('.overlay').css("display", "none");
        $('.buy-rate').css("display", "flex");
        $('.sell-rate').css("display", "flex");
    });
</script>
