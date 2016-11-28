<template>
    <div class="panel-body has-price-footer" v-if="loaded">
        <div v-if="products.length">
            <a href="#" @click.prevent="orderByBrand()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <ul class="list-group">
                 <li v-for="product in products" class="list-group-item">
                    <strong>{{ product.brand }}</strong>- {{ product.name }} : ${{ product.price }}
                    <span id="right-side-price-controls">
                        <input type="number" v-model="product.qty_selected" min="1">
                        <a href="#" v-if="!product.price_added" @click.prevent="addPrice(product.id, product.price)" class="btn btn-success">ADD</a>
                        <a href="#" v-else @click.prevent="subPrice(product.id, product.price)" class="btn btn-danger">REMOVE</a>
                        <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ product.qty_added }}</span>
                    </span>
                </li>
            </ul>
        </div>
         <div v-else>
            <p>You currently don't have any products listed.</p>
        </div>

        <footer v-if="products.length" class="total-price-footer">
            ${{ total.toFixed(2).replace('-', '') }} AUD
            <br />
            <a href="#" @click.prevent="clear()" class="clear-price">Reset Price</a>
        </footer>
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
                        // when a product's price has been added to total, we want to reflect that in the ui
                        Vue.set(this.products[i], 'price_added', false);
                        // keep track of qty input element of each product
                        Vue.set(this.products[i], 'qty_selected', 1);
                        // keep track of the actual qty of the product
                        Vue.set(this.products[i], 'qty_added', 0);
                    }
                });
            },
            orderByBrand () {
                this.products.reverse();
            },
            addPrice (id, price) {
                // get the qty input selected
                var qty = this.getProductQtySelected(id);
                // set the qty selected to the product obj qty_added prop, saving the inputs state,
                // rather than altering the obj prop for every change in input
                this.setProductQtyAdded(id, qty);
                this.total += (parseFloat(price) * qty);
                this.setPriceAddedProp(id, true);
            },
            subPrice (id, price) {
                // qty input by user
                var qty_selected = this.getProductQtySelected(id);
                if (qty_selected > this.getProductQtyAdded(id)) {
                    // if the qty_selected input is greater than the qty_added prop of product,
                    // which is set when user adds qty of product
                    this.total -= (parseFloat(price) * this.getProductQtyAdded(id));
                    this.setProductQtyAdded(id, 0);
                    this.setPriceAddedProp(id, false);
                } else if (qty_selected < this.getProductQtyAdded(id)) {
                    // see initial conditional statement comments for insight
                    this.total -= (parseFloat(price) * qty_selected);
                    this.setProductQtyAdded(id, (this.getProductQtyAdded(id) - qty_selected));
                    if (this.getProductQtyAdded(id) > 0) {
                        // if there is still a qty of the product after removing the qty_selected amount
                        this.setPriceAddedProp(id, true);
                    } else {
                        this.setPriceAddedProp(id, false);
                    }
                } else {
                    this.total -= (parseFloat(price) * qty_selected);
                    this.setProductQtyAdded(id, 0);
                    this.setPriceAddedProp(id, false);
                }

                if (this.getProductQtyAdded(id) === 0) {
                    // fall back, just in case
                    this.setPriceAddedProp(id, false);
                }

                if (parseFloat(this.total) < 0.001) {
                    // if the total is essentially zero, clear everthing, just to make sure everything is functioning as expected
                    this.clear();
                }
            },
            setPriceAddedProp (id, val) {
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                        this.products[i].price_added = val;
                    }
                }
            },
            getPriceAddedProp (id) {
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                        return this.products[i].price_added;
                    }
                }
            },
            getProductQtySelected (id) {
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                        return this.products[i].qty_selected;
                    }
                }
            },
            setProductQtyAdded (id, qty) {
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                       this.products[i].qty_added = qty;
                    }
                }
            },
            getProductQtyAdded (id) {
                for (var i = 0; i < this.products.length; i++) {
                    if (this.products[i].id === id) {
                       return this.products[i].qty_added;
                    }
                }
            },
            clear () {
                this.total = 0;
                for (var i = 0; i < this.products.length; i++) {
                    this.products[i].qty_added = 0;
                    if (this.products[i].priceAdded === true) {
                        this.products[i].price_added = false;
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
