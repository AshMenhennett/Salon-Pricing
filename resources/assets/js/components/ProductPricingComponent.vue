<template>
    <div class="panel-body has-footer" v-if="loaded">
        <div v-if="products.length">
            <a href="#" @click.prevent="orderByBrand()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <ul class="list-group">
                 <li v-for="product in products" class="list-group-item">
                    <strong>{{ product.brand }}</strong>- {{ product.name }} : ${{ product.price }}
                    <span v-if="!product.priceAdded"><a href="#" @click.prevent="addPrice(product.id, product.price)" class="btn btn-success pull-right">ADD</a></span>
                    <span v-else><a href="#" @click.prevent="subPrice(product.id, product.price)" class="btn btn-danger pull-right">REMOVE</a></span>
                </li>
            </ul>
        </div>
         <div v-else>
            <p>You currently don't have any products listed.</p>
        </div>
        <div v-if="products.length" class="total-price-footer">
            ${{ total.toFixed(2).replace('-', '') }} AUD
            <br />
            <a href="#" @click.prevent="clear()" style="font-weight: bolder; color: #ddd; text-decoration: underline;">Clear Price</a>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 products: [],
                 total: 0,
                 loaded: false
            }
        },
        methods: {
            fetchProducts () {
                return this.$http.get('/products/fetch').then((response) => {
                    this.products = response.body;
                    for (var i = 0; i < this.products.length; i++) {
                        this.products[i]['priceAdded'] = false;
                    }
                });
            },
            orderByBrand () {
                this.products.reverse();
            },
            addPrice (id, price) {
                this.total += parseFloat(price);
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                        this.products[i].priceAdded = true;
                    }
                }
            },
            subPrice (id, price) {
                this.total -= parseFloat(price);
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                        this.products[i].priceAdded = false;
                    }
                }
            },
            clear () {
                this.total = 0;
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].priceAdded === true) {
                        this.products[i].priceAdded = false;
                    }
                }
            }
        },
        mounted() {
            this.fetchProducts();
            this.loaded = true;
            this.total = 0;
        }
    }
</script>
