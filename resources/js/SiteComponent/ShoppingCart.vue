<template>
    <div>
        <div v-if="CartProduct.product != undefined && CartProduct.product.length>0">
            <div class="page_row">
                <div class="page_content">
                    <table class="cart_table">
                        <tr v-for="product in CartProduct['product']">
                            <td><span class="fa fa-close remove_product" v-on:click="remove_product(product)"></span>
                            </td>
                            <td>
                                <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+product.product_image_url">
                            </td>
                            <td>
                                <ul>
                                    <li class="title">
                                        <a href="">{{product.product_title}}</a>
                                    </li>
                                    <li>
                                        {{product.warranty_name}}
                                    </li>
                                    <li v-if="product.color_name != undefined">
                                        <span>رنگ : </span>
                                        <span>{{ product.color_name }}</span>
                                        <span class="ui_variant_shape" v-bind:style="{background: product.color_code} "></span>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <span>تعداد</span>
                                <p v-if="product.product_number_cart>1">
                                    <select class="selectpicker" v-model="product.product_count" v-on:change="change_product_count(product)">
                                        <option v-for="i in product.product_number_cart" v-bind:value="i">
                                            {{replace_number(i)}}
                                        </option>
                                    </select>
                                </p>
                                <p v-else>{{ replace_number(product.product_count) }}</p>
                            </td>
                            <td>
                                {{ product.price1 }} تومان
                            </td>
                        </tr>
                    </table>
                </div>
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
                        <a v-bind:href="$siteUrl+'shipping'">
                            <div class="send_btn checkout">
                                <span class="line"></span>
                                <span class="title">ادامه ثبت سفارش</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="cart_table empty_cart_div">
            <span class="fa fa-shopping-basket"></span>
            <p>سبد خرید شما خالی است !</p>
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
                this.show_dialog_box = false;
                const url = this.$siteUrl + 'site/cart/remove_product';
                const formData = new FormData();
                formData.append('product_id', this.selected_product.product_id);
                formData.append('warranty_id', this.selected_product.warranty_id);
                if (this.selected_product.color_id != undefined) {
                    formData.append('color_id', this.selected_product.color_id);
                }
                this.axios.post(url, formData).then(responce => {
                    if (responce.data != "error") {
                        this.CartProduct = responce.data;
                    }
                });
            },
            change_product_count: function (product) {
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
                    if (responce.data != "error") {
                        this.CartProduct = responce.data;
                    }
                });
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