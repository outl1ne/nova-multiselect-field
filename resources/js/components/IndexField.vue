<template>
  <span v-if="!field.asHtml">{{ value }}</span>
  <span v-else v-html="value"></span>
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
        if (!valuesArray || !valuesArray.length) return '—';

        const values = valuesArray
          .map(this.getValueFromOptions)
          .filter(Boolean)
          .map(val => `${this.isOptionGroups ? `[${val.group}] ` : ''}${val.label}`);

        const joinedValues = values.join(this.field.indexDelimiter ?? ', ');
        if (joinedValues.length <= (this.field.indexDisplayAtOnce ?? 40)) return joinedValues;

        return this.__('novaMultiselect.nItemsSelected', { count: String(values.length || '') });
      } else {
        const value = this.field.options.find(opt => String(opt.value) === String(this.field.value));
        return (value && value.label) || '—';
      }
    },
  },
};
</script>
