<template>
    <div>
        <div class="mobile_data_box2" style="z-index: 99999;">
            <div class="header">
                <span>{{ title }}</span>
                <a role="button" v-on:click="hide_list_box2">
                    <span>بازگشت</span>
                    <span class="fa fa-angle-left"></span>
                </a>
            </div>
            <div class="content">
                <div class="profile_item">
                    <div id="map" style="width: 100%;height: 300px"></div>
                    <button class="btn btn-success" id="select_location_btn">انتخاب</button>
                </div>
                <div class="profile_item" id="add_address_box">
                    <div class="form-group">
                        <div class="account_title">نام و نام خانوادگی تحویل گیرنده :</div>
                        <label class="input_label">
                            <input type="text" v-model="name"
                                   class="form-control"
                                   placeholder="نام و نام خانوادگی تحویل گیرنده ...">
                            <label v-if="error_name_message"
                                   :class="[error_name_message ? 'feedback-hint active' : 'feedback']">{{error_name_message}}</label>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="account_title">شماره موبایل</div>
                        <label class="input_label">
                            <input type="text" v-model="mobile" class="form-control"
                                   placeholder="شماره موبایل...">
                            <label v-if="error_mobile_message"
                                   :class="[error_mobile_message ? 'feedback-hint active' : 'feedback']">{{error_mobile_message}}</label>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="account_title">استان :</div>
                        <label class="input_label">
                            <select class="selectpicker form-control" data-live-search="true"
                                    v-model="province_id"
                                    id="province_id" v-on:change="getCity('')">
                                <option value="">انتخاب استان</option>
                                <option v-for="row in province" v-bind:value="row.id">{{ row.name
                                    }}
                                </option>
                            </select>
                            <label v-if="error_province_id_message"
                                   :class="[error_province_id_message ? 'feedback-hint active' : 'feedback']">{{error_province_id_message}}</label>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="account_title">شهر :</div>
                        <label class="input_label">
                            <select class="selectpicker form-control" data-live-search="true"
                                    v-model="city_id" id="city_id">
                                <option value="">انتخاب شهر</option>
                                <option v-for="row in city" v-bind:value="row.id">{{ row.name }}
                                </option>
                            </select>
                            <label v-if="error_city_id_message"
                                   :class="[error_city_id_message ? 'feedback-hint active' : 'feedback']">{{error_city_id_message}}</label>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="account_title">آدرس پستی :</div>
                        <label class="input_label">
                            <textarea class="textArea" v-model="address"></textarea>
                            <label v-if="error_address_message"
                                   :class="[error_address_message ? 'feedback-hint active' : 'feedback']">{{error_address_message}}</label>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="account_title">کد پستی :</div>
                        <label class="input_label">
                            <input type="text" v-model="zip_code" class="form-control"
                                   placeholder="کد پستی...">
                            <label v-if="error_zip_code_message"
                                   :class="[error_zip_code_message ? 'feedback-hint active' : 'feedback']">{{error_zip_code_message}}</label>
                        </label>
                    </div>
                    <button class="btn btn-primary" v-on:click="add_address()">{{ btn_text }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import myMixin from "../myMixin";

    export default {
        name: "AddressForm",
        mixins: [myMixin],
        data() {
            return {
                id: 0,
                name: "",
                mobile: "",
                province_id: "",
                city_id: "",
                address: "",
                zip_code: "",
                error_name_message: false,
                error_mobile_message: false,
                error_province_id_message: false,
                error_city_id_message: false,
                error_address_message: false,
                error_zip_code_message: false,
                province: [],
                city: [],
                title: 'افزودن آدرس جدید',
                btn_text : 'ثبت و ارسال به این آدرس',
            }
        },
        mounted() {
            this.getProvince();
        },
        methods: {
            add_address: function () {
                let validateName = this.validateName();
                let validateMobileNumber = this.validateMobileNumber();
                let validateAddress = this.validateAddress();
                let validateZipCode = this.validateZipCode();
                let validateProvince = this.validateProvince();
                let validateZipCity = this.validateZipCity();
                if (validateName && validateMobileNumber && validateAddress && validateZipCode && validateProvince && validateZipCity) {
                    $("#loading_box").show();
                    let lng = "0.0";
                    let lat = "0.0";
                    const formData = new FormData();
                    formData.append('id', this.id);
                    formData.append('name', this.name);
                    formData.append('mobile', this.mobile);
                    formData.append('province_id', this.province_id);
                    formData.append('city_id', this.city_id);
                    formData.append('address', this.address);
                    formData.append('zip_code', this.zip_code);
                    formData.append('lng', lng);
                    formData.append('lat', lat);
                    const url = this.$siteUrl + 'user/add_address';
                    this.axios.post(url, formData).then(response => {
                        $("#loading_box").hide();
                        if (response.data != "error") {
                            this.$emit('setData', response.data);
                            setTimeout(function () {
                                $(".mobile_data_box2").css('top', '100%');
                                $('body').css('overflow-y', 'auto');
                            }, 200);
                        }
                    }).catch(error => {
                        setTimeout(function () {
                            $(".mobile_data_box2").css('top', '100%');
                            $('body').css('overflow-y', 'auto');
                        }, 200);
                        console.log(error);
                    });
                }
            },
            setUpdateData: function (address, title) {
                this.title = title;
                this.btn_text = 'ویرایش';
                this.id = address.id;
                this.name = address.name;
                this.mobile = address.mobile;
                this.province_id = address.province_id;
                this.city_id = address.city_id;
                this.address = address.address;
                this.zip_code = address.zip_code;
                this.getProvince();
                if (this.province_id > 0) {
                    this.getCity(this.city_id);
                } else {
                    this.city = [];
                    setTimeout(function () {
                        $("#city_id").selectpicker('refresh');
                    }, 100);
                }
                this.error_name_message = false;
                this.error_mobile_message = false;
                this.error_province_id_message = false;
                this.error_city_id_message = false;
                this.error_address_message = false;
                this.error_zip_code_message = false;
                setTimeout(function () {
                    $(".mobile_data_box2").css('top', '0');
                    $('body').css('overflow-y', 'auto');
                }, 200);

            },
            hide_list_box2: function () {
                setTimeout(function () {
                    $(".mobile_data_box2").css('top', '100%');
                    $('body').css('overflow-y', 'auto');
                }, 200);
            },
        },
    }
</script>

<style scoped>

</style>