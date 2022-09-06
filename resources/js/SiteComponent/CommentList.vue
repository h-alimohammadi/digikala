<template>
    <div class="comment_box">
        <div class="row" v-if="comment_count>0">
            <h2>
                <div>
                    {{product_title}}
                </div>
                <div class="mr-2">
                    <span>|</span>
                    <span>{{ replace_number(5)}}/{{ replace_number(avg)}}</span>
                    <span>({{ replace_number(comment_count)}} نظر)</span>
                </div>

            </h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <ul class="rating_ul avg_ul" v-if="comment_count>1 && getServerData == 'ok'">
                    <li v-for="(item,key) in score_item">
                        <label>{{ item }}</label>
                        <div class="rating" v-bind:data-rate-digit="getLabel2(key)">
                            <div class="rating_value" v-bind:style="{width:getWidth2(key)+'%'}"></div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="comment_summery_note">
                    <span>شما میتوانید در مورد این کالا نظر بدهید.</span>
                    <p>
                        لازم است محتوای ارسالی منطبق برعرف و شئونات جامعه و با بیانی رسمی و عاری از لحن تند، تمسخرو
                        توهین باشد.
                    </p>
                    <a class="btn btn-primary" v-on:click="add_comment">افودن نظر جدید</a>
                </div>
            </div>
        </div>

        <div class="feq_filter">
            <p>نظرات کاربران</p>
            <ul class="feq_filter_item" data-title="مرتب سازی بر اساس :">
                <li :class="[ordering==1 ? 'active' : '']" v-on:click="set_ordering(1)">نظرات خریداران</li>
                <li :class="[ordering==2 ? 'active' : '']" v-on:click="set_ordering(2)">مفید ترین نظرات</li>
                <li :class="[ordering==3 ? 'active' : '']" v-on:click="set_ordering(3)">جدید ترین نظرات</li>
            </ul>
        </div>
        <div class="comment_div" v-for="(comment,key) in list.data">
            <div class="row">
                <div class="col-md-5">
                    <ul class="rating_ul">
                        <li v-for="(item,key2) in score_item">
                            <label>{{ item }}</label>
                            <div class="rating" v-bind:data-rate-digit="getLabel(key,key2)">
                                <div class="rating_value" v-bind:style="{width: getWidth(key,key2)+'%'}"></div>
                            </div>
                        </li>
                    </ul>
                    <div class="message_purchased" v-if="comment.order_id >0">
                        <a target="_blank">
                            <span class="fa fa-shopping-cart"></span>
                            خریدار محصول
                        </a>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="comment_header_box">
                        <span>{{ comment.title }}</span>
                        <p>
                            <span>توسط</span>
                            <span v-if="comment.get_user_info == null">ناشناس</span>
                            <span v-else>
                                {{ comment.get_user_info.first_name+' '+comment.get_user_info.last_name }}
                            </span>
                            <span>در تاریخ</span>
                            {{ getDate(comment.time) }}
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-0" v-if="comment.advantage.length > 0">
                            <span class="evaluation_label">نقاط قوت</span>
                            <ul class="evaluation_ul advantage">
                                <li v-for="advantage in comment.advantage" v-if="advantage!=''">
                                    <span>{{advantage}}</span></li>
                            </ul>
                        </div>
                        <div class="col-md-6" v-if="comment.disadvantage.length > 0">
                            <span class="evaluation_label">نقاط ضعف</span>
                            <ul class="evaluation_ul disadvantage">
                                <li v-for="disadvantage in comment.disadvantage" v-if="disadvantage!=''"><span>{{disadvantage}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="comment_text">{{comment.content}}</div>
                    <div class="footer_comment">
                        <div class="footer">
                            آیا این نظر برای شما مفید بود؟
                            <button class="btn_like" v-on:click="like(key,comment.id)"
                                    v-bind:data-count="replace_number(comment.like)">بله
                            </button>
                            <button class="btn_like dislike" v-on:click="dislike(key,comment.id,2)"
                                    v-bind:data-count="replace_number(comment.dislike)">خیر
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="paginate_div">
            <pagination :data="list" @pagination-change-page="getList"/>
        </div>
        <div v-if="comment_count==0 && getServerData == 'ok'">
            <p style="text-align: center;padding-top: 40px;padding-bottom: 20px; color: red;">تا کنون برای این محصول
                نظری ثبت نشده است.</p>
        </div>
    </div>
</template>

<script>
    import myMixin from "../myMixin";

    export default {
        name: "CommentList",
        props: ['auth', 'product_id', 'product_title'],
        mixins: [myMixin],
        data() {
            return {
                list: {data: []},
                avg: 0,
                comment_count: 0,
                avg_score: [],
                ordering: 1,
                getServerData:'no',
                score_item: [
                    'کیفیت ساخت :',
                    'نوآوری :',
                    'سهولت استفاده :',
                    'سهولت طراحی و ظاهر :',
                    'امکانات و قابلیت ها :',
                    'ارزش خرید به نسبت قیمت :',
                ],
                scoreLabel: [
                    'خیلی بد',
                    'بد',
                    'معمولی',
                    'خوب',
                    'عالی',
                ],
                mountName: [
                    'فروردین',
                    'اردیبهشت',
                    'خرداد',
                    'تیر',
                    'مرداد',
                    'شهریور',
                    'مهر',
                    'آبان',
                    'آذر',
                    'دی',
                    'بهمن',
                    'اسفند',
                ],
                send: true,
            }
        },
        mounted() {
            const self = this;
            $("#contact-tab").click(function () {
                if (self.list.data.length == 0) {
                    self.getList();
                }
            });
        },
        methods: {
            getList: function (page = 1) {
                $("#loading_box").show();
                const url = this.$siteUrl + 'site/getComment?page=' + page + '&product_id=' + this.product_id + "&orderBy=" + this.ordering;
                this.axios.get(url,).then(response => {
                    $("#loading_box").hide();
                    this.list = response.data.comment;
                    this.avg = response.data.avg;
                    this.comment_count = response.data.comment_count;
                    this.avg_score = response.data.avg_score;
                }).catch(error => {
                    this.getServerData='ok';
                    $("#loading_box").hide();

                });
            },
            getLabel2: function (key) {
                let score = this.avg_score[key];
                if (score >= 0 && score < 0.5) {
                    return 'خیلی بد';
                } else if (score >= 0.5 && score < 1) {
                    return 'بد';
                } else if (score >= 1 && score < 2.5) {
                    return 'معمولی';
                } else if (score >= 2.5 && score < 3.6) {
                    return 'خوب';
                } else if (score >= 3.6) {
                    return 'عالی';
                }
            },
            getWidth2: function (key) {
                let score = this.avg_score[key];
                score = (score * 25);
                return score;
            },
            getWidth: function (key, key2) {
                key2 = key2 + 1;
                const a = "score" + key2;
                if (this.list.data[key]['get_score'][a] != undefined) {
                    return (this.list.data[key]['get_score'][a] * 25);
                } else {
                    return 50;
                }
            },
            getLabel: function (key, key2) {
                key2 = key2 + 1;
                const a = "score" + key2;
                if (this.list.data[key]['get_score'][a] != undefined) {
                    return this.scoreLabel[this.list.data[key]['get_score'][a]];
                } else {
                    return 'معمولی';
                }
            },
            add_comment: function () {
                if (this.auth == 'no') {
                    window.location = this.$siteUrl + 'login';
                } else {
                    window.location = this.$siteUrl + 'product/comment/' + this.product_id
                }
            },
            getDate: function (time) {
                time = time * 1000;
                const date = new Date(time);
                const jalali = this.gregorian_to_jalali(date.getFullYear(), (date.getMonth() + 1), date.getDate());
                const r = this.replace_number(jalali[2]) + ' ' + this.mountName[(jalali[1] - 1)] + ' ' + this.replace_number(jalali[0]);
                return r;
            },
            like: function (key, comment_id) {
                if (this.send == true) {
                    this.send = false;
                    $("#loading_box").show();
                    const url = this.$siteUrl + 'user/LikeComment';
                    const formData = new FormData();
                    formData.append('comment_id', comment_id);
                    this.axios.post(url, formData).then(response => {
                        console.log(response.data);
                        this.send = true;
                        $("#loading_box").hide();
                        if (response.data == 'add') {
                            this.list.data[key].like = this.list.data[key].like + 1;
                        } else if (response.data == 'remove') {
                            this.list.data[key].like = this.list.data[key].like - 1;
                        }
                    }).catch(error => {
                        $("#loading_box").hide();
                        this.send = true;
                        if (error.response.status == 401){
                            $("#login_box").show();
                        }
                        console.log(error);

                    });
                }
            },
            dislike: function (key, comment_id) {
                if (this.send == true) {
                    this.send = false;
                    $("#loading_box").show();
                    const url = this.$siteUrl + 'user/disLikeComment';
                    const formData = new FormData();
                    formData.append('comment_id', comment_id);
                    this.axios.post(url, formData).then(response => {
                        console.log(response.data);
                        this.send = true;
                        $("#loading_box").hide();
                        if (response.data == 'add') {
                            this.list.data[key].dislike = this.list.data[key].dislike + 1;
                        } else if (response.data == 'remove') {
                            this.list.data[key].dislike = this.list.data[key].dislike - 1;
                        }
                    }).catch(error => {
                        $("#loading_box").hide();
                        this.send = true;
                        if (error.response.status == 401){
                            $("#login_box").show();
                        }
                        console.log(error);
                    });
                }
            },
            set_ordering: function (type) {
                this.ordering = type;
                this.getList(1);
            },
        }
    }
</script>

<style scoped>

</style>