<template>
    <div>
        <div class="product_list_box">
            <div class="header">
                <ul class="list-inline">
                    <li><span class="fa fa-sort-amount-asc"></span>مرتب سازی بر اساس :</li>
                    <li :class="sort == 21 ? 'active' : '' " v-on:click="set_sort(21)"><a><span>پربازدید ترین</span></a>
                    </li>
                    <li :class="sort == 22 ? 'active' : '' " v-on:click="set_sort(22)"><a><span>محبوب ترین</span></a>
                    </li>
                    <li :class="sort == 23 ? 'active' : '' " v-on:click="set_sort(23)"><a><span>جدید ترین</span></a>
                    </li>
                    <li :class="sort == 24 ? 'active' : '' " v-on:click="set_sort(24)"><a><span>ارزان ترین</span></a>
                    </li>
                    <li :class="sort == 25 ? 'active' : '' " v-on:click="set_sort(25)"><a><span>گران ترین</span></a>
                    </li>
                </ul>
            </div>
            <div class="search_product_div product_list">

                <div class="product_div" v-for="product in this.ProductList.data">
                    <div class="img_div">
                        <div class="product_offer_div">
                            <div v-if="check_has_off(product)">
                                <product-offers-time :time="check_has_off(product)"></product-offers-time>
                            </div>
                        </div>
                        <ul class="list-inline color_box">
                            <li v-for="(color,key) in product.get_color" v-if="color.color != null && key < 3">
                                <label :style="{background:color.color.code}"></label>
                            </li>
                            <li v-if="product.get_color.length>3">
                                <span class="fa fa-plus"></span>
                            </li>
                        </ul>
                        <a v-bind:href="$siteUrl+'product/dkp-'+product.id+'/'+product.product_url">
                            <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+product.image_url">
                        </a>
                    </div>
                    <div class="info">
                        <a v-bind:href="$siteUrl+'product/dkp-'+product.id+'/'+product.product_url">
                            <p class="title">{{product.title}}</p>
                        </a>
                        <div class="compare_tag" v-if="compare=='yes'">
                            <p v-on:click="add_compare_list(product)">
                                <span :class="[has_compare_list(product.id).result ? 'active check_box' : 'check_box' ]"></span>
                                <span class="pr-2">مقایسه</span>
                            </p>
                        </div>
                        <div v-if="product.status == 1 && product.get_first_product_price != null" class="price">
                            <div class="discount_div">
                                <div v-if="product.get_first_product_price.price1 != product.get_first_product_price.price2">
                                    <del>{{replace_number(number_format(product.get_first_product_price.price1))}} تومان
                                    </del>
                                    <span class="discount-badge">
                                {{ replace_number(getDiscountValue(product.get_first_product_price.price1, product.get_first_product_price.price2)) }}%
                            </span>
                                </div>
                            </div>
                            <span>{{replace_number(number_format(product.get_first_product_price.price2))}} تومان</span>
                        </div>
                        <div v-else class="product_status">
                            <div>
                                <p class="line"></p>
                                <span>ناموجود</span>
                            </div>
                        </div>
                    </div>
                    <div class="shop_name" v-if="product.status == 1">
                        فروشنده : دیجی آنلاین
                    </div>
                </div>
                <div v-if="this.ProductList.data.length == 0 && get_result" class="not_found_product_message">
                    محصولی برای نمایش یافت نشد.
                </div>
                <div class="paginate_div">
                    <pagination :data="ProductList" @pagination-change-page="getProduct"/>
                </div>
            </div>
        </div>
        <div class="compare_product_list" v-if="this.compare_list.length>0 && show_compare"
             v-on:mouseleave="show_compare=false">
            <ul>
                <li v-for="item in compare_list">
                    <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+item.pic">
                    <span style="width: 175px;">{{ item.title }}</span>
                    <span class="fa fa-close" v-on:click="remove_product_of_compare_list(item.id)"></span>
                </li>
            </ul>
            <span class="empty_compare_list" v-on:click="compare_list = [];compare_link=''">انصراف</span>
        </div>
        <a v-bind:href="compare_link" id="compare_list" v-if="this.compare_list.length>0"
           v-on:mouseover="show_compare=true">
            <div>
                <span>مقایسه</span>
                <span>{{ replace_number(compare_list.length) }}</span>
                <span>کالا</span>
            </div>
        </a>
    </div>
