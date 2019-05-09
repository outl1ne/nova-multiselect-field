Nova.booting((Vue, router, store) => {
    Vue.component('index-multi-select-field', require('./components/IndexField'))
    Vue.component('detail-multi-select-field', require('./components/DetailField'))
    Vue.component('form-multi-select-field', require('./components/FormField'))
})
