// Fix vue-draggable being stupid
window.Vue.component = () => false;

Nova.booting((Vue, router, store) => {
  Vue.component('index-multiselect-field', require('./components/IndexField').default);
  Vue.component('detail-multiselect-field', require('./components/DetailField').default);
  Vue.component('form-multiselect-field', require('./components/FormField').default);

  // Allow user to overwrite form-multiselect-field-tag
  if (!Vue._context.components['form-multiselect-field-tag']) {
    Vue.component(
      'form-multiselect-field-tag',
      require('./components/FormFieldTag').default
    );
  }

  // Allow user to overwrite nova-multiselect-detail-field-value
  if (!Vue._context.components['nova-multiselect-detail-field-value']) {
    Vue.component(
      'nova-multiselect-detail-field-value',
      require('./components/NovaMultiselectDetailFieldValue').default
    );
  }
});
