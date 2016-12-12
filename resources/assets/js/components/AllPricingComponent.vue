<template>
    <div>
        <div class="modal" tabindex="-1" role="dialog" id="service-price-error-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Service discount issue</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Whoops!</strong> You have added or removed a service item since applying the {{ serviceDiscountFactor }}% discount.</p>
                        <p>This has caused the total price to be incorrect.</p>
                        <p>Please remove and re-apply the service discount to clear this up :) ;</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" @click.prevent="discountServices(serviceDiscountFactor)" v-bind:data-dismiss="(servicesDiscounted) ? 'modal' : ''" class="btn btn-lg btn-discount" v-bind:class="(!servicesDiscounted) ? ' btn-success' : ' btn-danger'"><span class="glyphicon glyphicon-flash"></span>{{ (!servicesDiscounted) ? 'Discount by ' + serviceDiscountFactor + '%' : 'Remove discount' }}</a>
                        <button v-if="!servicesDiscounted" type="button" class="btn btn-default btn-lg btn-discount" data-dismiss="modal">Don't worry about the discount</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="product-price-error-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Product discount issue</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Whoops!</strong> You have added or removed a service item since applying the {{ productDiscountFactor }}% discount.</p>
                        <p>This has caused the total price to be incorrect.</p>
                        <p>Please remove and re-apply the service discount to clear this up :) ;</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" @click.prevent="discountProducts(productDiscountFactor)" v-bind:data-dismiss="(productsDiscounted) ? 'modal' : ''" class="btn btn-lg btn-discount" v-bind:class="(!productsDiscounted) ? ' btn-success' : ' btn-danger'"><span class="glyphicon glyphicon-flash"></span>{{ (!productsDiscounted) ? 'Discount by ' + productDiscountFactor + '%' : 'Remove discount' }}</a>
                        <button v-if="!productsDiscounted" type="button" class="btn btn-default btn-lg btn-discount" data-dismiss="modal">Don't worry about the discount</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body has-large-price-footer">
            <a href="#products" v-if="productBrands.data.length" class="pull-right">Jump to Products &dArr;</a>
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
                            <strong>{{ service.title }}</strong> : ${{ parseFloat(service.price).toFixed(2) }}
                            <span class="right-side-price-controls">
                                <input type="number" v-model="service.qty_selected" min="1">
                                <a href="#" @click.prevent="(!service.has_qty_added) ? addServicePrice(service) : subServicePrice(service)" class="btn" v-bind:class="(!service.has_qty_added) ? ' btn-success' : ' btn-danger'">{{ (!service.has_qty_added) ? 'ADD' : 'REMOVE' }}</a>
                                <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ service.qty_added }}</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
             <div v-if="!serviceCategories.data.length && loaded">
                <p>You currently don't have any services listed.</p>
            </div>

            <hr class="service-product-seperator">

            <a href="#services" v-if="serviceCategories.data.length" class="pull-right">Jump to Services &uArr;</a>
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
                                <strong>{{ product.name }}</strong> : ${{ parseFloat(product.price).toFixed(2) }}
                                <span class="right-side-price-controls">
                                    <input type="number" v-model="product.qty_selected" min="1">
                                    <a href="#" @click.prevent="(!product.has_qty_added) ? addProductPrice(product) : subProductPrice(product)" class="btn" v-bind:class="(!product.has_qty_added) ? ' btn-success' : ' btn-danger'">{{ (!product.has_qty_added) ? 'ADD' : 'REMOVE' }}</a>
                                    <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ product.qty_added }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" @click.prevent="discountServices(serviceDiscountFactor)" class="btn btn-lg btn-discount" v-bind:class="(!servicesDiscounted) ? ' btn-success' : ' btn-danger'"><span class="glyphicon glyphicon-flash"></span>{{ (!servicesDiscounted) ? 'Discount services by ' + serviceDiscountFactor + '%' : 'Remove services discount' }}</a>
                <a href="#" @click.prevent="discountProducts(productDiscountFactor)" class="btn btn-lg btn-discount" v-bind:class="(!productsDiscounted) ? ' btn-success' : ' btn-danger'"><span class="glyphicon glyphicon-flash"></span>{{ (!productsDiscounted) ? 'Discount products by ' + productDiscountFactor + '%' : 'Remove product discount' }}</a>
            </div>
             <div  v-if="!productBrands.data.length && loaded">
                <p>You currently don't have any products listed.</p>
            </div>

            <footer v-if="serviceCategories.data.length || productBrands.data.length" class="total-price-footer">
                <div v-if="servicesTotalPriceErr"><strong>There was an error with the service discount. Please remove and re-apply the discount.</strong></div>
                <div v-if="productsTotalPriceErr"><strong>There was an error with the product discount. Please remove and re-apply the discount.</strong></div>
                <span class="total-price" v-bind:class="(servicesTotalPriceErr === true || productsTotalPriceErr === true) ? 'text-danger' : ''" v-bind:style="(servicesTotalPriceErr === true || productsTotalPriceErr === true) ? 'text-decoration: line-through' : ''">${{ parseFloat(parseFloat(servicesTotal) + parseFloat(productsTotal)).toFixed(2).replace('-', '') }} AUD</span>
                <br />
                Service total:
                <span class="subtotal-price" v-bind:class="(servicesTotalPriceErr === true) ? 'text-danger' : ''" v-bind:style="(servicesTotalPriceErr === true) ? 'text-decoration: line-through' : ''">${{ parseFloat(servicesTotal).toFixed(2).replace('-', '') }} AUD</span>
                <br />
                Product total:
                <span class="subtotal-price" v-bind:class="(productsTotalPriceErr === true) ? 'text-danger' : ''" v-bind:style="(productsTotalPriceErr === true) ? 'text-decoration: line-through' : ''">${{ parseFloat(productsTotal).toFixed(2).replace('-', '') }} AUD</span>
                <br />
                <a href="#" @click.prevent="clear()" class="clear-price">Reset</a>
            </footer>
        </div>
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
                 servicesTotal: 0,
                 serviceDiscountFactor: 20,
                 servicesDiscounted: false,
                 servicesSavings: 0,
                 servicesTotalPriceErr: false,
                 productsTotal: 0,
                 productDiscountFactor: 10,
                 productsDiscounted: false,
                 productsSavings: 0,
                 productsTotalPriceErr: false,
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
                // check if there was a discount applied before adding this service
                // if the discount is already applied, before adding this service, the total price will be incorrect!
                if (this.servicesDiscounted) {
                    // we won't return, as we will still allow the user to add the item, but we will just warn them to reset discount
                    this.servicesTotalPriceErr = true;
                    $('#service-price-error-modal').modal('show');
                }
                // see ServicePricingComponent for logic insight
                var qty = service.qty_selected;
                service.qty_added = qty;
                this.servicesTotal += (parseFloat(service.price) * qty);
                service.has_qty_added = true;
            },
            addProductPrice (product) {
                // check if there was a discount applied before adding this product
                // if the discount is already applied, before adding this product, the total price will be incorrect!
                if (this.productsDiscounted) {
                    // we won't return, as we will still allow the user to add the item, but we will just warn them to reset discount
                    this.productsTotalPriceErr = true;
                    $('#product-price-error-modal').modal('show');
                }
                // see ProductPricingComponent for logic insight
                var qty = product.qty_selected;
                product.qty_added = qty;
                this.productsTotal += (parseFloat(product.price) * qty);
                product.has_qty_added = true;
            },
            subServicePrice (service) {
                // check if there was a discount applied before removing this service
                // if the discount is already applied, before removing this service, the total price will be incorrect!
                if (this.servicesDiscounted) {
                    // we won't return, as we will still allow the user to remove the item, but we will just warn them to reset discount
                    this.servicesTotalPriceErr = true;
                    $('#service-price-error-modal').modal('show');
                }
                // qty input by user
                var qty_selected = service.qty_selected;
                if (qty_selected > service.qty_added) {
                    // if the qty_selected input is greater than the qty_added prop of service,
                    // which is set when user adds qty of service
                    this.servicesTotal -= (parseFloat(service.price) * service.qty_added);
                    service.qty_added = 0
                    service.has_qty_added = false;
                } else if (qty_selected < service.qty_added) {
                    // see initial condition for insight
                    this.servicesTotal -= (parseFloat(service.price) * qty_selected);
                    service.qty_added = (service.qty_added - qty_selected);
                    // if there is still a qty of the service after removing the qty_selected amount
                    service.has_qty_added = (service.qty_added > 0) ? true : false;
                } else {
                    this.servicesTotal -= (parseFloat(service.price) * qty_selected);
                    service.qty_added = 0;
                    service.has_qty_added = false;
                }
            },
            subProductPrice (product) {
                // check if there was a discount applied before removing this product
                // if the discount is already applied, before removing this product, the total price will be incorrect!
                if (this.productsDiscounted) {
                    // we won't return, as we will still allow the user to remove the item, but we will just warn them to reset discount
                    this.productsTotalPriceErr = true;
                    $('#product-price-error-modal').modal('show');
                }
                // qty input by user
                var qty_selected = product.qty_selected;
                if (qty_selected > product.qty_added) {
                    // if the qty_selected input is greater than the has_qty_added prop of product,
                    // which is set when user adds qty of product
                    this.productsTotal -= (parseFloat(product.price) * product.qty_added);
                    product.qty_added = 0;
                    product.has_qty_added = false;
                } else if (qty_selected < product.qty_added) {
                    // see initial conditional statement comments for insight
                    this.productsTotal -= (parseFloat(product.price) * qty_selected);
                    product.qty_added = (product.qty_added - qty_selected);
                    // if there is still a qty of the product after removing the qty_selected amount
                    product.has_qty_added = (product.qty_added > 0) ? true : false;
                } else {
                    this.productsTotal -= (parseFloat(product.price) * qty_selected);
                    product.qty_added = 0;
                    product.has_qty_added = false;
                }
            },
            discountServices (factor) {
                if (this.servicesTotalPriceErr) {
                    // if there is a pricing error, let's 'reset' this
                    // the error occurs if the items are discounted and the user removes or adds more items after discount.
                    // this results in an incorrect total price!
                    // The resolution: requesting the user to remove discount and re-apply, so at this stage, they have removed discount, hence, no more error :)
                    this.servicesTotalPriceErr = ! this.servicesTotalPriceErr;
                }
                if (this.servicesTotal === 0) {
                    // no point in discounting if there is no total price!
                    return;
                }
                if (! this.servicesDiscounted) {
                    // if there is no discount applied, let's do calculate it and save the 'savings' in another varaible, to reference later on, if discount is removed!
                    // keep in mind that factor is to be a int, representing a percentage
                    this.servicesSavings = parseFloat((this.servicesTotal * (parseInt(factor) / 100)));

                    this.servicesTotal -= this.servicesSavings;
                } else {
                    // removing discount increases total price ;)
                    this.servicesTotal += this.servicesSavings;
                }

                // set discounted to its opposite value (boolean)
                this.servicesDiscounted = ! this.servicesDiscounted;
            },
            discountProducts (factor) {
                if (this.productsTotalPriceErr) {
                    // if there is a pricing error, let's 'reset' this
                    // the error occurs if the items are discounted and the user removes or adds more items after discount.
                    // this results in an incorrect total price!
                    // The resolution: requesting the user to remove discount and re-apply, so at this stage, they have removed discount, hence, no more error :)
                    this.productsTotalPriceErr = ! this.productsTotalPriceErr;
                }
                if (this.productsTotal === 0) {
                    // no point in discounting if there is no total price!
                    return;
                }
                if (! this.productsDiscounted) {
                    // if there is no discount applied, let's do calculate it and save the 'savings' in another varaible, to reference later on, if discount is removed!
                    // keep in mind that factor is to be a int, representing a percentage
                    this.productsSavings = parseFloat((this.productsTotal * (parseInt(factor) / 100)));

                    this.productsTotal -= this.productsSavings;
                } else {
                    // removing discount increases total price ;)
                    this.productsTotal += this.productsSavings;
                }

                // set discounted to its opposite value (boolean)
                this.productsDiscounted = ! this.productsDiscounted;
            },
            clear () {
                // reset total etc.
                this.total = 0;
                this.servicesTotal = 0;
                this.servicesDiscounted = false;
                this.servicesSavings = 0;
                this.servicesTotalPriceErr = false;
                this.productsTotal = 0;
                this.productsDiscounted = false;
                this.productsSavings = 0;
                this.productsTotalPriceErr = false;

                // clear services
                 for (var i = 0; i < this.serviceCategories.data.length; i++) {
                    for (var j = 0; j < this.serviceCategories.data[i].services.length; j++) {
                        this.serviceCategories.data[i].services[j].qty_added = 0;
                        if (this.serviceCategories.data[i].services[j].has_qty_added === true) {
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
