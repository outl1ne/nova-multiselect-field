Nova.booting((Vue, router, store) => {
  Vue.component('index-multiselect-field', require('./components/IndexField').default);
  Vue.component('detail-multiselect-field', require('./components/DetailField').default);
  Vue.component('form-multiselect-field', require('./components/FormField').default);
});
