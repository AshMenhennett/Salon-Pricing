<template>
    <div class="panel-body has-price-footer" v-if="loaded">
        <h4>Services</h4>
        <div v-if="services.length">
            <a href="#" @click.prevent="orderByTitle()" class="btn btn-default btn-top pull-left">Order Services <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <ul class="list-group">
                 <li v-for="service in services" class="list-group-item">
                    <strong>{{ service.title }}</strong> : ${{ service.price }}
                    <span v-if="!service.priceAdded"><a href="#" @click.prevent="addPrice('service', service.id, service.price)" class="btn btn-success pull-right">ADD</a></span>
                    <span v-else><a href="#" @click.prevent="subPrice('service', service.id, service.price)" class="btn btn-danger pull-right">REMOVE</a></span>
                </li>
            </ul>
        </div>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>

        <h4>Products</h4>
        <div v-if="products.length">
            <a href="#" @click.prevent="orderByBrand()" class="btn btn-default btn-top pull-left">Order Products<span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <ul class="list-group">
                 <li v-for="product in products" class="list-group-item">
                    <strong>{{ product.brand }}</strong>- {{ product.name }} : ${{ product.price }}
                    <span v-if="!product.priceAdded"><a href="#" @click.prevent="addPrice('product', product.id, product.price)" class="btn btn-success pull-right">ADD</a></span>
                    <span v-else><a href="#" @click.prevent="subPrice('product', product.id, product.price)" class="btn btn-danger pull-right">REMOVE</a></span>
                </li>
            </ul>
        </div>
         <div v-else>
            <p>You currently don't have any products listed.</p>
        </div>

        <footer v-if="services.length || products.length" class="total-price-footer">
            ${{ total.toFixed(2).replace('-', '') }} AUD
            <br />
            <a href="#" @click.prevent="clear()" style="font-weight: bolder; color: #ddd; text-decoration: underline;">Clear Price</a>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 services: [],
                 products: [],
                 total: 0,
                 loaded: false
            }
        },
        methods: {
            fetchServices () {
                return this.$http.get('/services/fetch').then((response) => {
                    this.services = response.body;
                    for (var i = 0; i < this.services.length; i++) {
                        this.services[i]['priceAdded'] = false;
                    }
                });
            },
            fetchProducts () {
                return this.$http.get('/products/fetch').then((response) => {
                    this.products = response.body;
                    for (var i = 0; i < this.products.length; i++) {
                        this.products[i]['priceAdded'] = false;
                    }
                });
            },
            orderByTitle () {
                this.services.reverse();
            },
            orderByBrand () {
                this.products.reverse();
            },
            addPrice (type, id, price) {
                this.total += parseFloat(price);
                if (type === 'service') {
                    this.changeServicePriceAddedProp(id, true);
                } else {
                    this.changeProductPriceAddedProp(id, true);
                }
            },
            subPrice (type, id, price) {
                this.total -= parseFloat(price);
                if (type === 'service') {
                    this.changeServicePriceAddedProp(id, false);
                } else {
                    this.changeProductPriceAddedProp(id, false);
                }
            },
            changeServicePriceAddedProp (id, val) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        this.services[i].priceAdded = val;
                    }
                }
            },
            changeProductPriceAddedProp (id, val) {
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                        this.products[i].priceAdded = val;
                    }
                }
            },
            clear () {
                this.total = 0;
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].priceAdded === true) {
                        this.services[i].priceAdded = false;
                    }
                }
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].priceAdded === true) {
                        this.products[i].priceAdded = false;
                    }
                }
            }
        },
        mounted() {
            this.fetchServices();
            this.fetchProducts();
            this.loaded = true;
            this.total = 0;
        }
    }
</script>
