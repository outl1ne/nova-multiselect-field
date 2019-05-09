Nova.booting((Vue, router, store) => {
  Vue.component('index-multiselect-field', require('./components/IndexField'));
  Vue.component('detail-multiselect-field', require('./components/DetailField'));
  Vue.component('form-multiselect-field', require('./components/FormField'));
});
