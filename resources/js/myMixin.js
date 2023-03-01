export default {
    methods: {
        replace_number: function (n) {
            n = n.toString();
            let find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
            let replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
            for (let i = 0; i < find.length; i++) {
                n = n.replace(new RegExp(find[i], 'g'), replace[i]);
            }
            return n;
        },
        check_mobile_number: function (mobile) {
            if (isNaN(mobile)) {
                return true;
            } else {
                if (mobile.toString().trim().length == 11) {
                    if (mobile.toString().trim().charAt(0) == '0' && mobile.toString().trim().charAt(1) == '9') {
                        return false;
                    } else
                        return true;
                } else if (mobile.toString().trim().length == 10) {
                    if (mobile.toString().trim().charAt(0) == '9') {
                        return false;
                    } else
                        return true;
                } else {
                    return true;
                }
            }
        },
        number_format: function (num) {
            num = num.toString();
            let format = '';
            let counter = 0;
            for (let i = num.length - 1; i >= 0; i--) {
                format += num[i];
                counter++;
                if (counter == 3){
                    format+=',';
                    counter=0;
                }
            }
            return format.split('').reverse().join('');
        },
        gregorian_to_jalali:function (gy, gm, gd) {
            var g_d_m, jy, jm, jd, gy2, days;
            g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
            gy2 = (gm > 2) ? (gy + 1) : gy;
            days = 355666 + (365 * gy) + ~~((gy2 + 3) / 4) - ~~((gy2 + 99) / 100) + ~~((gy2 + 399) / 400) + gd + g_d_m[gm - 1];
            jy = -1595 + (33 * ~~(days / 12053));
            days %= 12053;
            jy += 4 * ~~(days / 1461);
            days %= 1461;
            if (days > 365) {
                jy += ~~((days - 1) / 365);
                days = (days - 1) % 365;
            }
            if (days < 186) {
                jm = 1 + ~~(days / 31);
                jd = 1 + (days % 31);
            } else {
                jm = 7 + ~~((days - 186) / 30);
                jd = 1 + ((days - 186) % 30);
            }
            return [jy, jm, jd];
        },
        getProvince: function () {
            this.axios.get(this.$siteUrl + 'api/get_province').then(response => {
                this.province = response.data;
                setTimeout(function () {
                    $("#province_id").selectpicker('refresh');
                }, 100);
            })
        },
        getCity: function (id) {
            this.city_id = id;
            if (this.province != '') {
                this.city = [];
                this.axios.get(this.$siteUrl + 'api/get_city/' + this.province_id).then(response => {
                    this.city = response.data;
                    setTimeout(function () {
                        $("#city_id").selectpicker('refresh');
                    }, 100);
                })
            }
        },
        validateName: function () {
            if (this.name.toString().trim() == "") {
                this.error_name_message = 'نام و نام خانوادگی نمیتواند خالی باشد.';
                return false;
            } else if (this.name.toString().trim().length < 6) {
                this.error_name_message = 'نام و نام خانوادگی باید حداقل 6 کاراکتر باشد.';
                return false;
            } else {
                this.error_name_message = false;
                return true;
            }
        },
        validateMobileNumber: function () {
            if (this.mobile.toString().trim() == "") {
                this.error_mobile_message = 'لطفا شماره موبایل خود را وارد کنید.';
                return false;
            } else if (this.check_mobile_number(this.mobile)) {
                this.error_mobile_message = 'شماره موبایل وارد شده معتبر نمی باشد.';
                return false;
            } else {
                this.error_mobile_message = false;
                return true;
            }
        },
        validateAddress: function () {
            if (this.address.toString().trim() == "") {
                this.error_address_message = 'آدرس نمیتواند خالی باشد.';
                return false;
            } else if (this.address.toString().trim().length < 20) {
                this.error_address_message = 'آدرس وارد شده کوتاه است';
                return false;
            } else {
                this.error_address_message = false;
                return true;
            }
        },
        validateZipCode: function () {
            if (this.zip_code.toString().trim() == "") {
                this.error_zip_code_message = 'کد پستی نمیتواند خالی باشد.';
                return false;
            } else if (this.address.toString().trim().length < 10 || isNaN(this.zip_code || this.address.toString().trim().length > 10)) {
                this.error_zip_code_message = 'کد پستی  معتبر نمیباشد.';
                return false;
            } else {
                this.error_zip_code_message = false;
                return true;
            }
        },
        validateProvince: function () {
            if (this.province_id.toString().trim() == "") {
                this.error_province_id_message = 'لطفا استان را انتخاب کنید.';
                return false;
            } else {
                this.error_province_id_message = false;
                return true;
            }
        },
        validateZipCity: function () {
            if (this.city_id.toString().trim() == "") {
                this.error_city_id_message = 'لطفا شهر را انتخاب کنید.';
                return false;
            } else {
                this.error_city_id_message = false;
                return true;
            }
        },
        setTitle: function (title) {
            this.title = title;
            this.btn_text='ثبت و ارسال به این آدرس';
            this.id = '';
            this.name = '';
            this.mobile = '';
            this.province_id = '';
            this.city_id = '';
            this.address = '';
            this.zip_code = '';
            this.getProvince();
            this.city = [];
            setTimeout(function () {
                $("#city_id").selectpicker('refresh');
            }, 100);
        },
    }
}