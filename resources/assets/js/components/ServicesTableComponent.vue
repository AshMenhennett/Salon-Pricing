<template>
    <div class="panel-body" v-if="loaded">
        <a href="/services/create" class="btn btn-default btn-top btn-block">Add a Service</a>
        <button v-if="categories.data.length" @click.prevent="orderByCategory()" class="btn btn-default">Order Categories <span class="glyphicon glyphicon-sort"></span></button>
        <br />
        <br />
        <div v-if="categories.data.length">
            <div v-for="(category, categoryIndex) in categories.data" class="parent-view-group-container">
                <h4 class="cat-name">{{ category.category }}</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th> <th>Price</th> <th></th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr v-for="(service, serviceIndex) in category.services">
                            <td>{{ service.title }}</td> <td>${{ service.price }}</td> <td><a href="#" @click.prevent="destroy(categoryIndex, serviceIndex, service.id)" class="pull-right text-danger manipulate-link"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a v-bind:href="'/services/' + service.id + '/edit'" class="pull-right text-info manipulate-link"><span class="glyphicon glyphicon-pencil"></span></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-else>You do not currently have any services listed.</div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 categories: {
                    data: []
                 },
                 loaded: false
            }
        },
        methods: {
            fetchServices () {
                return this.$http.get('/services/fetch').then((response) => {
                    // getting all services, listed under their category.
                    // data structure: {"data": [{category: string, services: [{service1, ..., ...}]}]}
                    this.categories = response.body;
                    this.loaded = true;
                });
            },
            orderByCategory () {
                this.categories.data.reverse();
            },
            destroy (categoryIndex, serviceIndex, id) {
                // remove the service from the array
                this.categories.data[categoryIndex].services.splice(serviceIndex, 1);
                // remove empty categories
                (this.categories.data.length != 0 && this.categories.data[categoryIndex].services.length === 0) ? this.categories.data.splice(categoryIndex, 1) : false;
                return this.$http.delete('/services/' + id);
            }
        },
        mounted() {
            this.fetchServices();
        }
    }
</script>
