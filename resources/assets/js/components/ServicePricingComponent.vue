<template>
    <div class="panel-body" v-if="loaded">
        <a href="/products/create" class="btn btn-default btn-top btn-block">Add a Product</a>
        <table v-if="products.length" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th> <th @click.prevent="orderByBrand()">Brand &nbsp; <span class="glyphicon glyphicon-sort pull-right"></span></th> <th>Price</th> <th></th>
                </tr>
            </thead>
            <tbody>
                 <tr v-for="product in products">
                    <td>{{ product.name }}</td> <td>{{ product.brand }}</td> <td>${{ product.price }}</td> <td><a href="#" @click.prevent="destroy(product.id)" class="pull-right text-danger manipulate-link"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a v-bind:href="'/products/product/' + product.id + '/edit'" class="pull-right text-info manipulate-link"><span class="glyphicon glyphicon-pencil"></span></a></td>
                </tr>
            </tbody>
        </table>
         <div v-else>
            <p>You currently don't have any products listed.</p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 products: [],
                 loaded: false
            }
        },
        methods: {
            fetchProducts () {
                return this.$http.get('/products/fetch').then((response) => {
                    this.products = response.body;
                });
            },
            orderByBrand () {
                this.products.reverse();
            },
            destroy (id) {
                for (var i = 0; i < this.products.length; i++) {
                    (this.products[i].id === id) ? this.products.splice(i, 1) : false;
                }
                return this.$http.delete('/products/product/' + id);
            }
        },
        mounted() {
            this.fetchProducts();
            this.loaded = true;
        }
    }
</script>
