
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).ready(function () {
    if ($('.has-price-footer').length > 0) {
        $('.footer-container').css('margin-bottom', '95px');
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('products-table', require('./components/ProductsTableComponent.vue'));
Vue.component('services-table', require('./components/ServicesTableComponent.vue'));

Vue.component('product-pricing', require('./components/ProductPricingComponent.vue'));
Vue.component('service-pricing', require('./components/ServicePricingComponent.vue'));
Vue.component('all-pricing', require('./components/AllPricingComponent.vue'));

const app = new Vue({
    el: '#app'
});
