function getSum() {
    $('#basket .good').each(function () {
        var sum = $(this).find('.price').html()*$(this).find('.quantity').val();
        $(this).find('.sum').html(sum.toFixed(2));
    });
}

$(document).ready(function() {
    getSum();
    $('.quantity').on("change", function () {
        getSum();
        $.post('/basket/change/?asAjax=true', {'quantity': $(this).val(), 'goodName': $(this).parent().prev().html()}, function (res) {
            console.log('Данные изменены!');
        })

    })
});
function order() {
    $.ajax(
        {
            url: '/order/add/?asAjax=true',
            method: 'GET',
            data: {
                'phone': $("input[name=phone]").val(),
                'address': $("input[name=address]").val()
            },
            dataType: 'json',
            cache: false

        }).done(function (res) {
        console.log(res);
        alert("Заказ оформлен.");
    }).fail(function (res) {
        console.log(res);
        alert('Ошибка добавления заказа.');
    });
}
function basket(goodId) {
    console.log(goodId);
    $.ajax({
        url: '/basket/add/?asAjax=true',
        method: 'GET',
        data: {
            'good_id': goodId,
            'quantity': 1
        },
        dataType: 'json',
        cache: false
    }).done(function (res) {
        console.log(res);
        alert("Товар добавлен в корзину.");
    }).fail(function (res) {
        console.log(res);
        alert('Ошибка добавления товара.');
    });
}
function clearBasket(){
    $.ajax({
        url: '/basket/clear/?asAjax=true',
        method: 'GET',
        data: {
            'user_id': 'noName'
        },
        dataType: 'json',
        cache: false
    }).done(function (res) {
        alert("Корзина очищена");
    }).fail(function (res) {
        alert('Ошибка очистки корзины' + res);
    });
    document.location.href = '/basket/';
}
function showBasket(){
    document.location.href = '/user/cart';
}
function showMore(goodId) {
    $.ajax({
        url: '/user/more_goods.php',
        method: 'GET',
        data: {
            'good_id': goodId,
            'quantity': 1
        },
        dataType: 'json',
        cache: false
    }).done(function (res) {
        console.log(res);
        alert("!!!.");
    }).fail(function (res) {
        console.log("!!!" + res);
        alert('Ошибка загрузки товаров');
    });
}