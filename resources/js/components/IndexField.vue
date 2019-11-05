<template>
  <span>{{ value }}</span>
</template>

<script>
import HandlesFieldValue from '../mixins/HandlesFieldValue';

export default {
  mixins: [HandlesFieldValue],

  props: ['resourceName', 'field'],

  computed: {
    value() {
      if (this.isMultiselect) {
        const valuesArray = this.getInitialFieldValuesArray();
        if (!valuesArray) return '-';

        const values = valuesArray
          .map(val => this.field.options.find(opt => String(opt.value) === String(val)))
          .filter(val => !!val)
          .map(val => val.label);

        const joinedValues = values.join(', ');
        if (joinedValues.length <= 40) return joinedValues;

        return this.__('novaMultiselect.nItemsSelected', { count: values.length });
      } else {
        const value = this.field.options.find(opt => String(opt.value) === String(this.field.value));
        return (value && value.label) || '—';
      }
    },
  },
};
</script>
