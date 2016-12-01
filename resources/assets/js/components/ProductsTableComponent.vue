<template>
    <div class="panel-body" v-if="loaded">
        <a href="/products/create" class="btn btn-default btn-top btn-block">Add a Product</a>
        <button @click.prevent="orderByBrand()" class="btn btn-default">Order Brands <span class="glyphicon glyphicon-sort"></span></button>
        <br />
        <br />
        <div v-if="brands.data.length">
            <div v-for="brand in brands.data" class="parent-view-group-container">
                <h4 style="text-transform: capitalize; text-decoration: underline;">{{ brand.brand_name }}</h4>
                <div v-for="category in brand.categories" class="well">
                    <h4 style="text-transform: capitalize;">{{ category.cat_name }}</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th> <th>Price</th> <th></th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr v-for="product in category.products">
                                <td>{{ product.name }}</td>  <td>${{ product.price }}</td> <td><a href="#" @click.prevent="destroy(product.id)" class="pull-right text-danger manipulate-link"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a v-bind:href="'/products/' + product.id + '/edit'" class="pull-right text-info manipulate-link"><span class="glyphicon glyphicon-pencil"></span></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else>You do not currently have any products listed.</div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 brands: {
                    data: []
                 },
                 loaded: false
            }
        },
        methods: {
            fetchProducts () {
                return this.$http.get('/products/fetch').then((response) => {
                    // getting all products, listed under their category, listed under their brand.
                    // data structure: {"data": [{brand_name: string, categories: [{category: string, cat_name: string, products: [{prod1, ..., ...}]}]}]}
                    this.brands = response.body;
                    this.loaded = true;
                });
            },
            orderByBrand () {
                this.brands.data.reverse();
            },
            destroy (id) {
                for (var i = 0; i < this.brands.data.length; i++) {
                    for (var j = 0; j < this.brands.data[i].categories.length; j++) {
                        for (var k = 0; k < this.brands.data[i].categories[j].products.length; k++) {
                            // loop through products[] and remove the product prop that destroy() was called from
                            (this.brands.data[i].categories[j].products[k].id === id) ? this.brands.data[i].categories[j].products.splice(j, 1) : false;
                        }
                        if (this.brands.data.length != 0 && this.brands.data[i].categories[j].products.length === 0) {
                            // remove empty categories
                            this.brands.data[i].categories.splice(j, 1);
                        }
                    }
                    if (this.brands.data.length != 0 && this.brands.data[i].categories.length === 0) {
                        // remove empty brands
                        this.brands.data.splice(i, 1);
                    }
                }
                return this.$http.delete('/products/' + id);
            }
        },
        mounted() {
            this.fetchProducts();
        }
    }
</script>
