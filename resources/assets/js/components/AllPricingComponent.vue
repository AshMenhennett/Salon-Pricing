<template>
    <div class="panel-body" v-if="loaded">
        <a href="#products" class="pull-right">Jump to Products &dArr;</a>
        <br />
        <h4 id="services">Services</h4>
        <div v-if="serviceCategories.data.length">
            <a href="#" @click.prevent="orderServicesByCategory()" class="btn btn-default btn-top pull-left">Order Services <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <div v-for="category in serviceCategories.data" class="parent-view-group-container">
                <h4 class="cat-name">{{ category.category }}</h4>
                <ul class="list-group">
                    <li class="list-group-item" v-for="service in category.services">
                        <strong>{{ service.title }}</strong> : ${{ service.price }}
                        <span class="right-side-price-controls">
                            <input type="number" v-model="service.qty_selected" min="1">
                            <a href="#" @click.prevent="(!service.has_qty_added) ? addServicePrice(service) : subServicePrice(service)" class="btn" v-bind:class="(!service.has_qty_added) ? ' btn-success' : ' btn-danger'">{{ (!service.has_qty_added) ? 'ADD' : 'REMOVE' }}</a>
                            <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ service.qty_added }}</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>

        <hr class="service-product-seperator">

        <a href="#services" class="pull-right">Jump to Services &uArr;</a>
        <br />
        <h4 id="products">Products</h4>
        <div v-if="productBrands.data.length">
            <a href="#" @click.prevent="orderProductsByBrand()" class="btn btn-default btn-top pull-left">Order Products <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <div v-for="brand in productBrands.data" class="parent-view-group-container">
                <h4 class="brand-name">{{ brand.brand_name }}</h4>
                <div v-for="category in brand.categories" class="well">
                    <h4 class="cat-name">{{ category.category }}</h4>
                    <ul class="list-group">
                        <li v-for="product in category.products" class="list-group-item">
                            <strong>{{ product.name }}</strong> : ${{ product.price }}
                            <span class="right-side-price-controls">
                                <input type="number" v-model="product.qty_selected" min="1">
                                <a href="#" @click.prevent="(!product.has_qty_added) ? addProductPrice(product) : subProductPrice(product)" class="btn" v-bind:class="(!product.has_qty_added) ? ' btn-success' : ' btn-danger'">{{ (!product.has_qty_added) ? 'ADD' : 'REMOVE' }}</a>
                                <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ product.qty_added }}</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
         <div v-else>
            <p>You currently don't have any products listed.</p>
        </div>

        <footer v-if="serviceCategories.data.length || productBrands.data.length" class="total-price-footer">
            <span class="total-price">${{ total.toFixed(2).replace('-', '') }} AUD</span>
            <br />
            <a href="#" @click.prevent="clear()" class="clear-price">Reset</a>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 serviceCategories: {
                    data: []
                 },
                 productBrands: {
                    data: []
                 },
                 total: 0,
                 loaded: false
            }
        },
        methods: {
            fetchServices () {
                return this.$http.get('/services/fetch').then((response) => {
                    // getting all services, listed under their category.
                    // data structure: {"data": [{category: string, services: [{service1, ..., ...}]}]}
                    this.serviceCategories = response.body;
                    for (var i = 0; i < this.serviceCategories.data.length; i++) {
                        for (var j = 0; j < this.serviceCategories.data[i].services.length; j++) {
                            // when a service's price has been added to total, we want to reflect that in the ui
                            Vue.set(this.serviceCategories.data[i].services[j], 'has_qty_added', false);
                            // keep track of qty input element of each service
                            Vue.set(this.serviceCategories.data[i].services[j], 'qty_selected', 1);
                            // keep track of the actual qty of the service
                            Vue.set(this.serviceCategories.data[i].services[j], 'qty_added', 0);
                        }
                    }
                    this.loaded = true;
                });
            },
            fetchProducts () {
                return this.$http.get('/products/fetch').then((response) => {
                    // getting all products, listed under their brand and category.
                    // data structure: {"data": [{brand_name: string, categories: [{category: string, products: [{prod1, ..., ...}]}]}]}
                    this.productBrands = response.body;
                    for (var i = 0; i < this.productBrands.data.length; i++) {
                        for (var j = 0; j < this.productBrands.data[i].categories.length; j++) {
                            for (var k = 0; k < this.productBrands.data[i].categories[j].products.length; k++) {
                                // when a product's price has been added to total, we want to reflect that in the ui
                                Vue.set(this.productBrands.data[i].categories[j].products[k], 'has_qty_added', false);
                                // keep track of qty input element of each product
                                Vue.set(this.productBrands.data[i].categories[j].products[k], 'qty_selected', 1);
                                // keep track of the actual qty of the product added
                                Vue.set(this.productBrands.data[i].categories[j].products[k], 'qty_added', 0);
                            }
                        }
                    }
                });
            },
            orderServicesByCategory () {
                this.serviceCategories.data.reverse();
            },
            orderProductsByBrand () {
                this.productBrands.data.reverse();
            },
            addServicePrice (service) {
                // see ServicePricingComponent for logic insight
                var qty = service.qty_selected;
                service.qty_added = qty;
                this.total += (parseFloat(service.price) * qty);
                service.has_qty_added = true;
            },
            addProductPrice (product) {
                // see ProductPricingComponent for logic insight
                var qty = product.qty_selected;
                product.qty_added = qty;
                this.total += (parseFloat(product.price) * qty);
                product.has_qty_added = true;
            },
            subServicePrice (service) {
                // qty input by user
                var qty_selected = service.qty_selected;
                if (qty_selected > service.qty_added) {
                    // if the qty_selected input is greater than the qty_added prop of service,
                    // which is set when user adds qty of service
                    this.total -= (parseFloat(service.price) * service.qty_added);
                    service.qty_added = 0
                    service.has_qty_added = false;
                } else if (qty_selected < service.qty_added) {
                    // see initial condition for insight
                    this.total -= (parseFloat(service.price) * qty_selected);
                    service.qty_added = (service.qty_added - qty_selected);
                    // if there is still a qty of the service after removing the qty_selected amount
                    service.has_qty_added = (service.qty_added > 0) ? true : false;
                } else {
                    this.total -= (parseFloat(service.price) * qty_selected);
                    service.qty_added = 0;
                    service.has_qty_added = false;
                }
            },
            subProductPrice (product) {
                // qty input by user
                var qty_selected = product.qty_selected;
                if (qty_selected > product.qty_added) {
                    // if the qty_selected input is greater than the has_qty_added prop of product,
                    // which is set when user adds qty of product
                    this.total -= (parseFloat(product.price) * product.qty_added);
                    product.qty_added = 0;
                    product.has_qty_added = false;
                } else if (qty_selected < product.qty_added) {
                    // see initial conditional statement comments for insight
                    this.total -= (parseFloat(product.price) * qty_selected);
                    product.qty_added = (product.qty_added - qty_selected);
                    // if there is still a qty of the product after removing the qty_selected amount
                    product.has_qty_added = (product.qty_added > 0) ? true : false;
                } else {
                    this.total -= (parseFloat(product.price) * qty_selected);
                    product.qty_added = 0;
                    product.has_qty_added = false;
                }
            },
            clear () {
                // reset total
                this.total = 0;
                // clear services
                 for (var i = 0; i < this.serviceCategories.data.length; i++) {
                    for (var j = 0; j < this.serviceCategories.data[i].services.length; j++) {
                        this.serviceCategories.data[i].services[j].qty_added = 0;
                        if (this.serviceCategories.data[i].services[j].priceAdded === true) {
                            this.serviceCategories.data[i].services[j].has_qty_added = false;
                        }
                    }
                }
                // clear products
                for (var i = 0; i < this.productBrands.data.length; i++) {
                    for (var j = 0; j < this.productBrands.data[i].categories.length; j++) {
                        for (var k = 0; k < this.productBrands.data[i].categories[j].products.length; k++) {
                            this.productBrands.data[i].categories[j].products[k].qty_added = 0;
                            if (this.productBrands.data[i].categories[j].products[k].has_qty_added === true) {
                                this.productBrands.data[i].categories[j].products[k].has_qty_added = false;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.fetchServices();
            this.fetchProducts();
        }
    }
</script>

<style>
    .footer-container {
        margin-bottom: 95px;
    }
</style>
