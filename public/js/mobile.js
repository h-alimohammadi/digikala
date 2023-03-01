const site_url = 'http://localhost:8000/';
let show_item_product = false;
$(document).ready(function () {
    $(".fa-align-justify").click(function () {
        $(".catBox").show();
        setTimeout(function () {
            $("#mySideNav").css('right', '0');
        }, 50);
    });
    $("#catBox").click(function (event) {
        const width = $(window).width();
        const clientX = parseInt(event.clientX);
        if ((width - clientX) > 270) {
            $("#mySideNav").css('right', '-270px');
            setTimeout(function () {
                $(".catBox").hide();
            }, 300);
        }
    });
    $(".parent_cat").click(function () {
        $(".li_div").hide();
        if ($(this).find('span').hasClass('fa fa-plus-circle')) {
            $(".parent_cat").find('span').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            $(this).find('span').removeClass('fa-plus-circle').addClass('fa-minus-circle');
            $(this).parent().find('.li_div').show();
        } else if ($(this).find('span').hasClass('fa fa-minus-circle')) {
            $(".parent_cat").find('span').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            $(this).find('span').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            $(this).parent().find('.li_div').hide();
        }
    });
    $("#mySideNav .child_cat").click(function () {
        $(this).parent().parent().find('li ul').hide();
        $("#mySideNav .child_cat").find('.fa').removeClass('fa-minus-circle').addClass('fa-plus-circle');
        if ($(this).find('.fa').hasClass('fa fa-plus-circle')) {
            $(this).find('.fa').removeClass('fa-plus-circle').addClass('fa-minus-circle');
            $(this).parent().find('ul').show();
        } else {
            $(this).find('.fa').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            $(this).parent().find('ul').hide();

        }
    });
    $(document).on('click', '.color_li', function () {
        const color_id = $(this).attr('data');
        const product_id = $("#product_id").val();
        changeColor(color_id, product_id);
    });
    $("#show_item_product").click(function () {
        if (show_item_product == false) {
            $('body').css('overflow-y', 'hidden');
            $(".mobile_data_box_item").css('top', '0');
            show_item_product = true;
        } else {
            $('body').css('overflow-y', 'auto');
            $(".mobile_data_box_item").css('top', '100%');
            show_item_product = false;
        }
    });

    $("#hide_item_product").click(function () {
        if (show_item_product == true) {
            $('body').css('overflow-y', 'auto');
            $(".mobile_data_box_item").css('top', '100%');
            show_item_product = false;
        }
    });
    $(".add_product_link").click(function () {
        $("#add_cart_form").submit();
    });

});

function changeColor(color_id, product_id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const url = site_url + 'site/change_color';
    $.ajax({
        url: url,
        type: "POST",
        data: "color_id=" + color_id + "&product_id=" + product_id,
        success: function (response) {
            if (response) {
                $('#warranty_box').html(response);
                $("#offer_time").click();
            }
        }
    });
}