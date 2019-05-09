<template>
  <span>{{ value }}</span>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    value() {
      if (!this.field.value) return '';
      return this.field.value
        .split(SEPARATOR)
        .map(val => this.field.options.find(opt => String(opt.value) === val))
        .filter(val => !!val)
        .map(val => val.label)
        .join(', ');
    },
  },
};
</script>
