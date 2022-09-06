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
        }
    }
}