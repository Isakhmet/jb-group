$(document).ready(function () {
    $("#currency_change").change(function () {
        let id = $("#currency_change").val();

        $.ajax({
            url: '/get-balance-by-currency/',
            type: 'GET',
            data: {
                'id' : id
            },
            success: function(result) {
                let html = '';

                $.each(result,function(index, value){
                    let balance = numberWithCommas(value.balance);
                    let date = value.updated_at.substr(0, 10)
                    let style = '';

                    if(value.is_limited === true) {
                        style = 'style="background-color: red"';
                    }

                    option = '<tr><th scope="row" style="color: #004d40; font-size: 22px">'+ value.branch.name +'</th>';
                    option += '<td '+style+'>';
                    option += '<ul class="list-group">' +
                        '<li style="font-size: 21px; list-style-type: none;" class="money">'+balance+'</li>' +
                        '<li style="list-style-type: none;">'+date+'</li>' +
                        '</ul>';
                    option += '</td></tr>';
                    html += option;
                });

                $('tbody').empty().append(html);
                $('.money').maskMoney();
            }
        });
    })

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
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
