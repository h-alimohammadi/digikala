<template>
    <div class="offer_time_box" v-if="show_secound>0">
        <img class="offer_pic" v-bind:src="this.$siteUrl+'files/images/eccdcccd.png'">
        <div class="c-counter">
            <span>{{ h }}</span>:<span>{{ m }}</span>:<span>{{ s }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OfferTime",
        data() {
            return {
                h: '',
                m: '',
                s: '',
                show_secound: 0,
            }
        },
        mounted() {
            const self = this;
            if ($("#offer_time").attr('data') > 0) {
                self.show_secound = $('#offer_time').attr('data');
                self.counter();
                setInterval(self.counter, 1000);

            }
            $(document).on('click', '#offer_time', function () {
                if ($("#offer_time").attr('data') > 0) {
                    self.show_secound = $('#offer_time').attr('data');
                    self.counter();
                    setInterval(self.counter, 1000);
                }
            });
        },
        methods: {
            counter: function () {
                let second = this.show_secound;
                let h = Math.floor(second / 3600);
                second = second - h * 3600;
                let m = Math.floor(second / 60);
                let s = second - m * 60;
                if (h.toString().length == 1)
                    h = '0' + h;
                if (m.toString().length == 1)
                    m = '0' + m;
                if (s.toString().length == 1)
                    s = '0' + s;
                this.h = this.replace_number(h);
                this.m = this.replace_number(m);
                this.s = this.replace_number(s);
                --this.show_secound;
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
        },
    }
</script>

<style scoped>

</style>