<template>
    <div class="panel-body" v-if="loaded">
        <a href="/services/create" class="btn btn-default btn-top btn-block">Add a Service</a>
        <table v-if="services.length" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th @click.prevent="orderByTitle()">Title &nbsp; <span class="glyphicon glyphicon-sort pull-right"></span></th> <th>Price</th> <th></th>
                </tr>
            </thead>
            <tbody>
                 <tr v-for="service in services">
                    <td>{{ service.title }}</td> <td>${{ service.price }}</td> <td><a href="#" @click.prevent="destroy(service.id)" class="pull-right text-danger manipulate-link"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a v-bind:href="'/services/service/' + service.id + '/edit'" class="pull-right text-info manipulate-link"><span class="glyphicon glyphicon-pencil"></span></a></td>
                </tr>
            </tbody>
        </table>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 services: [],
                 loaded: false
            }
        },
        methods: {
            fetchServices () {
                return this.$http.get('/services/fetch').then((response) => {
                    this.services = response.body;
                });
            },
            orderByTitle () {
                this.services.reverse();
            },
            destroy (id) {
                for (var i = 0; i < this.services.length; i++) {
                    (this.services[i].id === id) ? this.services.splice(i, 1) : false;
                }
                return this.$http.delete('/services/service/' + id);
            }
        },
        mounted() {
            this.fetchServices();
            this.loaded = true;
        }
    }
</script>
