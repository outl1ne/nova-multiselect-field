<template>
  <span>{{ value }}</span>
</template>

<script>
import SEPARATOR from '../separator';

export default {
  props: ['resourceName', 'field'],

  computed: {
    value() {
      if (!this.field.value) return '-';
      const values = this.field.value
        .split(SEPARATOR)
        .map(val => this.field.options.find(opt => String(opt.value) === val))
        .filter(val => !!val)
        .map(val => val.label);

      const joinedValues = values.join(', ');
      if (joinedValues.length <= 40) return joinedValues;

      return `${values.length} items selected`;
    },
  },
};
</script>
