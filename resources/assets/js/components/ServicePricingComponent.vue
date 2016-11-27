<template>
    <div class="panel-body has-price-footer" v-if="loaded">
        <div v-if="services.length">
            <a href="#" @click.prevent="orderByTitle()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <ul class="list-group">
                 <li v-for="service in services" class="list-group-item">
                    <strong>{{ service.title }}</strong> : ${{ service.price }}
                    <span v-if="!service.priceAdded"><a href="#" @click.prevent="addPrice(service.id, service.price)" class="btn btn-success pull-right">ADD</a></span>
                    <span v-else><a href="#" @click.prevent="subPrice(service.id, service.price)" class="btn btn-danger pull-right">REMOVE</a></span>
                </li>
            </ul>
        </div>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>

        <footer v-if="services.length" class="total-price-footer">
            ${{ total.toFixed(2).replace('-', '') }} AUD
            <br />
            <a href="#" @click.prevent="clear()" class="clear-price">Clear Price</a>
        </footer>
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
                this.changePriceAddedProp(id, true);
            },
            subPrice (id, price) {
                this.total -= parseFloat(price);
                this.changePriceAddedProp(id, false);
            },
            changePriceAddedProp (id, val) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        this.services[i].priceAdded = val;
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
