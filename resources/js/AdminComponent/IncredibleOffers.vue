<template>
    <div>
        <div class="search_form">
            <div class="btn-group">
                <input v-model="search_text" type="text" name="string" class="form-control"
                       placeholder="عنوان محصول...">
                <button class="btn btn-primary" v-on:click="getProductWarrantyList(1)">جست و جو</button>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ردیف</td>
                <td>تصویر</td>
                <td>عنوان محصول</td>
                <td>فروشنده</td>
                <td>گارانتی</td>
                <td>رنگ</td>
                <td>عملیات</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(productWarranty,key) in ProductWarrantyList.data">
                <td>{{get_row(key)}}</td>
                <td>
                    <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+productWarranty.product.image_url"
                         class="product_pic">
                </td>
                <td style="font-size: 14px">{{productWarranty.product.title}}</td>
                <td></td>
                <td style="font-size: 14px">{{productWarranty.warranty.name}}</td>
                <td style="width: 100px">
                    <span>
                        <span class="color-span" v-if="productWarranty.color.id>0"
                              v-bind:style="[productWarranty.color.id>0 ? {background:productWarranty.color.code}:{},productWarranty.color.name == 'مشکی'? {background: 'white',color:'black'}:{}]">
                            {{productWarranty.color.name}}
                        </span>
                    </span>
                </td>
                <td style="width: 100px">
                    <p class="select_item" v-on:click="show_item(productWarranty.id,key)">انتخاب</p>
                    <p v-if="productWarranty.offers == 1" class="remove_item"
                       v-on:click="remove_offers(productWarranty.id,key)">حذف</p>
                </td>
            </tr>
            </tbody>
        </table>
        <pagination :data="ProductWarrantyList" @pagination-change-page="getProductWarrantyList"/>

        <div class="modal fade" id="productWarrantyPrice" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>افزودن به لیست پیشنهاد شگفت انگیز</h5>
                        <button type="button" class="close" data-dismiss="modal">⨯</button>
                    </div>
                    <div class="modal-body">
                        <div v-if="server_error" class="alert alert-warning p-0 pb-0">
                            <ul class="list-inline m-1">
                                <li class="has_error pb-1" v-for="error in server_error">{{error[0]}}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>هزینه محصول :</label>
                            <example-component v-model="formInput.price1" :options="options"
                                               class="form-control left"></example-component>
                            <span class="has_error" v-if="errors.price1">{{errors.price1}}</span>
                        </div>
                        <div class="form-group">
                            <label>هزینه محصول برای فروش :</label>
                            <example-component v-model="formInput.price2" :options="options"
                                               class="form-control left"></example-component>
                            <span class="has_error" v-if="errors.price2">{{errors.price2}}</span>

                        </div>
                        <div class="form-group">
                            <label>تعداد موجودی محصول :</label>
                            <example-component v-model="formInput.product_number" :options="options"
                                               class="form-control left"></example-component>
                            <span class="has_error" v-if="errors.product_number">{{errors.product_number}}</span>

                        </div>
                        <div class="form-group">
                            <label>تعداد قابل سفارش در سبد خرید :</label>
                            <example-component v-model="formInput.product_number_cart" :options="options"
                                               class="form-control left"></example-component>
                            <span class="has_error"
                                  v-if="errors.product_number_cart">{{errors.product_number_cart}}</span>

                        </div>
                        <div class="form-group">
                            <label>تاریخ شروع :</label>
                            <input type="text" v-model="date1" id="pcal1" class="form-control">
                            <!--                            <span class="has_error" v-if="errors.date1">{{errors.date1}}</span>-->
                        </div>
                        <div class="form-group">
                            <label>تاریخ پایان :</label>
                            <input type="text" v-model="date2" id="pcal2" class="form-control">
                            <!--                            <span class="has_error" v-if="errors.date2">{{errors.date2}}</span>-->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" v-on:click="add()">افزودن</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="message_div d-block" v-if="show_message_box">
            <div class="message_box">
                <p id="msg">آیا از حذف این محصول از لیست پیشنهاد شگفت انگیز مطمئن هستید؟</p>
                <a class="alert alert-success" v-on:click="remove_of_list()">بله</a>
                <a class="alert alert-danger" v-on:click="show_message_box = !show_message_box">خیر</a>
            </div>
        </div>

    </div>
</template>


