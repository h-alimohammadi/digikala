<template>
    <div v-if="ProductWarrantyList.length>1" class="ProductPriceList">
        <div :class="[key==0 ?'warranty_list active':'warranty_list']" v-for="(item,key) in ProductWarrantyList">
            <div>
                <span class="fa fa-home"></span>
                <a v-if="item.seller.id!=0" v-bind:href="$siteUrl+'seller/'+item.seller.id">
                    <span>{{item.seller.brand_name}}</span>
                </a>
                <a v-else>
                    <span>{{item.seller.brand_name}}</span>
                </a>
            </div>
            <div class="product_send_time">
                <span data-toggle="tooltip" data-placement="bottom" v-bind:title="get_time_message(item.send_time)"
                      v-if="item.send_time==0">
                    {{get_day(item.send_time)}}
                </span>
                <span data-toggle="tooltip" data-placement="bottom" v-bind:title="get_time_message(item.send_time)"
                      v-else>
                    {{get_day(item.send_time)}}
                </span>
            </div>
            <div>
                <span>{{ item.warranty.name }}</span>
            </div>
            <div class="price">
                {{replace_number(number_format(item.price2)) }} تومان
            </div>
            <div class="d-flex justify-content-center">
                <a class="btn-seller-add-cart" v-on:click="add_product(item.warranty_id)">
                    افزودن به سبد خرید
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import myMixin from '../myMixin';

    export default {
        name: "OtherPrice",
        props: ['product_id'],
        mixins: [myMixin],
        data() {
            return {
                color_id: 0,
                ProductWarrantyList: [],
                request_count: 0,
            }
        },
        mounted() {
            this.color_id = $('#color_id').val();
            const self = this;
            $(document).on('click', '.color_li', function () {
                self.color_id = $(this).attr('data');
                self.getProductWarranty();
            });
            this.getProductWarranty();
        },
        methods: {
            getProductWarranty: function () {
                $("#loading_box").show();
                this.request_count++;
                const url = this.$siteUrl + '/api/getProductWarranty';
                const formData = new FormData();
                formData.append('product_id', this.product_id);
                formData.append('color_id', this.color_id);
                this.axios.post(url, formData).then(response => {
                    this.request_count = 0;
                    this.ProductWarrantyList = response.data;
                    $("#loading_box").hide();
                    this.$nextTick(function () {
                        $("[data-toggle='tooltip']").tooltip();
                    });
                }).catch(error => {
                    $("#loading_box").hide();
                    console.log(error);
                    if (this.request_count < 2) {
                        this.getProductWarranty();
                    }
                });
            },
            get_time_message: function (day) {
                if (day == 0) {
                    return 'این کالا در حال حاضر در انبار دیجی آنلاین وجود، آماده پردازش و ارسال است.';
                } else {
                    return 'این کالا در انبار فروشنده موجود است، برای ارسل باید برای مدت زمان ذکر شده منتظر بمانید.';
                }
            },
            get_day: function (day) {
                if (day == 0) {
                    return 'آماده ارسال';
                } else {
                    return 'ارسال از ' + this.replace_number(day) + ' روز کاری آینده';
                }
            },
            add_product: function (id) {
                $('#warranty_id').val(id);
                $("#add_cart_form").submit();
            },

        }
    }
</script>

<style scoped>


</style>