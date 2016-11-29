<template>
    <div class="panel-body has-price-footer" v-if="loaded">
        <div v-if="services.length">
            <a href="#" @click.prevent="orderByTitle()" class="btn btn-default btn-top pull-left">Order <span class="glyphicon glyphicon-sort"></span></a>
            <br />
            <br />
            <ul class="list-group">
                 <li v-for="service in services" class="list-group-item">
                    <strong>{{ service.title }}</strong> : ${{ service.price }}
                    <span class="right-side-price-controls">
                        <input type="number" v-model="service.qty_selected" min="1">
                        <a href="#" v-if="!service.price_added" @click.prevent="addPrice(service.id, service.price)" class="btn btn-success">ADD</a>
                        <a href="#" v-else @click.prevent="subPrice(service.id, service.price)" class="btn btn-danger">REMOVE</a>
                        <span><span class="glyphicon glyphicon-shopping-cart"></span> {{ service.qty_added }}</span>
                    </span>
                </li>
            </ul>
        </div>
         <div v-else>
            <p>You currently don't have any services listed.</p>
        </div>

        <footer v-if="services.length" class="total-price-footer">
            ${{ total.toFixed(2).replace('-', '') }} AUD
            <br />
            <a href="#" @click.prevent="clear()" class="clear-price">Reset</a>
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
                        // when a service's price has been added to total, we want to reflect that in the ui
                        Vue.set(this.services[i], 'price_added', false);
                        // keep track of qty input element of each service
                        Vue.set(this.services[i], 'qty_selected', 1);
                        // keep track of the actual qty of the service
                        Vue.set(this.services[i], 'qty_added', 0);
                    }
                });
            },
            orderByTitle () {
                this.services.reverse();
            },
            addPrice (id, price) {
                // get the qty input selected
                var qty = this.getServiceQtySelected(id);
                // set the qty selected to the service obj qty_added prop, saving the inputs state,
                // rather than altering the obj prop for every change in input
                this.setServiceQtyAdded(id, qty);
                this.total += (parseFloat(price) * qty);
                // when service.price_added === true: remove button is shown
                this.setPriceAddedProp(id, true);
            },
            subPrice (id, price) {
                // qty input by user
                var qty_selected = this.getServiceQtySelected(id);
                if (qty_selected > this.getServiceQtyAdded(id)) {
                    // if the qty_selected input is greater than the qty_added prop of service,
                    // which is set when user adds qty of service
                    this.total -= (parseFloat(price) * this.getServiceQtyAdded(id));
                    this.setServiceQtyAdded(id, 0);
                    this.setPriceAddedProp(id, false);
                } else if (qty_selected < this.getServiceQtyAdded(id)) {
                    // see initial conditional statement comments for insight
                    this.total -= (parseFloat(price) * qty_selected);
                    this.setServiceQtyAdded(id, (this.getServiceQtyAdded(id) - qty_selected));
                    if (this.getServiceQtyAdded(id) > 0) {
                        // if there is still a qty of the service after removing the qty_selected amount
                        this.setPriceAddedProp(id, true);
                    } else {
                        this.setPriceAddedProp(id, false);
                    }
                } else {
                    this.total -= (parseFloat(price) * qty_selected);
                    this.setServiceQtyAdded(id, 0);
                    this.setPriceAddedProp(id, false);
                }
            },
            setPriceAddedProp (id, val) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        this.services[i].price_added = val;
                    }
                }
            },
            getPriceAddedProp (id) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        return this.services[i].price_added;
                    }
                }
            },
            getServiceQtySelected (id) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                        return this.services[i].qty_selected;
                    }
                }
            },
            setServiceQtyAdded (id, qty) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                       this.services[i].qty_added = qty;
                    }
                }
            },
            getServiceQtyAdded (id) {
                for (var i = 0; i < this.services.length; i++) {
                    if (this.services[i].id === id) {
                       return this.services[i].qty_added;
                    }
                }
            },
            clear () {
                this.total = 0;
                for (var i = 0; i < this.services.length; i++) {
                    this.services[i].qty_added = 0;
                    if (this.services[i].priceAdded === true) {
                        this.services[i].price_added = false;
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
