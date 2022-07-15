const site_url = 'http://localhost:8000/';
let times = null;
let t = 180;

$(document).ready(function () {
    $(".cat_item").mouseover(function () {
        const li_with = $(this).css('width');
        const ul_width = $(".index_cat_list ul").width();
        const a = li_with.replace('px', '');
        const right = ul_width - $(this).offset().left - a + 15;
        // alert(right);
        $('.cat_hover').css('width', li_with);
        $('.cat_hover').css('right', right);
        $('.cat_hover').css('transform', 'scaleX(1)');
        $('.li_div').hide();
        $('.li_div', this).show();
    });
    $(".cat_item").mouseleave(function () {
        $('.li_div').hide();
        $('.cat_hover').css('transform', 'scaleX(0)');

    });
    $('.discount_left_item div').click(function () {
        $('.discount_left_item div').removeClass('active');
        $(this).addClass('active');
        const id = $(this).attr('data-id');
        $('.discount_box_content .item').hide()
        $('#discount_box_link_' + id).show();
    });
    $('.discount_box_footer .swiper-slide-amazing').click(function () {
        $('.discount_box_footer .swiper-slide-amazing').removeClass('active');
        $(this).addClass('active');
        const id = $(this).attr('data-id');
        $('.discount_box_content .item').hide()
        $('#discount_box_link_' + id).show();
    });
    let discount_slider_count = 0;
    let discount_slider_number = 0;
    let discount_box_footer = $('.discount_box_footer').css('display');
    if (discount_box_footer == 'none') {
        discount_slider_count = $('.discount_left_item div').length;
        let discount_slider = setInterval(function () {
            let discount_box_footer = $('.discount_box_footer').css('display');
            if (discount_box_footer == 'none') {
                discount_slider_number++;
                $('.discount_left_item div').removeClass('active');
                $('.discount_box_content .item').hide()
                if (discount_slider_number >= discount_slider_count) {
                    discount_slider_number = 0;
                }
                $("#item_number_" + discount_slider_number).addClass('active');
                const id = $("#item_number_" + discount_slider_number).attr('data-id');
                $('#discount_box_link_' + id).show();
            } else {
                clearInterval(discount_slider);
            }
        }, 5000);
    }
    $("span").tooltip();
    $("a").tooltip();
    $("li").tooltip();
    $(document).on('click', '.color_li', function () {
        const color_id = $(this).attr('data');
        const product_id = $("#product_id").val();
        changeColor(color_id, product_id);
    });
    $('.send_btn').hover(function () {
        $(this).find('.line').addClass('line2');
    }, function () {
        $(this).find('.line').removeClass('line2');
    });
    $(".show_more_important_item").click(function () {
        const more_important_item = $('.more_important_item').css('display');
        if (more_important_item == 'none') {
            $(".more_important_item").slideDown();
            $(".show_more_important_item").text('موارد کمتر');
            $(".show_more_important_item").addClass('minus_important_item');
        } else {
            $(".more_important_item").slideUp();
            $(".show_more_important_item").text('موارد بیشتر');
            $(".show_more_important_item").removeClass('minus_important_item');
        }

    });

    $("#register_btn").click(function () {

        let mobile = $("#register_mobile").val();
        let password = $("#register_password").val();
        const result1 = validate_register_mobile(mobile);
        const result2 = validate_register_password(password);
        if (result1 && result2) {
            $("#register_form").submit();
        }

    });

    $("#resend_active_code").click(function () {
        if (t == 0) {
            const mobile = $("#user_mobile").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const url = site_url + 'ajax/resend';
            $.ajax({
                url: url,
                type: "POST",
                data: "mobile=" + mobile,
                success: function (response) {
                    t = 180;
                    start_timer();
                },
                error: function (jqXhr, textstatus, error) {
                    t = 180;
                    start_timer();
                }
            });
        }
    });
    $("#active_account_btn").click(function () {
        $("#active_account_form").submit();
    });
    $("#login_btn").click(function () {

        let mobile = $("#login_mobile").val();
        let password = $("#login_password").val();
        const result1 = validate_login_mobile(mobile);
        const result2 = validate_login_password(password);
        if (result1 && result2) {
            $("#login_form").submit();
        }
    });
    $("#login-remember").click(function () {
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $("#remember").removeAttr('checked');
        }else {
            $(this).addClass('active');
            $("#remember").attr('checked',true);
        }
    });
});
let img_count = 0;
let img = 0;

