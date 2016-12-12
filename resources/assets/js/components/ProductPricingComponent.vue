<template>
    <div>
        <div class="modal" tabindex="-1" role="dialog" id="price-error-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Discount issue</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Whoops!</strong> You have added or removed an item since applying the {{ discountFactor }}% discount.</p>
                        <p>This has caused the total price to be incorrect.</p>
                        <p>Please remove and re-apply the discount to clear this up :) ;</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" @click.prevent="discount(discountFactor)" v-bind:data-dismiss="(discounted) ? 'modal' : ''" class="btn btn-lg btn-discount" v-bind:class="(!discounted) ? ' btn-success' : ' btn-danger'"><span class="glyphicon glyphicon-flash"></span>{{ (!discounted) ? 'Discount by ' + discountFactor + '%' : 'Remove discount' }}</a>
                        <button v-if="!discounted" type="button" class="btn btn-default btn-lg btn-discount" data-dismiss="modal">Don't worry about the discount</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body has-price-footer">
            <div v-if="brands.data.length">
                <a href="#" @click.prevent="orderByBrand()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
                <br />
                <br />
                <div v-for="brand in brands.data" class="parent-view-group-container">
                    <h4 class="brand-name">{{ brand.brand_name }}</h4>
                    <div v-for="category in brand.categories" class="well">
                        <h4 class="cat-name">{{ category.category }}</h4>
                        <ul class="list-group">
                            <li v-for="product in category.products" class="list-group-item">
                                <strong>{{ product.name }}</strong> : ${{ product.price.toFixed(2) }}
                                <span class="right-side-price-controls">
                                    <input type="number" v-model="product.qty_selected" min="1">
                                    <a href="#" @click.prevent="(!product.has_qty_added) ? addPrice(product) : subPrice(product)" class="btn" v-bind:class="(!product.has_qty_added) ? ' btn-success' : ' btn-danger'">{{ (!product.has_qty_added) ? 'ADD' : 'REMOVE' }}</a>
                                    <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ product.qty_added }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" @click.prevent="discount(discountFactor)" class="btn btn-lg btn-discount" v-bind:class="(!discounted) ? ' btn-success' : ' btn-danger'"><span class="glyphicon glyphicon-flash"></span>{{ (!discounted) ? 'Discount by ' + discountFactor + '%' : 'Remove discount' }}</a>
            </div>
             <div  v-if="!brands.data.length && loaded">
                <p>You currently don't have any products listed.</p>
            </div>

            <footer v-if="brands.data.length" class="total-price-footer">
                <div v-if="totalPriceErr"><strong>There was an error with the discount. Please remove and re-apply the discount.</strong></div>
                <span class="total-price" v-bind:class="(totalPriceErr === true) ? 'text-danger' : ''" v-bind:style="(totalPriceErr === true) ? 'text-decoration: line-through' : ''">${{ total.toFixed(2).replace('-', '') }} AUD</span>
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
                 brands: {
                    data: []
                 },
                 total: 0,
                 discountFactor: 10,
                 discounted: false,
                 savings: 0,
                 totalPriceErr: false,
                 loaded: false
            }
        },
        methods: {
            fetchProducts () {
                return this.$http.get('/products/fetch').then((response) => {
                    // getting all products, listed under their brand and category.
                    // data structure: [{"data": [{brand_name: string, categories: [{category: string, products: [{prod1}, {...}, {...}]}, ..., ...]}, ..., ...]}]
                    this.brands = response.body;
                    for (var i = 0; i < this.brands.data.length; i++) {
                        for (var j = 0; j < this.brands.data[i].categories.length; j++) {
                            for (var k = 0; k < this.brands.data[i].categories[j].products.length; k++) {
                                // when a product's price has been added to total, we want to reflect that in the ui
                                Vue.set(this.brands.data[i].categories[j].products[k], 'has_qty_added', false);
                                // keep track of qty input element of each product
                                Vue.set(this.brands.data[i].categories[j].products[k], 'qty_selected', 1);
                                // keep track of the actual qty of the product added
                                Vue.set(this.brands.data[i].categories[j].products[k], 'qty_added', 0);
                            }
                        }
                    }
                    this.loaded = true;
                });
            },
            orderByBrand () {
                this.brands.data.reverse();
            },
            addPrice (product) {
                // check if there was a discount applied before adding this product
                // if the discount is already applied, before adding this product, the total price will be incorrect!
                if (this.discounted) {
                    // we won't return, as we will still allow the user to add the item, but we will just warn them to reset discount
                    this.totalPriceErr = true;
                    $('#price-error-modal').modal('show');
                }
                // get the qty input selected
                var qty = product.qty_selected;
                // set the qty selected to the product obj qty_added prop, saving the inputs state,
                // rather than altering the obj prop for every change in input
                product.qty_added = qty;
                this.total += (parseFloat(product.price) * qty);
                // when product.has_qty_added === true: remove button is shown
                product.has_qty_added = true;
            },
            subPrice (product) {
                // check if there was a discount applied before removing this product
                // if the discount is already applied, before removing this product, the total price will be incorrect!
                if (this.discounted) {
                    // we won't return, as we will still allow the user to remove the item, but we will just warn them to reset discount
                    this.totalPriceErr = true;
                    $('#price-error-modal').modal('show');
                }
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
            discount (factor) {
                if (this.totalPriceErr) {
                    // if there is a pricing error, let's 'reset' this
                    // the error occurs if the items are discounted and the user removes or adds more items after discount.
                    // this results in an incorrect total price!
                    // The resolution: requesting the user to remove discount and re-apply, so at this stage, they have removed discount, hence, no more error :)
                    this.totalPriceErr = ! this.totalPriceErr;
                }
                if (this.total === 0) {
                    // no point in discounting if there is no total price!
                    return;
                }
                if (! this.discounted) {
                    // if there is no discount applied, let's do calculate it and save the 'savings' in another varaible, to reference later on, if discount is removed!
                    // keep in mind that factor is to be a int, representing a percentage
                    this.savings = parseFloat((this.total * (parseInt(factor) / 100)));

                    this.total -= this.savings;
                } else {
                    // removing discount increases total price ;)
                    this.total += this.savings;
                }

                // set discounted to its opposite value (boolean)
                this.discounted = ! this.discounted;
            },
            clear () {
                this.total = 0;
                this.savings = 0;
                this.discounted = false;
                this.totalPriceErr = false;

                for (var i = 0; i < this.brands.data.length; i++) {
                    for (var j = 0; j < this.brands.data[i].categories.length; j++) {
                        for (var k = 0; k < this.brands.data[i].categories[j].products.length; k++) {
                            this.brands.data[i].categories[j].products[k].qty_added = 0;
                            if (this.brands.data[i].categories[j].products[k].has_qty_added === true) {
                                this.brands.data[i].categories[j].products[k].has_qty_added = false;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.fetchProducts();
        }
    }
</script>
