<template>
  <span>{{ value }}</span>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    value() {
      if (!this.field.value) return '-';

      const valuesArray = Array.isArray(this.field.value) ? this.field.value : JSON.parse(this.field.value);
      if (!Array.isArray(valuesArray)) return '-';

      const values = valuesArray
        .map(val => this.field.options.find(opt => String(opt.value) === val))
        .filter(val => !!val)
        .map(val => val.label);

      const joinedValues = values.join(', ');
      if (joinedValues.length <= 40) return joinedValues;

      return this.__('novaMultiselect.nItemsSelected', { count: values.length });
    },
  },
};
</script>