<script>
    export default {
        name: "Test",
        data() {
            return {
                ProductWarrantyList: {data: []},
                page: 1,
                options: {
                    numeral: true,
                    delimiter: ',',
                },
                formInput: {
                    price1: '',
                    price2: '',
                    product_number: '',
                    product_number_cart: '',
                },
                date1: '',
                date2: '',
                select_key: -1,
                productWarranty_id: -1,
                send_form: true,
                show_message_box: false,
                errors: {
                    price1: false,
                    price2: false,
                    product_number: false,
                    product_number_cart: false,
                    date1: false,
                    date2: false,
                },
                lable: {
                    price1: 'هزینه محصول',
                    price2: 'هزینه محصول برای فروش',
                    product_number: 'تعداد موجودی محصول',
                    product_number_cart: 'تعداد قابل سفارش در سبد خرید',
                    date1: 'تاریخ شروع',
                    date2: 'تاریخ پایان',
                },
                search_text: '',
                server_error: null,
            }
        },
        mounted() {
            this.getProductWarrantyList(1);
        },
        methods: {
            getProductWarrantyList: function (page) {
                this.page = page;
                const url = this.$siteUrl + 'admin/ajax/getProductWarranty?page=' + page + '&search_text=' + this.search_text;
                this.axios.get(url).then(response => {
                    this.ProductWarrantyList = response.data;
                })
            },
            get_row: function (index) {
                ++index;
                let k = (this.page - 1) * 10;
                k += index;
                return this.replace_number(k);
            },
            replace_number: function (n) {
                n = n.toString();
                let find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
                let replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
                for (let i = 0; i < find.length; i++) {
                    n = n.replace(new RegExp(find[i], 'g'), replace[i]);
                }
                return n;
            },
            show_item: function (item_id, key) {
                if (this.send_form == true) {
                    this.server_error = false;
                    this.productWarranty_id = item_id;
                    this.select_key = key;
                    this.formInput.price1 = this.ProductWarrantyList.data[key].price1;
                    this.formInput.price2 = this.ProductWarrantyList.data[key].price2;
                    this.formInput.product_number = this.ProductWarrantyList.data[key].product_number;
                    this.formInput.product_number_cart = this.ProductWarrantyList.data[key].product_number_cart;
                    this.date1 = this.ProductWarrantyList.data[key].offers_first_date;
                    this.date2 = this.ProductWarrantyList.data[key].offers_last_date;
                    $('#productWarrantyPrice').modal('show');
                }
            },
            add: function () {
                this.date1 = $("#pcal1").val();
                this.date2 = $("#pcal2").val();
                if (this.validation()) {
                    this.send_form = false;
                    const formdata = new FormData();
                    formdata.append('price1', this.formInput.price1);
                    formdata.append('price2', this.formInput.price2);
                    formdata.append('product_number', this.formInput.product_number);
                    formdata.append('product_number_cart', this.formInput.product_number_cart);
                    formdata.append('date1', this.date1);
                    formdata.append('date2', this.date2);
                    const url = this.$siteUrl + "admin/add-incredible-offers/" + this.productWarranty_id;
                    this.axios.post(url, formdata).then(response => {
                        if (response.data == 'Ok') {
                            this.send_form = true;
                            $('#productWarrantyPrice').modal('hide');
                            this.ProductWarrantyList.data[this.select_key].offers = 1;
                            this.ProductWarrantyList.data[this.select_key].price1 = this.formInput.price1;
                            this.ProductWarrantyList.data[this.select_key].price2 = this.formInput.price2;
                            this.ProductWarrantyList.data[this.select_key].product_number = this.formInput.product_number;
                            this.ProductWarrantyList.data[this.select_key].product_number_cart = this.formInput.product_number_cart;
                            this.ProductWarrantyList.data[this.select_key].offers_first_date = this.date1;
                            this.ProductWarrantyList.data[this.select_key].offers_last_date = this.date2;
                        } else if (response.data.error != undefined) {
                            this.send_form = true;

                        } else {
                            this.server_error = response.data;
                            this.send_form = true;

                        }
                    });
                }
            },
            remove_offers: function (item_id, key) {
                this.productWarranty_id = item_id;
                this.select_key = key;
                this.show_message_box = true;

            },
            remove_of_list: function () {
                this.show_message_box = false;
                const url = this.$siteUrl + "admin/remove-incredible-offers/" + this.productWarranty_id;
                this.axios.post(url).then(response => {
                    if (response.data.price1) {
                        this.send_form = true;
                        this.ProductWarrantyList.data[this.select_key].offers = 0;
                        this.ProductWarrantyList.data[this.select_key].price1 = response.data.price1;
                        this.ProductWarrantyList.data[this.select_key].price2 = response.data.price2;
                        this.ProductWarrantyList.data[this.select_key].product_number = response.data.product_number;
                        this.ProductWarrantyList.data[this.select_key].product_number_cart = response.data.product_number_cart;
                    }

                    // alert(response.data);
                });
            },
            validation: function () {
                let valid = true;
                for (let formInputKey in this.formInput) {
                    if (this.formInput[formInputKey].toString().trim().length == 0) {
                        this.errors[formInputKey] = this.lable[formInputKey] + ' نمی تواند خالی باشد.';
                        valid = false;
                    } else {
                        this.errors[formInputKey] = false;
                        valid = true;
                    }
                }

                return valid;
            },

        }
    }

</script>

<style scoped>
    .message_box {
        width: 500px;
    }

    .has_error {
        /*font-size: 10px;*/
    }
</style>