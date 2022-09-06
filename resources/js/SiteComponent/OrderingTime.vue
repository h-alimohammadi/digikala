<template>
    <div>
        <div v-if="multi_type_send">
            <h6>انتخاب نحوه ارسال</h6>
            <div class="shipping_data_box">
                <p v-on:click="set_normal_send()">
                    <span :class="[normal_send ? 'radio_check active_radio_check' : 'radio_check']"></span>
                    <span>عادی</span>
                </p>
                <p v-on:click="set_fast_send()">
                    <span :class="[fast_send ? 'radio_check active_radio_check' : 'radio_check']"></span>
                    <span>سریع (مرسوله ها در سریع ترین زمان ممن ارسال میشوند.)</span>
                </p>
            </div>
        </div>

        <div v-if="normal_send" class="shipping_data_box pr-0 pl-0">
            <div class="swiper_product_box">

                <swiper :options="swiperOption">
                    <swiper-slide v-for="(product,i) in OrderingData.cart_product_data" :key="i"
                                  class="product_info_box">
                        <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+product.product_image_url">
                        <p>{{product.product_title}}</p>
                    </swiper-slide>
                    <div class="swiper-button-next" slot="button-next"></div>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                </swiper>

            </div>
            <div>
                <span class="checkout_image"></span>
                <div class="checkout_time">
                    <p>
                        <span>بازه تحویل سفارش :</span>
                        <span>{{OrderingData.min_ordering_day}}</span>
                        <span>تا </span>
                        <span>{{OrderingData.max_ordering_day}}</span>
                    </p>
                    <span>شیوه ارسال : پست پیشتاز با ظرفیت اختصاصی برای دیجی کالا</span>
                    <span>هزینه ارسال :</span>
                    <span>{{OrderingData.normal_send_order_amount}}</span>
                </div>
            </div>
        </div>

        <div v-if="fast_send" v-for="(delivery_order_interval,key) in OrderingData.delivery_order_interval">
            <p>
                <span>مرسوله</span>
                <span>{{replace_number(key+1)}}</span>
                <span>از</span>
                <span>{{OrderingData.delivery_order_interval.length}}</span>
            </p>
            <div class="shipping_data_box pr-0 pl-0">
                <div class="swiper_product_box">
                    <swiper :options="swiperOption">
                        <swiper-slide v-for="(data,key2) in OrderingData.array_product_id[key]" v-bind:key="key2"
                                      class="product_info_box">
                            <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+OrderingData.cart_product_data[data+'_'+key2].product_image_url">
                            <p>{{OrderingData.cart_product_data[data+'_'+key2].product_title}}</p>
                        </swiper-slide>
                        <div class="swiper-button-next" slot="button-next"></div>
                        <div class="swiper-button-prev" slot="button-prev"></div>
                    </swiper>
                </div>
                <div>
                    <span class="checkout_image"></span>
                    <div class="checkout_time">
                        <p>
                            <span>بازه تحویل سفارش :</span>
                            <span>{{delivery_order_interval.dayLabel1}}</span>
                            <span>تا </span>
                            <span>{{delivery_order_interval.dayLabel2}}</span>
                        </p>
                        <span>شیوه ارسال : پست پیشتاز با ظرفیت اختصاصی برای دیجی کالا</span>
                        <span>هزینه ارسال :</span>
                        <span>{{delivery_order_interval.send_fast_price}}</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="shipping_data_box" style="padding: 20px 20px 15px 30px;">
            <input type="checkbox" checked="checked" class="form-check-input" name="need-invoice">
            <span class="check_box active" id="need_invoice"></span>
            <span style="padding-right: 10px">درخواست ارسال فاکتور خرید</span>
        </div>
        <ul class="checkout_action">
            <li>
                <a class="data_link" v-bind:href="$siteUrl+'Cart'">
                    بازگشت به سبد خرید
                </a>
            </li>
            <li>
                <a class="data_link" v-bind:href="$siteUrl+'payment'">
                    تایید و ادامه ثبت سفارش
                </a>
            </li>
        </ul>


    </div>

</template>

<script>
    import myMixin from "../myMixin";
    import {swiper, swiperSlide} from 'vue-awesome-swiper';
    import 'swiper/dist/css/swiper.css';

    export default {
        name: "OrderingTime",
        props: ['city_id'],
        components: {
            swiper,
            swiperSlide,
        },
        mixins: [myMixin],
        data() {
            return {
                OrderingData: [],
                multi_type_send: false,
                normal_send: true,
                fast_send: false,
                swiperOption: {
                    spaceBetween: 30,
                    slidesPerView: 4,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    }
                },
            }
        },
        mounted() {
            this.get_ordering_time();
        },
        methods: {
            get_ordering_time: function () {
                this.axios.get(this.$siteUrl + "shipping/getSendData/" + this.city_id).then(respnse => {
                    this.OrderingData = respnse.data;
                    console.log(this.OrderingData);
                    if (this.OrderingData.delivery_order_interval.length > 1) {
                        this.multi_type_send = true;
                    }
                    this.setPrice();
                });
            },
            set_normal_send: function () {
                this.normal_send = true;
                this.fast_send = false;
                this.setPrice();
                document.getElementById('sent_type').value = 1;
            },
            set_fast_send: function () {
                this.normal_send = false;
                this.fast_send = true;
                this.setPrice();
                document.getElementById('sent_type').value = 2;
            },
            setPrice: function () {
                if (this.normal_send) {
                    $("#total_send_order_price").text(this.OrderingData.normal_send_order_amount);
                    $("#final_price").text(this.OrderingData.normal_cart_price);
                } else {
                    $("#total_fast_order_price").text(this.OrderingData.total_fast_send_amount);
                    $("#final_price").text(this.OrderingData.fasted_cart_amount);


                }
            }
        },
        watch: {
            city_id: function (newVal, oldVal) {
                this.city_id = newVal;
                this.get_ordering_time();
                this.setPrice();
            }
        }
    }
</script>

<style scoped>

</style>