<template>
 <div>
     <div class="header_cart_box">
         <div class="basket_arrow"></div>
         <div class="box_label">
             <span>({{replace_number(CartProduct.product_count)}}) کالا </span>
             <a v-bind:href="$siteUrl+'Cart'">مشاهده سبد خرید</a>
         </div>
         <div id="header_cart_content">
             <div v-if="CartProduct.product != undefined && CartProduct.product.length>0">
                 <table class="cart_table">
                     <tr v-for="product in CartProduct['product']">
                         <td>
                             <img v-bind:src="$siteUrl+'files/uploads/thumbnails/'+product.product_image_url">
                         </td>
                         <td>
                             <ul>
                                 <li class="title">
                                     <a href="">{{product.product_title}}</a>
                                 </li>
                                 <li>
                                  <div class="d-flex justify-content-between w-100">
                                      <div>
                                          <span style="padding-left: 4px">{{ replace_number(product.product_count) }} عدد</span>
                                          <div v-if="product.color_name != undefined">
                                              <span>رنگ : </span>
                                              <span> {{ product.color_name }}</span>
                                          </div>
                                      </div>
                                      <a v-bind:href="$siteUrl+'Cart'">
                                          <span style="font-size: 14px" class="fa fa-trash"></span>
                                      </a>
                                  </div>
                                 </li>
                             </ul>
                         </td>
                     </tr>
                 </table>
             </div>
         </div>
         <div class="box_label" style="height: 40px">
             <div>
                 <span>مبلغ قابل پرداخت</span>
                 <span>{{ CartProduct.total_price }} تومان </span>
             </div>
             <a v-bind:href="$siteUrl+'shipping'" class="btn order_page">مشاهده سبد خرید</a>
         </div>
     </div>
 </div>
</template>

<script>
    import myMixin from "../myMixin";

    export default {
        name: "HeaderCart",
        mixins: [myMixin],
        data() {
            return {
                show_dialog_box: false,
                selected_product: null,
                CartProduct: {},
            }
        },
        mounted() {
            this.CartProductData();
        },
        methods: {
            CartProductData: function () {
                const url = this.$siteUrl + 'site/CartProductData';
                this.axios.get(url).then(responce => {
                    this.CartProduct = responce.data;
                    console.log(responce.data);

                }).catch(error => {
                    console.log(error);
                });

            },

        }
    }
</script>

<style scoped>

</style>