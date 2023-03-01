<template>
    <div class="modal" role="dialog" id="vue_chart">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        نمودار تغییرات قیمت
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div v-if="this.chartOptions['series'].length > 0">
                    <div style="width: 97%;margin:25px auto">
                        <highcharts :options="chartOptions"></highcharts>
                    </div>
                    <div class="chart_color_div">
                        <ul class="color_ul">
                            <li v-for="(color,key) in colors"
                                :class="[color_key==key ? 'color_li active' : 'color_li']">
                                <label v-on:click="change_series(key)" class="position-relative">
                                    <span class="ui_variant_shape" v-bind:style="{background:color.code}"></span>
                                    <span class="color_name">{{ color.name }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div v-else class="d-flex justify-content-center align-items-center" style="height: 400px;">
                    <span>طی یک ماه گذشته تغییرات قیمت برای این محصول ثبت نشده است.</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Chart} from 'highcharts-vue'

    export default {
        name: "VueChart",
        components: {
            highcharts: Chart
        },
        props: ['product_id'],
        data() {
            return {
                chartOptions: {
                    title: {
                        text: '',
                        update: true,
                    },
                    series: [],
                    xAxis: {
                        categories: []
                    },
                    chart: {
                        height: 400,
                        type: 'line',
                        style: {
                            fontFamily: 'Vazir',
                        },
                    },
                    yAxis: {
                        title: {
                            text: ''
                        },
                        labels: {
                            style: {
                                fontSize: '13px',
                            },
                            useHTML: true,
                            formatter: function () {
                                let value = replace_number(number_format(this.value));
                                return '<div style="direction: rtl"><span>' + value + '</span> <span style="padding-right: 2px">تومان</span></div>';
                            },
                        },
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        borderColor: '#c8c8c8',
                        borderRadius: 10,
                        borderWidth: 1,
                        useHTML: true,
                        formatter: function () {
                            if (this.point.has_product == 'Ok') {
                                return '<div>' +
                                    '<ul class="chart_ul">' +
                                    '<li style="justify-content: end;margin-top: 10px">' +
                                    '<span class="pr-2">' + this.point.seller + ' </span> : <span> فروشنده</span>' +
                                    '</li>' +
                                    '<li style="justify-content: space-between;margin-top: 15px">' +
                                    '<div style="color: #00bfd6;font-size: 19px;direction: rtl"><span>' + replace_number(number_format(this.point.price)) + '</span> <span>تومان</span></div>' +
                                    '<div>کمترین قیمت</div>' +
                                    '</li>' +
                                    '</ul>' +
                                    '</div>';
                            } else {
                                return '<div>' +
                                    '<ul class="chart_ul">' +
                                    '<li style="justify-content: space-between;margin-top: 15px">' +
                                    '<div style="color: #00bfd6;font-size: 19px;direction: rtl"><span>ناموجود</span></div>' +
                                    '<div>کمترین قیمت</div>' +
                                    '</li>' +
                                    '</ul>' +
                                    '</div>';
                            }
                        },
                    },
                },
                colors: [],
                color_key: 0,

            }
        },
        mounted() {

            const self = this;
            $(document).on('click', '.fa-line-chart', function () {
                self.getData();
            });
        },
        methods: {
            getData: function () {
                const self = this;
                if (this.chartOptions['series'].length == 0) {
                    $("#loading_box").show();
                    const url = this.$siteUrl + 'api/getProductChartData/' + this.product_id;
                    this.axios.get(url).then(response => {
                        this.chartOptions['xAxis']['categories'] = response.data.points;
                        this.colors = response.data.color;
                        let i = 0;
                        response.data.price.forEach(function (item) {
                            let name = response.data.color[i].name;
                            const zonesRow = response.data.zone[response.data.color[i].id];
                            self.chartOptions['series'].push({
                                'data': item,
                                'name': name,
                                'color': '#00bfd6',
                                'marker': {symbol: 'circle'},
                                zones: zonesRow,
                                zoneAxis: 'x'
                            });
                            if (i > 0) {
                                self.chartOptions['series'][i].visible = false;
                            } else {
                                self.chartOptions['series'][i].visible = true;
                            }
                            i++;
                        });
                        $("#loading_box").hide();
                        $('#vue_chart').modal('show');
                    }).catch(error => {
                        $("#loading_box").hide();
                        console.log(error);
                    });
                } else {
                    $('#vue_chart').modal('show');
                }


            },
            change_series: function (key) {
                this.color_key = key;
                this.chartOptions['title']['update'] = !this.chartOptions['title']['update'];
                this.chartOptions['series'].forEach(function (item) {
                    item.visible = false;

                });
                this.chartOptions['series'][key].visible = true;
            }
        },
    }
</script>

<style scoped>

</style>