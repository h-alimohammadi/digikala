<template>
    <div class="shipping_data_box">
        <p class="shipping_data_box_title">استفاده از کد تخفیف دیجی کالا</p>
        <p>با ثبت کد تخفیف، مبلغ کد تخفیف از مبلغ قابل پرداخت کسر میشود.</p>
        <div class="form-group d-flex">
            <input type="text" v-model="code" class="form-control discount_input ">
            <button v-on:click="send_code()" class="btn btn-success discount_btn">ثبت کد تخفیف</button>
        </div>
        <div v-if="success_message" class="alert alert-success">
            {{ success_message}}
        </div>
        <div v-if="error_message" class="alert alert-danger">
            {{error_message}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "DiscountBox",
        data() {
            return {
                code: '',
                error_message: false,
                success_message: false,
            }
        },
        methods: {
            send_code: function () {
                if (this.code.trim() != '') {
                    $("#loading_box").show();
                    const url = this.$siteUrl + 'site/check-discount-code';
                    const formData = new FormData();
                    formData.append('code', this.code);
                    this.axios.post(url, formData).then(response => {
                        $("#loading_box").hide();
                        if (typeof response.data.status != "undefined") {
                            if (response.data.status = 'Ok') {
                                const discount_value = response.data.discount_value;
                                const cart_final_price = response.data.cart_final_price;
                                $(".discount_li").show();
                                $("#discount_cart_amount").text(discount_value);
                                $("#final_price").text(cart_final_price);
                                this.error_message = false;
                                this.success_message = 'کد تخفیف وارد شده صحیح میباشد و مبلغ کد تخفیف از هزینه کسر شد.';
                                this.code='';
                            }
                        } else {
                            this.success_message = false;
                            this.error_message = response.data;
                        }
                    }).catch(error => {
                        $("#loading_box").hide();
                        this.error_message = 'خطا در ارسال اطلاعات، مجددا تلاش نمایید.';
                        console.log(error);
                    });
                } else {
                    $("#loading_box").hide();
                    this.error_message = 'لطفا کد تخفیف را وارد نمایید.';
                }
            },
        }
    }
</script>

<style scoped>

</style>