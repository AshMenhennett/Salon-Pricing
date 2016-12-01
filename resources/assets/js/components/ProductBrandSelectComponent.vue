<template>
    <div v-if="loaded">
        <select v-if="brands.length" name="brand" class="form-control" id="brand" required>
           <option v-for="brand in brands" v-bind:value="brand.brand" value="" v-bind:selected="selectedBrand === brand.brand || brand.selected">{{ brand.brand }}</option>
        </select>
        <input type="text" v-model="newBrand" class="form-control" placeholder="New brand">
        <a href="#" class="btn btn-info btn-block btn-sm pull-right custom-cat-btn" @click.prevent="addBrand()"><span class="glyphicon glyphicon-plus"></span> Add New Brand</a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 brands: [],
                 newBrand: null,
                 loaded: false
            }
        },
        props: {
            selectedBrand: null
        },
        methods: {
            fetchBrands () {
                return this.$http.get('/products/fetch/brands').then((response) => {
                    this.brands = response.body;
                    for (var i = 0; i < this.brands.length; i ++) {
                        this.brands[i]['selected'] = false;
                    }
                    this.loaded = true;
                });
            },
            addBrand () {
                this.brands.push({brand: this.newBrand, selected: true});
                this.newBrand = null;
            }
        },
        mounted() {
            this.fetchBrands();
        }
    }
</script>
