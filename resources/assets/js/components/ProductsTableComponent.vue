<template>
    <div class="panel-body" v-if="loaded">
        <a href="/products/create" class="btn btn-default btn-top btn-block">Add a Product</a>
        <button @click.prevent="orderByBrand()" class="btn btn-default">Order Brands <span class="glyphicon glyphicon-sort"></span></button>
        <br />
        <br />
        <div v-if="brands.data.length">
            <div v-for="(brand, brandIndex) in brands.data" class="parent-view-group-container">
                <h4 class="brand-name">{{ brand.brand_name }}</h4>
                <div v-for="(category, categoryIndex) in brand.categories" class="well">
                    <h4 class="cat-name">{{ category.category }}</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th> <th>Price</th> <th></th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr v-for="(product, productIndex) in category.products">
                                <td>{{ product.name }}</td>  <td>${{ product.price }}</td> <td><a href="#" @click.prevent="destroy(brandIndex, categoryIndex, productIndex, product.id)" class="pull-right text-danger manipulate-link"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a v-bind:href="'/products/' + product.id + '/edit'" class="pull-right text-info manipulate-link"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
                    // data structure: {"data": [{brand_name: string, categories: [{category: string, products: [{prod1, ..., ...}]}]}]}
                    this.brands = response.body;
                    this.loaded = true;
                });
            },
            orderByBrand () {
                this.brands.data.reverse();
            },
            destroy (brandIndex, categoryIndex, productIndex, id) {
                // remove the product from the array
                this.brands.data[brandIndex].categories[categoryIndex].products.splice(productIndex, 1);
                // remove empty categories and brands
                (this.brands.data.length != 0 && this.brands.data[brandIndex].categories[categoryIndex].products.length === 0) ? this.brands.data[brandIndex].categories.splice(categoryIndex, 1) : false;
                (this.brands.data.length != 0 && this.brands.data[brandIndex].categories.length === 0) ? this.brands.data.splice(brandIndex, 1) : false;
                // send delete request to server
                return this.$http.delete('/products/' + id);
            }
        },
        mounted() {
            this.fetchProducts();
        }
    }
</script>
