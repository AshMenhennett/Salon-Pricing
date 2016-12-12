
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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
Vue.component('service-category-select', require('./components/ServiceCategorySelectComponent.vue'));
Vue.component('product-category-select', require('./components/ProductCategorySelectComponent.vue'));
Vue.component('product-brand-select', require('./components/ProductBrandSelectComponent.vue'));

const app = new Vue({
    el: '#app'
});

// legal links aren't, by default visible on pricing pages, due to .total-price-footer 'hiding' them.
// we need to add margin-bottom prop to .footer-container to allow for users to see the legal links.
// Option 1: Adding css in specific vue components, where needed. Problem: The css gets added to all pages.
// Option 2: Adding css via jQuery, when doscument is ready. Problem: The element in question (.footer-container) isn't available until the component has finished the requests.
// Option 3: Using setTimeout(), and invoking jQuery methods after the component has 'probably' finished the requests. Problem: If the component takes longer that we think, the css won't be added. If we leave the timeout to long, the legal links wont be visible on initial viewing of the page
// Option 4: Removing loaded prop from compnonents, this way, the .has-large-price-footer and .has-price-footer elements, on pricing pages will be visible immediately, not only after requets are made (v-if="loaded")

// currently Vue templates with the target classes have had an enclosing element (div) added with the target class applied,
// so when the document is ready, the class will be visible, rather than waiting on a callback from a request.
$(document).ready(function () {
    if ($('.has-large-price-footer').length > 0) {
        $('.footer-container').css('margin-bottom', '166px');
    } else if ($('.has-price-footer').length > 0) {
        $('.footer-container').css('margin-bottom', '95px');
    }
});
