<template>
    <div class="panel-body" v-if="loaded">
        <a href="/services/create" class="btn btn-default btn-top btn-block">Add a Service</a>
        <button @click.prevent="orderByCategory()" class="btn btn-default">Order Categories <span class="glyphicon glyphicon-sort"></span></button>
        <br />
        <br />
        <div v-if="categories.data.length">
            <div v-for="category in categories.data" class="parent-view-group-container">
                <h4 style="text-transform: capitalize;">{{ category.cat_name }}</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th> <th>Price</th> <th></th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr v-for="service in category.services">
                            <td>{{ service.title }}</td> <td>${{ service.price }}</td> <td><a href="#" @click.prevent="destroy(service.id)" class="pull-right text-danger manipulate-link"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a v-bind:href="'/services/' + service.id + '/edit'" class="pull-right text-info manipulate-link"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
                    // data structure: {"data": [{cat_name: string, services: [{service1, ..., ...}]}]}
                    this.categories = response.body;
                    this.loaded = true;
                });
            },
            orderByCategory () {
                this.categories.data.reverse();
            },
            destroy (id) {
                for (var i = 0; i < this.categories.data.length; i++) {
                    for (var j = 0; j < this.categories.data[i].services.length; j++) {
                        // loop through services[] and remove the service prop that destroy() was called from
                        (this.categories.data[i].services[j].id === id) ? this.categories.data[i].services.splice(j, 1) : false;
                    }
                    if (this.categories.data.length != 0 && this.categories.data[i].services.length === 0) {
                        // if there is still some data and there are services, lets remove the lonely categories (no services under them)
                        this.categories.data.splice(i, 1);
                    }
                }
                return this.$http.delete('/services/' + id);
            }
        },
        mounted() {
            this.fetchServices();
        }
    }
</script>
