let toggeld = false;
$('.page_sidebar li').click(function () {
    if (!$(this).hasClass('active')) {
        $('.page_sidebar li').removeClass('active');
        $('.child_menu').slideUp(300);
        $('#sidebar_menu .fa-angle-left').removeClass('fa-angle-down');

        $(this).addClass('active');
        $('#sidebar_menu .fa-angle-left', this).addClass('fa-angle-down');

        if (!toggeld) {
            $('.child_menu', this).slideDown(500);
        } else {
            $('.child_menu', this).show();
        }
    } else if (toggeld) {
        $('.child_menu').slideUp(300);
        $('.child_menu', this).show();
    }
    // }else {
    //     $('.child_menu', this).slideUp(500);
    // }
});

$('.page_sidebar .fa-bars').click(function () {
    if ($(".page_sidebar").hasClass('toggled')) {
        toggeld = false;
        $(".page_sidebar").removeClass('toggled');
        $('.page_content').css('margin-right', '240px');
    } else {
        toggeld = true;
        $(".page_sidebar").addClass('toggled');
        $('.page_content').css('margin-right', '50px');
    }
});

$(window).resize(function () {
    const width = document.body.offsetWidth;
    if (width < 850) {
        // toggeld = true;
        $(".page_sidebar").addClass('toggled');
        $('.page_content').css('margin-right', '50px');

    } else {
        // toggeld = false;
        if (toggeld == false) {

            $(".page_sidebar").removeClass('toggled');
            $('.page_content').css('margin-right', '240px');
            $('.child_menu', this).hide();
        }
    }
});

$(document).ready(function () {
    set_sidebar_width();
    let url = window.location.href.split('?')[0];
    $('#sidebar_menu a[href="' + url + '"]').parent().parent().addClass('active');
    $('#sidebar_menu a[href="' + url + '"]').parent().parent().find('a .fa-angle-left').addClass('fa-angle-down');
    $('#sidebar_menu a[href="' + url + '"]').parent().parent().find('.child_menu').show();
});

function set_sidebar_width() {
    const width = document.body.offsetWidth;
    if (width < 850) {
        // toggeld = true;
        $(".page_sidebar").addClass('toggled');
        $('.page_content').css('margin-right', '50px');

    } else {
        // toggeld = false;
        if (toggeld == false) {

            $(".page_sidebar").removeClass('toggled');
            $('.page_content').css('margin-right', '240px');
            $('.child_menu', this).hide();
        }
    }
};


select_file = function () {
    $('#pic').click();
};
select_file2 = function () {
    $('#mobile_pic').click();
};

function loadFile(event) {
    const render = new FileReader();
    render.readAsDataURL(event.target.files[0])
    render.onload = function () {
        const output = document.getElementById('output');
        output.src = render.result;
    };
}
function loadFile2(event) {
    const render = new FileReader();
    render.readAsDataURL(event.target.files[0])
    render.onload = function () {
        const output = document.getElementById('output2');
        output.src = render.result;
    };
}

let delete_url;
let token;
let send_array_data = false;
let _method = 'DELETE';

function del_row(url, t, message) {
    delete_url = url;
    token = t;
    $('#msg').text(message);
    $('.message_div').show();
}

hide_box = function () {
    _method = 'DELETE';
    delete_url = '';
    token = '';
    $('.message_div').hide();
};
deleted_row = function () {
    if (send_array_data) {
        $("#data_form").submit();
    } else {
        let form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', delete_url);
        const hidenInput1 = document.createElement('input');
        hidenInput1.setAttribute('name', '_method');
        hidenInput1.setAttribute('value', _method);
        form.append(hidenInput1);
        const hidenInput2 = document.createElement('input');
        hidenInput2.setAttribute('name', '_token');
        hidenInput2.setAttribute('value', token);
        form.append(hidenInput2);

        document.body.append(form);
        form.submit();
        document.body.removeChild(form);
    }
};

$(".check_box").click(function () {
    send_array_data = false;
    const $checkboxes = $('table tr td input[type="checkbox"]');
    const count = $checkboxes.filter(':checked').length;
    if (count > 0) {
        $(".item_form").removeClass('off');
    } else {
        $(".item_form").addClass('off');
    }
});
$('.item_form').click(function () {
    send_array_data = true;
    const $checkboxes = $('table tr td input[type="checkbox"]');
    const count = $checkboxes.filter(':checked').length;
    if (count > 0) {
        let href = window.location.href.split('?');
        let action = href[0] + "/" + this.id;
        if (href.length == 2) {
            action = action + '?' + href[1];
        }
        console.log(action);

        $("#data_form").attr('action', action);
        $('#msg').text($(this).attr('msg'));
        $(".message_div").show();

    }
});

$("span").tooltip();
$("a").tooltip();

function restore_row(url, t, message) {
    _method = 'post';
    delete_url = url;
    token = t;
    $('#msg').text(message);
    $('.message_div').show();
}

add_tags = function () {
    let tags = $('#tag_list').val();
    let keywords = document.getElementById('keywords').value;
    let array_tag = tags.split('،');
    let string = keywords;
    let count = document.getElementsByClassName('tag_div').length + 1;
    for (let i = 0; i < array_tag.length; i++) {
        if (array_tag[i].trim() != '') {
            const n = keywords.search(array_tag[i]);
            if (n == -1) {
                let r = "'" + array_tag[i] + "'";
                string = string + '،' + array_tag[i];
                var tag = '<div class="tag_div" id="tag_div_' + count + '">' +
                    '<span class="fa fa-remove" onclick="remove_tag(' + count + ',' + r + ')"></span>' + array_tag[i] +
                    '</div>';
                count++;
                $(".tag_box").append(tag);
            }
        }
    }
    document.getElementById('keywords').value = string;
    $('#tag_list').val('');
};
remove_tag = function (id, text) {
    $("#tag_div_" + id).remove();
    let keywords = document.getElementById('keywords').value;
    let t1 = text + '،';
    let t2 = '،' + text;
    let a = keywords.replace(t1, '');
    let b = a.replace(t2, '');
    document.getElementById('keywords').value = b;
};