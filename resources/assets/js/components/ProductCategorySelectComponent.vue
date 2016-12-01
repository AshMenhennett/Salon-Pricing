<template>
    <div v-if="loaded">
        <select v-if="categories.length" name="category" class="form-control" id="category" required>
           <option v-for="category in categories" v-bind:value="category.category" value="" v-bind:selected="selectedCategory === category.category || category.selected">{{ category.category }}</option>
        </select>
        <input type="text" v-model="newCategory" class="form-control" placeholder="New category">
        <a href="#" class="btn btn-info btn-block btn-sm pull-right custom-cat-btn" @click.prevent="addCategory()"><span class="glyphicon glyphicon-plus"></span> Add New Category</a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 categories: [],
                 newCategory: null,
                 loaded: false
            }
        },
        props: {
            selectedCategory: null
        },
        methods: {
            fetchCategories () {
                return this.$http.get('/products/fetch/categories').then((response) => {
                    this.categories = response.body;
                    for (var i = 0; i < this.categories.length; i ++) {
                        this.categories[i]['selected'] = false;
                    }
                    this.loaded = true;
                });
            },
            addCategory () {
                this.categories.push({category: this.newCategory, selected: true});
                this.newCategory = null;
            }
        },
        mounted() {
            this.fetchCategories();
        }
    }
</script>