</template>

<script>
    import myMixin from "../myMixin";
    import ProductOffersTime from "./ProductOffersTime";

    export default {
        name: "ProductBox",
        components: {ProductOffersTime},
        props: ['compare'],
        data() {
            return {
                request_url: '',
                ProductList: {data: []},
                noUiSlider: null,
                min_price: 0,
                max_price: 0,
                get_result: false,
                sort: 21,
                search_string: '',
                compare_list: [],
                show_compare: false,
                compare_link: '',
            }
        },
        mixins: [myMixin],
        mounted() {
            const self = this;
            this.set_product_sort();
            this.set_search_string();
            this.check_search_param();
            $(document).on('click', '#price_filter_btn', function () {
                self.setFilterPrice();
            });
            $(document).on('click', '.product_cat_ul li', function () {
                self.set_filter_event(this);
            });
            $(document).on('keyup', '#search_input', function () {
                self.search_product(event);
            });
            $(document).on('toggle', '#product_status', function (e, action) {
                self.set_product_status(action);
            });
            $(document).on('toggle', '#send_status', function (e, action) {
                self.set_send_status(action);
            });
            $(document).on('click', '.select_filter_item .fa-close', function () {
                self.remove_filter_item($(this).parent());
            });
            $(document).on('click', '#remove_all_filter', function () {
                self.remove_all_filter();
            });
            this.getProduct();
            $(document).on({
                mouseenter: function () {
                    $('.compare_tag p', this).show();
                },
                mouseleave: function () {
                    $('.compare_tag p', this).hide();
                }
            }, '.product_div');
        },
        methods: {
            getProduct: function (page = 1) {
                $("#loading_box").show();
                this.request_url = window.location.href.replace(this.$siteUrl, this.$siteUrl + 'get-product/');
                const url = this.request_url + '?page=' + page
                this.axios.get(this.get_request_url(this.request_url, page)).then(response => {
                    if (response.data['count'] != undefined) {
                        $('#product_count').text(this.replace_number(response.data['count']) + " کالا");
                    }
                    $("#loading_box").hide();
                    this.ProductList = response.data['product'];
                    this.setRangeSlider(response.data.maxPrice);
                    this.get_result = true;

                }).catch(error => {
                    $("#loading_box").hide();
                    console.log(error);
                });
            },
            check_search_param: function () {
                let url = window.location.href;
                const params = url.split('?');
                if (params[1] != undefined) {
                    if (params[1].indexOf('&') > -1) {
                        let vars = params[1].split('&');
                        for (let i in vars) {
                            let k = vars[i].split('=')[0];
                            let v = vars[i].split('=')[1];
                            k = k.split('[');
                            this.add_active_filter(k, v);
                        }
                    } else {
                        let k = params[1].split('=')[0];
                        let v = params[1].split('=')[1];
                        k = k.split('[');
                        this.add_active_filter(k, v);
                    }
                }
            },
            add_filter_tag: function (data, k, v) {
                data = data.toString().replace(',', '_').replace(',', '_');
                data = data.toString().replace("'", '').replace("'", '');
                const el = "li[data='" + data + "']";
                const title = $(el).parent().parent().parent().parent().find('.title_box').text();
                const html = '<div class="select_filter_item" data-key="' + k + '" data-value="' + v + '">' +
                    '<span>' +
                    title + ":" + $(el).find('.title').text() +
                    '</span>' +
                    '<span class="fa fa-close"></span>' +
                    '</div>';
                $("#select_filter_box").append(html);
                $("#filter_div").show();
            },
            remove_filter_tag: function (k, v) {
                let data_key = $('.select_filter_item[data-key="' + k + '"][data-value="' + v + '"]');
                if (data_key.length != 0) {
                    data_key.remove();
                }
                if ($('.select_filter_item').length == 0 && $('.product_status_filter').length == 0 && $('.send_status_filter').length == 0) {
                    $("#filter_div").hide();
                }
            },
            setRangeSlider: function (price) {
                const self = this;
                var slider = document.querySelector('.price_range_slider');
                if (this.noUiSlider == null) {
                    if (parseInt(price) > 0) {
                        this.noUiSlider = noUiSlider.create(slider, {
                            start: [0, price],
                            connect: true,
                            direction: 'rtl',
                            range: {
                                'min': 0,
                                'max': price,
                            },
                            format: {
                                from: function (value) {
                                    return parseInt(value);
                                },
                                to: function (value) {
                                    return parseInt(value);
                                }
                            },
                        });
                    }

                }
                if (slider.noUiSlider != undefined) {
                    slider.noUiSlider.on('update', function (values, handle) {
                        self.min_price = values[0];
                        self.max_price = values[1];
                        $('#min_price').text(self.replace_number(self.number_format(values[0])));
                        $('#max_price').text(self.replace_number(self.number_format(values[1])));
                    });
                    // else {
                    //     this.noUiSlider.updateOptions({
                    //         start: [0, price],
                    //         range: {
                    //             'min': 0,
                    //             'max': price,
                    //         },
                    //     });
                    // }

                    let search = new window.URLSearchParams(window.location.search);
                    let min = search.get('price[min]') != null ? parseInt(search.get('price[min]')) : 0;
                    if (search.get('price[max]') != null) {
                        this.noUiSlider.updateOptions({
                            start: [min, parseInt(search.get('price[max]'))],
                        });
                    }
                    if (search.get('price[min]') != null && search.get('price[max]') == null) {
                        this.noUiSlider.updateOptions({
                            start: [parseInt(search.get('price[min]')), price],
                        });
                    }
                }
            },
            add_url_param: function (key, value) {
                let params = new window.URLSearchParams(window.location.search);
                let url = window.location.href;
                if (params.get(key) != null) {
                    const old_param = key + "=" + encodeURIComponent(params.get(key));
                    const new_param = key + "=" + value;
                    url = url.replace(old_param, new_param);
                } else {
                    const url_params = url.split('?');
                    if (url_params[1] == undefined) {
                        url = url + '?' + key + '=' + value;
                    } else {
                        url = url + '&' + key + '=' + value;
                    }
                }
                const url_param = url.split('?');
                if (url_param[1] == undefined) {
                    url = url.replace('&', '?');
                }
                this.setPageUrl(url);
            },
            setPageUrl: function (url) {
                window.history.pushState('data', 'title', url);
                this.getProduct();
            },
            getDiscountValue: function (price1, price2) {
                let a = (price2 / price1) * 100;
                a = 100 - a;
                a = Math.round(a);
                return a;
            },
            set_filter_event: function (el) {
                let data = $(el).attr('data');
                data = data.split('_');
                if ($('.check_box', el).hasClass('active')) {
                    $('.check_box', el).removeClass('active');
                    this.remove_url_query_string(data[0], data[2]);
                    this.remove_filter_tag(data[0], data[2]);

                } else {
                    $('.check_box', el).addClass('active');
                    this.add_url_query_string(data[0], data[2]);
                    this.add_filter_tag(data, data[0], data[2]);
                }
            },
            add_url_query_string: function (key, value) {
                let url = window.location.href;
                let check = url.split(key);
                const n = check.length - 1;
                const url_params = url.split('?');
                if (url_params[1] == undefined) {
                    url = url + '?' + key + "[" + n + "]" + '=' + value;
                } else {
                    url = url + '&' + key + "[" + n + "]" + '=' + value;
                }
                this.setPageUrl(url);
            },
            remove_url_query_string: function (key, value) {
                let url = window.location.href;
                let check = url.split(key);
                const params = url.split('?');
                let h = 0;
                if (params[1] != undefined) {
                    if (params[1].indexOf('&') > 1) {
                        let vars = params[1].split('&');
                        for (let i in vars) {
                            let k = vars[i].split('=')[0];
                            let v = vars[i].split('=')[1];
                            let n = k.indexOf(key);
                            if (n > -1 && v != value) {
                                k = k.replace(key, '');
                                k = k.replace('[', '');
                                k = k.replace(']', '');
                                const new_string = key + "[" + h + "]=" + v;
                                const old_string = key + "[" + k + "]=" + v;
                                url = url.replace(old_string, new_string);
                                h++;
                            } else if (n > -1) {
                                url = url.replace('&' + k + "=" + v, '');
                                url = url.replace('?' + k + "=" + v, '');
                            }
                        }
                    } else {
                        url = url.replace('?' + key + '[0]' + '=' + value, '');
                    }
                }
                const url_param = url.split('?');
                if (url_param[1] == undefined) {
                    url = url.replace('&', '?');
                }
                this.setPageUrl(url);
            },
            setFilterPrice: function () {
                this.add_url_param('price[min]', this.min_price);
                this.add_url_param('price[max]', this.max_price);
                this.getProduct(1);
            },
            add_active_filter: function (k, v) {
                if (k.toString().length > 1) {
                    if (k == 'has_product') {
                        const html = '<div class="select_filter_item product_status_filter">' +
                            '<span>فقط کالاهای موجود</span>' +
                            '<span class="fa fa-close"></span>' +
                            '</div>';
                        $('#select_filter_box').append(html);
                        $('#filter_div').show();
                    } else if (k == 'ready_to_shipment') {
                        const html = '<div class="select_filter_item send_status_filter">' +
                            '<span>فقط کالاهای آماده ارسال</span>' +
                            '<span class="fa fa-close"></span>' +
                            '</div>';
                        $('#select_filter_box').append(html);
                        $('#filter_div').show();
                    }
                    let data = '';
                    let filter_key = k[0];
                    if (k.length == 3) {
                        data = k[0] + "[" + k[1] + "_param_" + v;
                        data = "'" + data + "'";
                        filter_key = k[0] + "[" + k[1];
                    } else {
                        data = k[0] + "_param_" + v;
                    }
                    data = "'" + data + "'";
                    $('li[data=' + data + '] .check_box').addClass('active');
                    $('li[data=' + data + ']').parent().parent().slideDown();
                    if ($('li[data=' + data + ']').length == 1) {
                        this.add_filter_tag(data, filter_key, v);
                    }
                }

            },
            set_sort: function (value) {
                this.sort = value;
                this.add_url_param('sortBy', value);
            },
            get_request_url: function (url, page) {
                const url_param = url.split('?');
                if (url_param[1] == undefined) {
                    url = url + "?page=" + page;
                } else {
                    url = url + "&page=" + page;
                }
                return url;
            },
            set_product_sort: function () {
                let params = new window.URLSearchParams(window.location.search);
                if (params.get('sortBy') != null) {
                    if (params.get('sortBy') >= 21 && params.get('sortBy') <= 25) {
                        this.sort = params.get('sortBy');
                    }
                } else {
                    this.sort = 21;
                }
            },
            search_product: function (event) {
                if (event.keyCode == 13) {
                    const search_text = $('#search_input').val();
                    if (search_text.trim().length == 0) {
                        if (this.search_string != '') {
                            this.remove_url_param('string', this.search_string);
                            this.search_string = '';
                        }
                    } else {
                        if (search_text.trim().length > 1) {
                            this.search_string = search_text;
                            this.add_url_param('string', search_text);
                        }
                    }
                }
            },
            remove_url_param: function (key, value) {
                let params = new window.URLSearchParams(window.location.search);
                let url = window.location.href;
                if (params.get(key) != null) {
                    value = encodeURIComponent(value);
                    url = url.replace('&' + key + "=" + value, '');
                    url = url.replace('?' + key + "=" + value, '');
                    this.remove_filter_tag(key, value);
                }
                const url_param = url.split('?');
                if (url_param[1] == undefined) {
                    url = url.replace('&', '?');
                }
                this.setPageUrl(url);

            },
            set_search_string: function () {
                let params = new window.URLSearchParams(window.location.search);
                let url = window.location.href;
                if (params.get('string') != null) {
                    this.search_string = params.get('string');
                }
            },
            set_product_status: function (action) {
                if (action) {
                    this.add_url_param('has_product', 1);
                    const html = '<div class="select_filter_item product_status_filter">' +
                        '<span>فقط کالاهای موجود</span>' +
                        '<span class="fa fa-close"></span>' +
                        '</div>';
                    $('#select_filter_box').append(html);
                    $('#filter_div').show();
                } else {
                    this.remove_url_param('has_product', 1);
                    $(".product_status_filter").remove();
                    if ($('.select_filter_item').length == 0 && $('.product_status_filter').length == 0 && $('.send_status_filter').length == 0) {
                        $("#filter_div").hide();
                    }
                }
            },
            set_send_status: function (action) {
                if (action) {
                    this.add_url_param('ready_to_shipment', 1);
                    const html = '<div class="select_filter_item send_status_filter">' +
                        '<span>فقط کالاهای آماده ارسال</span>' +
                        '<span class="fa fa-close"></span>' +
                        '</div>';
                    $('#select_filter_box').append(html);
                    $('#filter_div').show();
                } else {
                    this.remove_url_param('ready_to_shipment', 1);
                    $(".send_status_filter").remove();
                    if ($('.select_filter_item').length == 0 && $('.product_status_filter').length == 0 && $('.send_status_filter').length == 0) {
                        $("#filter_div").hide();
                    }
                }
            },
            remove_filter_item: function (el) {
                const key = $(el).attr('data-key');
                const value = $(el).attr('data-value');
                if (key && value) {
                    this.remove_url_query_string(key, value);
                    $(el).remove();
                    const data = key + "_param_" + value;
                    $('li[data="' + data + '"] .check_box').removeClass('active');
                    if ($('.select_filter_item').length == 0 && $('.product_status_filter').length == 0 && $('.send_status_filter').length == 0) {
                        $("#filter_div").hide();
                    }
                } else if ($(el).hasClass('product_status_filter')) {
                    this.remove_product_status(el);
                } else if ($(el).hasClass('send_status_filter')) {
                    this.remove_send_status(el);
                }

            },
            remove_product_status: function (el) {
                $(el).remove();
                this.remove_url_param('has_product', '1');
                if ($('.select_filter_item').length == 0 && $('.product_status_filter').length == 0 && $('.send_status_filter').length == 0) {
                    $("#filter_div").hide();
                }
                $('#product_status').unbind('click');
                $('#product_status').toggles({
                    type: 'Light',
                    text: {'on': '', 'off': ''},
                    width: 50,
                    direction: 'rtl',
                    on: false,
                });
            },
            remove_send_status: function (el) {
                $(el).remove();
                this.remove_url_param('ready_to_shipment', 1);
                if ($('.select_filter_item').length == 0 && $('.product_status_filter').length == 0 && $('.send_status_filter').length == 0) {
                    $("#filter_div").hide();
                }
                $('#send_status').unbind('click');
                $('#send_status').toggles({
                    type: 'Light',
                    text: {'on': '', 'off': ''},
                    width: 50,
                    direction: 'rtl',
                    on: false,
                });
            },
            add_compare_list: function (product) {
                const array = this.has_compare_list(product.id);
                if (array.result) {
                    this.$delete(this.compare_list, array.key);
                } else {
                    if (this.compare_list.length < 4) {
                        this.set_compare_link(product.id);
                        this.compare_list.push({id: product.id, title: product.title, pic: product.image_url});
                    }
                }
            },
            has_compare_list: function (id) {
                let result = false;
                let key = null;
                for (let i = 0; i < this.compare_list.length; i++) {
                    if (this.compare_list[i].id == id) {
                        result = true;
                        key = i;
                    }
                }
                return {result: result, key: key};
            },
            remove_product_of_compare_list: function (id) {
                if (this.has_compare_list(id)) {
                    this.$delete(this.compare_list, this.has_compare_list(id).key);
                }
                this.compare_link = this.compare_link.replace('/dkp-' + id, '');
            },
            set_compare_link: function (id) {
                if (this.compare_link == '') {
                    this.compare_link = this.$siteUrl + 'compare';
                }
                this.compare_link += "/dkp-" + id;

            },
            check_has_off: function (product) {
                if (product.get_first_product_price != null) {

                    const last_time = product.get_first_product_price.offers_last_time;
                    const time = Math.floor(Date.now() / 1000);
                    if (product.get_first_product_price.offers == 1 && (last_time - time) > 0) {
                        return (last_time - time);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            },
            remove_all_filter: function () {
                let url = window.location.href;
                url = url.split('?');
                $(".select_filter_item").remove();
                $("#filter_div").hide();
                $(".filter_box .check_box.active").removeClass('active');
                $(".filter_box").slideUp(300);
                if (!$("#product_status .toggle-slide .toggle-off").hasClass('active')) {
                    $("#product_status").click();
                }
                if (!$("#send_status .toggle-slide .toggle-off").hasClass('active')) {
                    $("#send_status").click();
                }
                if (this.noUiSlider) {
                    this.noUiSlider.reset();
                }
                this.setPageUrl(url[0]);
            },
        },
    }
</script>

<style scoped>

</style>