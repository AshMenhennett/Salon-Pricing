<template>
    <div class="panel-body has-footer" v-if="loaded">
        <a href="#" @click.prevent="orderByTitle()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
        <br />
        <br />
        <ul v-if="services.length" class="list-group">
             <li v-for="service in services" class="list-group-item">
                <strong>{{ service.title }}</strong> : ${{ service.price }}
                <span v-if="!service.priceAdded"><a href="#" @click.prevent="addPrice(service.id, service.price)" class="btn btn-success pull-right">ADD</a></span>
                <span v-else><a href="#" @click.prevent="subPrice(service.id, service.price)" class="btn btn-danger pull-right">REMOVE</a></span>
            </li>
        </ul>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>
        <div v-if="services.length" class="total-price-footer">
            ${{ total.toFixed(2).replace('-', '') }} AUD
            <br />
            <a href="#" @click.prevent="clear()" style="font-weight: bolder; color: #ddd; text-decoration: underline;">Clear Price</a>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 services: [],
                 total: 0,
                 loaded: false
            }
        },
        methods: {
            fetchServices () {
                return this.$http.get('/services/fetch').then((response) => {
                    this.services = response.body;
                    for (var i = 0; i < this.services.length; i++) {
                        this.services[i]['priceAdded'] = false;
                    }
                });
            },
            orderByTitle () {
                this.services.reverse();
            },
            addPrice (id, price) {
                this.total += parseFloat(price);
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        this.services[i].priceAdded = true;
                    }
                }
            },
            subPrice (id, price) {
                this.total -= parseFloat(price);
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        this.services[i].priceAdded = false;
                    }
                }
            },
            clear () {
                this.total = 0;
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].priceAdded === true) {
                        this.services[i].priceAdded = false;
                    }
                }
            }
        },
        mounted() {
            this.fetchServices();
            this.loaded = true;
            this.total = 0;
        }
    }
</script>
