<template>
    <div class="panel-body" v-if="loaded">
        <div v-if="categories.data.length">
            <a href="#" @click.prevent="orderByCategory()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <div v-for="category in categories.data" class="parent-view-group-container">
                <h4 class="cat-name">{{ category.category }}</h4>
                <ul class="list-group">
                    <li class="list-group-item" v-for="service in category.services">
                        <strong>{{ service.title }}</strong> : ${{ service.price }}
                        <span class="right-side-price-controls">
                            <input type="number" v-model="service.qty_selected" min="1">
                            <a href="#" @click.prevent="(!service.has_qty_added) ? addPrice(service) : subPrice(service)" class="btn" v-bind:class="(!service.has_qty_added) ? ' btn-success' : ' btn-danger'">{{ (!service.has_qty_added) ? 'ADD' : 'REMOVE' }}</a>
                            <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ service.qty_added }}</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>

        <footer v-if="categories.data.length" class="total-price-footer">
            <span class="total-price">${{ total.toFixed(2).replace('-', '') }} AUD</span>
            <br />
            <a href="#" @click.prevent="clear()" class="clear-price">Reset</a>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                 categories: {
                    data: []
                 },
                 total: 0,
                 loaded: false
            }
        },
        methods: {
            fetchServices () {
                return this.$http.get('/services/fetch').then((response) => {
                    // getting all services, listed under their category.
                    // data structure: {"data": [{category: string, services: [{service1, ..., ...}]}]}
                    this.categories = response.body;
                    for (var i = 0; i < this.categories.data.length; i++) {
                        for (var j = 0; j < this.categories.data[i].services.length; j++) {
                            // when a service's price has been added to total, we want to reflect that in the ui
                            Vue.set(this.categories.data[i].services[j], 'has_qty_added', false);
                            // keep track of qty input element of each service
                            Vue.set(this.categories.data[i].services[j], 'qty_selected', 1);
                            // keep track of the actual qty of the service
                            Vue.set(this.categories.data[i].services[j], 'qty_added', 0);
                        }
                    }
                    this.loaded = true;
                });
            },
            orderByCategory () {
                this.categories.data.reverse();
            },
            addPrice (service) {
                // get the qty input selected
                var qty = service.qty_selected;
                // set the qty selected to the service obj qty_added prop, saving the inputs state,
                // rather than altering the obj prop for every change in input
                service.qty_added = qty;
                this.total += (parseFloat(service.price) * qty);
                // when service.has_qty_added === true: remove button is shown
                service.has_qty_added = true;
            },
            subPrice (service) {
                // qty input by user
                var qty_selected = service.qty_selected;
                if (qty_selected > service.qty_added) {
                    // if the qty_selected input is greater than the qty_added prop of service,
                    // which is set when user adds qty of service
                    this.total -= (parseFloat(service.price) * service.qty_added);
                    service.qty_added = 0
                    service.has_qty_added = false;
                } else if (qty_selected < service.qty_added) {
                    // see initial condition for insight
                    this.total -= (parseFloat(service.price) * qty_selected);
                    service.qty_added = (service.qty_added - qty_selected);
                    // if there is still a qty of the service after removing the qty_selected amount
                    service.has_qty_added = (service.qty_added > 0) ? true : false;
                } else {
                    this.total -= (parseFloat(service.price) * qty_selected);
                    service.qty_added = 0;
                    service.has_qty_added = false;
                }
            },
            clear () {
                this.total = 0;
                for (var i = 0; i < this.categories.data.length; i++) {
                    for (var j = 0; j < this.categories.data[i].services.length; j++) {
                        this.categories.data[i].services[j].qty_added = 0;
                        if (this.categories.data[i].services[j].priceAdded === true) {
                            this.categories.data[i].services[j].has_qty_added = false;
                        }
                    }
                }
            }
        },
        mounted() {
            this.fetchServices();
        }
    }
</script>

<style>
    .footer-container {
        margin-bottom: 95px;
    }
</style>
