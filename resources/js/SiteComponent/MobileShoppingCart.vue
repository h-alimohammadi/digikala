<template>
    <div>
        <div class="page_row">
            <div v-if="CartProduct.product != undefined && CartProduct.product.length>0">
                <div class="page_content">
                    <div class="page_aside">
                        <div class="order_info">
                            <ul>
                                <li>
                                    <span>مبلغ کل</span>
                                    <span>({{replace_number(CartProduct.product_count)}}) کالا </span>
                                    <span class="left">{{ CartProduct.total_price }} تومان </span>
                                </li>
                                <li class="cart_discount_li" v-if="CartProduct.discount != 0">
                                    <span>سود شما از خرید   </span>
                                    <span class="left">{{ CartProduct.discount }} تومان </span>
                                </li>
                                <li>
                                    <span>هزینه ارسال</span>
                                    <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom"
                                          title="هزینه ارسال مرسولات میتواند وابسته به شهر و آدرس گیرنده متفاوت باشد. در صورتی که هر یک از مرسولات حداقل ارزشی برابر با 150 هزار تومان داشته باشد. آن مرسوله بصورت رایگان ارسال میشود."></span>
                                    <span class="left">وابسته به آدرس</span>
                                </li>
                            </ul>
                            <div class="checkout_devider"></div>
                            <div class="checkout_content">
                                <p style="color: red">مبلغ قابل پرداخت</p>
                                <p>{{CartProduct.cart_price}} تومان </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart_content">
                    <div v-for="product in CartProduct['product']" class="cart_row">
                        <div v-if="check_has_off(product)" class="discount_cart_div">
                            <span style="color: red">پیشنهاد شگفت انگیز</span>
                            <span>
                                تخفیف : {{ replace_number(number_format((product.price1-product.int_price2))) }} تومان
                            </span>
                        </div>
                        <div class="checkout_item">
                            <div class="cart_product_image">
                                <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+product.product_image_url">
                            </div>
                            <div class="cart_product_info">
                                <a v-bind:href="$siteUrl+'product/dkp-'+product.product_id+'/'+product.product_url">{{product.product_title}}</a>
                                <span>{{product.warranty_name}}</span>
                                <div class="cart_color_div" v-if="product.color_name != undefined">
                                    <span>رنگ : </span>
                                    <span>{{ product.color_name }}</span>
                                    <span class="ui_variant_shape1"
                                          v-bind:style="{background: product.color_code} "></span>
                                </div>
                                <span class="cart_product_price"> {{ replace_number(number_format(product.price1)) }} تومان</span>
                            </div>
                        </div>
                        <div class="checkout_options">
                            <span>تعداد :</span>
                            <span>
                            <select class="selectpicker" v-model="product.product_count"
                                    v-on:change="change_product_count(product)">
                                <option v-for="i in product.product_number_cart" v-bind:value="i">
                                    {{replace_number(i)}}
                                </option>
                            </select>
                        </span>
                            <div class="remove_product" v-on:click="remove_product(product)">حذف</div>
                        </div>
                    </div>
                </div>
                <a v-bind:href="$siteUrl+'shipping'">
                    <div class="add_product_link">
                        <span>افزودن محصول به سبد خرید</span>
                    </div>
                </a>
            </div>
            <div v-else class="cart_table empty_cart_div">
                <span class="fa fa-shopping-basket"></span>
                <span>سبد خرید شما خالی است !</span>
            </div>
        </div>

        <div class="message_div" v-if="show_dialog_box">
            <div class="message_box">
                <p id="msg">آیا مایل به حذف این محصول هستید ؟</p>
                <a class="alert alert-success" v-on:click="approve()">بله</a>
                <a class="alert alert-danger" v-on:click="show_dialog_box=false,selected_product=null">خیر</a>
            </div>
        </div>
    </div>

</template>

<script>
    import myMixin from "../myMixin";

    export default {
        name: "ShoppingCart",
        props: ['cart_data'],
        mixins: [myMixin],
        mounted() {
            this.CartProduct = this.cart_data;
        },
        data() {
            return {
                show_dialog_box: false,
                selected_product: null,
                CartProduct: {},
            }
        },
        methods: {
            remove_product: function (product) {
                this.selected_product = product;
                this.show_dialog_box = true;

            },
            approve: function () {
                $("#loading_box").show();
                this.show_dialog_box = false;
                const url = this.$siteUrl + 'site/cart/remove_product';
                const formData = new FormData();
                formData.append('product_id', this.selected_product.product_id);
                formData.append('warranty_id', this.selected_product.warranty_id);
                if (this.selected_product.color_id != undefined) {
                    formData.append('color_id', this.selected_product.color_id);
                }
                this.axios.post(url, formData).then(responce => {
                    $("#loading_box").hide();

                    if (responce.data != "error") {
                        this.CartProduct = responce.data;
                        if (this.CartProduct.product){
                            $(".cart_product_count").text(this.replace_number(this.CartProduct.product.length));
                        } else {
                            $(".cart_product_count").hide();
                        }
                    }
                }).catch(error => {
                    $("#loading_box").hide();
                    console.log(error);
                });
                ;
            },
            change_product_count: function (product) {
                $("#loading_box").show();
                // product.product_count
                const url = this.$siteUrl + 'site/cart/change_product_cart';
                const formData = new FormData();
                formData.append('product_id', product.product_id);
                formData.append('warranty_id', product.warranty_id);
                if (product.color_id != undefined) {
                    formData.append('color_id', product.color_id);
                }
                formData.append('product_count', product.product_count);
                this.axios.post(url, formData).then(responce => {
                    $("#loading_box").hide();
                    if (responce.data != "error") {
                        this.CartProduct = responce.data;
                    }
                }).catch(error => {
                    $("#loading_box").hide();
                    console.log(error);
                });
            },
            check_has_off: function (product) {
                let time = Date.now();
                time = parseInt(time / 1000);
                if (product.offers_last_time > time && product.int_price2 < product.price1) {
                    return true;
                } else {
                    return false;
                }
            },
        }
    }
</script>

<style>
    .tooltip-inner {
        background: #68a5ff !important;
        text-align: right !important;

    }

    .tooltip .arrow:before {
        border-bottom-color: #68a5ff !important;
        border-top-color: #68a5ff !important;
    }
</style>