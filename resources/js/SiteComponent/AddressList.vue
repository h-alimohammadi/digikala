<template>
    <div>
        <button v-if="ListAddress.length==0" type="button" class="add_address_btn" v-on:click="show_modal_box()"
                data-target="#myModal">
            <strong>ایجاد آدرس جدید</strong>
        </button>

        <div class="address_box border_box" v-if="show_address_list">
            <div class="select_address_label">
                <span>آدرس مورد نظر خود را جهت تحویل انتخاب فرمایید :</span>
                <span class="fa fa-close" v-on:click="close_address_list()"></span>
            </div>
            <button type="button" class="add_address_btn" v-on:click="show_modal_box()" data-target="#myModal">
                <strong>ایجاد آدرس جدید</strong>
            </button>

            <div class="address_row" v-for="(address,key) in ListAddress" v-bind:key="address.id">
                <h6>{{address.name}}</h6>
                <div class="checkout_address">
                    <span>{{address.province.name}}</span>
                    <span>{{address.city.name}}</span>
                    <span>{{address.address}}</span>
                </div>
                <ul>
                    <li>
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
                    </li>
                    <li>
                        <ul>
                            <li>
                                <button class="address_btn" v-on:click="update_row(address)">ویرایش</button>
                            </li>
                            <li>
                                <button class="address_btn" v-on:click="remove_address(address)">حذف</button>
                            </li>
                        </ul>
                    </li>
                </ul>
                <button :class="[key==0 ? 'checkout_address_btn selected_address' : 'checkout_address_btn' ]"
                        v-on:click="change_default_address(key)">
                    <span v-if="key==0">سفارش به این آدرس ارسال میشود.</span>
                    <span v-else>ارسال سفارش به این آدرس</span>
                </button>
            </div>
        </div>
        <address-form @setData="updateAddressList" ref="data"></address-form>
            <div v-if="show_default_address()">
            <div class="address_row default_address">
                <div style="padding-right: 20px">
                    <ul>
                        <li>
                            <h6>{{ListAddress[0].name}}</h6>
                        </li>
                        <li>
                            <span class="data_link"  v-on:click="update_row(ListAddress[0])">اصلاح این آدرس</span>
                        </li>
                        <li class="change_address_btn">
                            <button class="address_btn" v-on:click="change_address()">تغییر آدرس ارسال</button>
                        </li>
                    </ul>
                    <div class="checkout_address">
                        <span>{{ListAddress[0].province.name}}</span>
                        <span>{{ListAddress[0].city.name}}</span>
                        <span>{{ListAddress[0].address}}</span>
                    </div>
                    <ul>
                        <li>
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
                        </li>
                    </ul>
                </div>
                <div class="checkout_contact">

                </div>
            </div>
        </div>
        <ordering-time v-if="city_id>0" v-bind:city_id="city_id"></ordering-time>
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
    import AddressForm from "./AddressForm";
    import myMixin from "../myMixin";
    import OrderingTime from "./OrderingTime";

    export default {
        name: "AddressList",
        components: {OrderingTime, AddressForm},
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
                this.$refs.data.setTitle('افزودن آدرس جدید');
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
                }).catch(error=>{
                    $("#loading_box").hide();
                });;
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
            },

        }
    }
</script>

<style scoped>

</style>