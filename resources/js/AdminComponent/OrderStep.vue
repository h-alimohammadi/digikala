<template>
    <div>
        <div class="swiper-container order_steps">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="(step,key) in steps" v-if="key > -1">
                    <div :class="[order_status < key ? 'step_div step_inactive' : 'step_div']"
                         v-on:click="change_status(key)">
                        <img v-bind:src="$siteUrl+'files/images/step'+key+'.svg'"
                             :style="[key==6 ? {width:'65%'} : {} ]">
                        <span>{{ step }}</span>
                    </div>
                    <hr v-if="key<6" :class="[order_status > key ? 'hr_active' : '']">
                    <div v-else style="min-width: 66px"></div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <div class="message_div" style="display: block" v-if="show_box">
            <div class="message_box">
                <p id="msg">آیا از تغییر وضعیت مرسوله مطمئن هستید؟</p>
                <a class="alert alert-success" v-on:click="send_data()">بله</a>
                <a class="alert alert-danger" v-on:click="show_box=false">خیر</a>
            </div>
        </div>
        <div class="error_dialog">
            <span>خطا در ارسال اطلاعات - مجددا تلاش نمایید</span>
        </div>

    </div>
</template>

<script>
    export default {
        name: "OrderStep",
        props: ['steps', 'send_status', 'order_id'],
        data() {
            return {
                show_box: false,
                status: 0,
                order_status: 0,
                error_dialog: false,
            }
        },
        mounted() {
            this.order_status = this.send_status;
        },
        methods: {
            change_status: function (status) {
                this.status = status;
                this.show_box = true;
            },
            send_data: function () {
                this.show_box = false;
                $("#loading_box").show();
                const formData = new FormData();
                formData.append('order_id', this.order_id);
                formData.append('status', this.status);
                const url = this.$siteUrl + 'admin/order/change_status';
                this.axios.post(url, formData).then(response => {
                    $("#loading_box").hide();
                    if (response.data == 'Ok') {
                        this.order_status = this.status;
                    } else {
                        this.error_dialog = true;
                        setTimeout(function () {
                            this.error_dialog = false;
                        }, 4000);
                    }
                }).catch(error => {

                    $("#loading_box").hide();
                    this.error_dialog = true;
                    setTimeout(function () {
                        this.error_dialog = false;
                    }, 4000);
                    console.log(error);
                });

            },
        }
    }
</script>

<style scoped>

</style>