function load_slider(count) {
    img_count = count;
    setInterval(next, 5000);
}

function next() {
    if (img == (img_count - 1)) {
        img = -1;
    }
    img = img + 1;
    $('.slide_div').hide();
    $('.slider_bullet_div span').removeClass('active');
    $('.slider_bullet_div #slide_bullet_' + img).addClass('active');
    document.getElementById('slider_img_' + img).style.display = 'block';
}

function previous() {
    img = img - 1;
    if (img == -1) {
        img = (img_count - 1);
    }
    $('.slide_div').hide();
    $('.slider_bullet_div span').removeClass('active');
    $('.slider_bullet_div #slide_bullet_' + img).addClass('active');
    document.getElementById('slider_img_' + img).style.display = 'block';

}

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

function validate_register_mobile(mobile) {
    if (mobile.toString().trim() == "") {
        $("#register_mobile").addClass('validate_error_border');
        $("#mobile_error_message").show().text('لطفا شماره موبایل خود را وارد نمایید.');
        return false;
    } else if (check_mobile_number(mobile)) {
        $("#register_mobile").addClass('validate_error_border');
        $("#mobile_error_message").show().text('شماره موبایل وارد شده معتبر نمی باشد.');
        return false;
    } else {
        $("#register_mobile").removeClass('validate_error_border');
        $("#mobile_error_message").text('').hide();
        return true;
    }
}

function check_mobile_number(mobile) {
    if (isNaN(mobile)) {
        return true;
    } else {
        if (mobile.toString().trim().length == 11) {
            if (mobile.toString().trim().charAt(0) == '0' && mobile.toString().trim().charAt(1) == '9') {
                return false;
            } else
                return true;
        } else if (mobile.toString().trim().length == 10) {
            if (mobile.toString().trim().charAt(0) == '9') {
                return false;
            } else
                return true;
        } else {
            return true;
        }
    }
}

function validate_register_password(password) {
    if (password.toString().trim().length < 8) {
        $("#register_password").addClass('validate_error_border');
        $("#password_error_message").show().text('کلمه عبور باید حداقل شامل 8 کاراکتر باشد.');
        return false;
    } else {
        $("#register_password").removeClass('validate_error_border');
        $("#password_error_message").text('').hide();
        return true;
    }
    return true;
}

replace_number = function (n) {
    n = n.toString();
    let find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
    let replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
    for (let i = 0; i < find.length; i++) {
        n = n.replace(new RegExp(find[i], 'g'), replace[i]);
    }
    return n;
};

function start_timer() {
    times = setInterval(function () {
        t = t - 1;
        let m = Math.floor(t / 60);
        let s = t - m * 60;
        if (t.toString().length == 1) {
            s = "0" + s;
        }
        let text = replace_number("0" + m.toString()) + ":" + replace_number(s.toString());
        if (t == 0) {
            clearInterval(times);
            times = null;
            $("#timer").text("");
        } else {
            $("#timer").text(text);
        }
    }, 1000);
};

function validate_login_mobile(mobile) {
    if (mobile.toString().trim() == "") {
        $("#login_mobile").addClass('validate_error_border');
        $("#mobile_error_message").show().text('لطفا شماره موبایل خود را وارد نمایید.');
        return false;
    } else if (check_mobile_number(mobile)) {
        $("#login_mobile").addClass('validate_error_border');
        $("#mobile_error_message").show().text('شماره موبایل وارد شده معتبر نمی باشد.');
        return false;
    } else {
        $("#login_mobile").removeClass('validate_error_border');
        $("#mobile_error_message").text('').hide();
        return true;
    }
}

function validate_login_password(password) {
    if (password.toString().trim().length < 8) {
        $("#login_password").addClass('validate_error_border');
        $("#password_error_message").show().text('کلمه عبور باید حداقل شامل 8 کاراکتر باشد.');
        return false;
    } else {
        $("#login_password").removeClass('validate_error_border');
        $("#password_error_message").text('').hide();
        return true;
    }
    return true;
}