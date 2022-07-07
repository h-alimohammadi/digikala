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