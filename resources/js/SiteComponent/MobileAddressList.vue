<template>
    <div>
        <button v-if="ListAddress.length==0" type="button" class="add_address_btn" v-on:click="show_list_box2"
                data-target="#myModal">
            <strong>ایجاد آدرس جدید</strong>
        </button>
        <div v-if="ListAddress.length>0" class="address_list">
            <div class="product_item_box default_address">
                <h6>تحویل گیرنده : {{ ListAddress[0]['name'] }}</h6>
                <div class="address_content">
                    <div>آدرس : {{ListAddress[0]['province']['name']}} {{ListAddress[0]['city']['name']}}
                        {{ListAddress[0]['address']}}
                    </div>
                    <ul>
                        <li>
                            کد پستی :
                            <span>{{replace_number(ListAddress[0].zip_code)}}</span>
                        </li>
                        <li>
                            شماره همراه :
                            <span>{{replace_number(ListAddress[0].mobile)}}</span>
                        </li>
                    </ul>
                    <a class="show_other_item" v-on:click="show_list_box">
                        <span>تغییر آدرس ارسال</span>
                        <span class="fa fa-angle-left"></span>
                    </a>
                </div>
            </div>
        </div>
        <mobile-address-form @setData="updateAddressList" ref="data"></mobile-address-form>
        <div class="mobile_data_box">
            <div class="header">
                <span>انتخاب آدرس</span>
                <a role="button" v-on:click="hide_list_box">
                    <span>بازگشت</span>
                    <span class="fa fa-angle-left"></span>
                </a>
            </div>
            <div class="content">
                <button type="button" class="add_address_btn" v-on:click="show_list_box2">
                    <strong>ایجاد آدرس جدید</strong>
                </button>
                <div v-for="(address,key) in ListAddress">
                    <div class="product_item_box default_address">
                        <div class="address_item_header">
                            <h6>تحویل گیرنده : {{ address['name'] }}</h6>
                            <div>
                                <span class="fa fa-edit" v-on:click="update_row(address)"></span>
                                <span class="fa fa-trash-o" v-on:click="remove_address(address)"></span>
                            </div>
                        </div>
                        <div class="address_content">
                            <div>آدرس : {{address['province']['name']}} {{address['city']['name'] }}
                                {{address['address'] }}
                            </div>
                            <ul>
                                <li>
                                    کد پستی :
                                    <span>{{replace_number(address.zip_code)}}</span>
                                </li>
                                <li>
                                    شماره همراه :
                                    <span>{{replace_number(address.mobile)}}</span>
                                </li>
                            </ul>
                            <a class="show_other_item justify-content-center" v-on:click="show_list_box">
                                <span v-if="key==0" class="select_address_tag">سفارش به این آدرس ارسال میشود.</span>
                                <span class="select_address_tag" style="color: black" v-else
                                      v-on:click="change_default_address(key)">ارسال سفارش به این آدرس</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="message_div" v-if="show_dialog_box">
            <div class="message_box">
                <p id="msg">آیا مایل به حذف این آدرس هستید ؟</p>
                <a class="alert alert-success" v-on:click="delete_address()">بله</a>
                <a class="alert alert-danger" v-on:click="show_dialog_box=false">خیر</a>
            </div>
        </div>
    </div>
</template>

<script>
    import myMixin from "../myMixin";
    import OrderingTime from "./OrderingTime";
    import MobileAddressForm from "./MobileAddressForm";

    export default {
        name: "AddressList",
        components: {OrderingTime, MobileAddressForm},
        props: ['data'],
        mixins: [myMixin],
        data() {
            return {
                ListAddress: [],
                show_address_list: false,
                show_default: true,
                city_id: '',
                show_dialog_box: false,
                remove_address_id: '',
            }
        },
        mounted() {
            this.ListAddress = this.data;
            if (this.ListAddress.length > 0) {
                this.city_id = this.ListAddress[0].city_id;
                document.getElementById('address_id').value = this.ListAddress[0].id;
            }
        },
        methods: {
            show_modal_box: function () {
                $("#myModal").modal('show');
            },
            close_address_list: function (address) {
                this.show_address_list = false;
                this.show_default = true;
            },
            show_default_address: function () {
                if (this.ListAddress.length > 0 && this.show_default) {
                    return true;
                } else {
                    return false;
                }
            },
            update_row: function (address) {
                this.$refs.data.setUpdateData(address, 'ویرایش آدرس');
            },

            remove_address: function (address) {
                this.show_dialog_box = true;
                this.remove_address_id = address.id;
            },
            delete_address: function () {
                $("#loading_box").show();
                this.show_dialog_box = false;
                const url = this.$siteUrl + 'user/remove_address/' + this.remove_address_id;
                this.axios.delete(url).then(response => {
                    $("#loading_box").hide();
                    if (response.data != "error") {
                        this.ListAddress = response.data;
                    }
                }).catch(error => {
                    $("#loading_box").hide();
                });
                ;
            },
            change_address: function (address) {
                this.show_default = false;
                this.show_address_list = true;
            },
            updateAddressList: function (data) {
                this.ListAddress = data;
            },
            change_default_address: function (key) {
                let old_array = this.ListAddress;
                const first = old_array[0];
                const select = old_array[key];
                this.city_id = select.city_id;
                this.$set(this.ListAddress, 0, select);
                this.$set(this.ListAddress, key, first);
                this.show_address_list = false;
                this.show_default = true;
                document.getElementById('address_id').value = select.id;
                setTimeout(function () {
                    $(".mobile_data_box").css('top', '100%');
                    $('body').css('overflow-y', 'auto');
                }, 10);
            },
            show_more_address: function () {

            },
            show_list_box: function () {

                $('body').css('overflow-y', 'hidden');
                $(".mobile_data_box").css('top', '0');
            },
            hide_list_box: function () {
                setTimeout(function () {
                    $(".mobile_data_box").css('top', '100%');
                    $('body').css('overflow-y', 'auto');
                }, 200);
            },
            show_list_box2: function () {
                this.$refs.data.setTitle('ایجاد آدرس جدید');
                $('body').css('overflow-y', 'hidden');
                $(".mobile_data_box2").css('top', '0');
            },
        }
    }
</script>

<style scoped>

</style>