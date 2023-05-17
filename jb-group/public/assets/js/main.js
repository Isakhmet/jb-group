$(document).ready(function () {
    $('#phone').inputmask({"mask": "+7(999)999-99-99"});
    $('#addition_phone').inputmask({"mask": "+7(999)999-99-99"});

    if (typeof $('input[name="limit"]').val() !== 'undefined') {
        var limit = numberWithCommas($('input[name="limit"]').val());
        $('input[name="limit"]').val(limit)
    }

    var elements = document.getElementsByClassName('money')

    $.each(elements,function(index, value){
        elements[index].innerText = numberWithCommas(value.innerText)
    });

    if (typeof $('.money').html() !== 'undefined') {
        var money = numberWithCommas($('.money').html());
        //$('.money').html(money)
    }

    $(function() {
        $('input[name="limit"]').maskMoney();
        $('.money').maskMoney();
    })

    $("#branch_change").change(function () {
        var id = $("#branch_change").val();

        $.ajax({
            url: '/get-branch-currency/',
            type: 'GET',
            data: {
                '_token' : $('input[name="token"]').val(),
                'id' : id
            },
            success: function(result) {
                var html = '';

                $.each(result,function(index, value){
                    balance = numberWithCommas(value.balance);
                    console.log(balance)
                    option = '<tr>';
                    option += '<td>' + value.currency.code + '</td>';
                    option += '<td>' +
                        '<input ' +
                        'type="text" ' +
                        'class="form-control mb-3 money" ' +
                        'name="currency['+value.currency.id+']" ' +
                        'value="'+balance+'">' +
                        '</td>';
                    option += '</tr>'
                    html += option;
                });

                $('tbody').empty().append(html);
                $('.money').maskMoney();
                $('.btn').prop("disabled", false);
            }
        });
    })

    $('#clients').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ru.json',
        },
    });

    $(".notify").click(function () {
        let phone = $("#phone").val();
        phone = phone.replace(/\D/g, "");

        $.ajax({
            url: '/notify',
            type: 'GET',
            data: {
                '_token' : $('input[name="token"]').val(),
                'id' : phone
            },
            success: function(result) {
                console.log(result.code)
                $('.notify').prop("disabled", false);
                $('#code').val(result.code)
                $('.overlay').css("display", "block");
            }
        });
    });

    $('.check-code').click(function () {
        let serverCode = $('#code').val();
        let clientCode = $('input[name="code"]').val();

        if(serverCode === clientCode) {
            alert('Код верный');
            $('input[name="code"]').val("");
            $('.overlay').css("display", "none");
        }else {
            alert('Неверный код!!!');
            $('input[name="code"]').val("");
        }
    })

    $(document).click(function(e) {
        if(e.target.id == "overlay"){
            $('.overlay').css("display", "none");
        }
    });

    $( document ).on( 'keydown', function ( e ) {
        if ( e.keyCode === 27 ) {
            $('.overlay').css("display", "none");
        }
    });
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
