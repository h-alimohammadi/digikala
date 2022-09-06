<template>
    <div class="modal fade product_list" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="header">
                    <div class="input-group mb-3">
                        <input class="form-control" type="text" v-on:keyup.enter="search_product" v-model="search_text"
                               placeholder="نام کالای مورد نظر را وارد کنید...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" data-toggle="dropdown">
                                <span>{{this.brand_name}}</span>
                                <span class="fa fa-angle-down"></span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" v-on:click="getBrandProduct(0,'تمامی برند ها')">تمامی برند
                                    ها</a>
                                <a class="dropdown-item" v-for="brand in this.BrandList"
                                   v-on:click="getBrandProduct(brand.brand_id,brand.get_brand.name)">{{brand.get_brand.name}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div style="display: flex;flex-wrap: wrap">
                        <div v-for="product in ProductList.data" class="select_product_for_compare"
                             v-bind:data-id="product.id" v-on:click="add_compare_list(product.id)">
                            <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+product.image_url"
                                 v-if="product.image_url != null">
                            <p>{{product.title}}</p>
                        </div>
                    </div>
                    <pagination :data="ProductList" @pagination-change-page="getProduct"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CompareProductList",
        data() {
            return {
                ProductList: {data: []},
                brand_id: 0,
                brand_name: 'تمامی برند ها',
                search_text: '',
                old_search_text: '',
                BrandList: [],
                product_fail_request_count: 0,
                brand_fail_request_count: 0,

            }
        },
        props: ['cat_id'],
        mounted() {
            this.getProduct(1);
            this.getBrand();
        },
        methods: {
            getProduct: function (page = 1) {
                $("#loading_box").show();
                const url = this.$siteUrl + 'get-compare-products?page=' + page;
                const formData = new FormData();
                formData.append('brand_id', this.brand_id);
                formData.append('cat_id', this.cat_id);
                formData.append('search_product', this.search_text);
                this.axios.post(url, formData).then(response => {
                    $("#loading_box").hide();
                    this.ProductList = response.data;
                }).catch(error => {
                    $("#loading_box").hide();
                    if (this.product_fail_request_count < 2) {
                        this.getProduct(page);
                        this.product_fail_request_count++
                    }
                    console.log(error);
                });
            },
            add_compare_list: function (product_id) {
                let url = window.location.href;
                if (url.split("/dkp-" + product_id).length == 1) {
                    url = url + "/dkp-" + product_id;
                    window.location = url;
                } else {
                    alert('قبلا اتخاب شده است');
                }

            },
            getBrand: function () {
                $("#loading_box").show();
                const url = this.$siteUrl + 'site/getCatBrand';
                const formData = new FormData();
                formData.append('cat_id', this.cat_id);
                this.axios.post(url, formData).then(response => {
                    $("#loading_box").hide();
                    this.BrandList = response.data;
                }).catch(error => {
                    $("#loading_box").hide();
                    if (this.brand_fail_request_count < 2) {
                        this.getBrand();
                        this.brand_fail_request_count++;
                    }
                    console.log(error);
                });
            },
            getBrandProduct: function (brand_id, brand_name) {
                this.brand_id = brand_id;
                this.brand_name = brand_name;
                this.getProduct();
            },
            search_product: function () {
                if (this.search_text.trim().length > 2) {
                    this.old_search_text = this.search_text;
                    this.getProduct();
                } else if (this.search_text.trim() == '' && this.old_search_text.trim().length > 0) {
                    this.getProduct();
                    this.old_search_text='';
                }
            },
        }
    }
</script>

<style scoped>

</style>