<template>
  <panel-item :field="field">
    <template slot="value">
      <nova-multiselect-detail-field-value v-if="isMultiselect" :field="field" :values="values" />

      <div v-else>{{ (value && value.label) || '—' }}</div>
    </template>
  </panel-item>
</template>

<script>
import HandlesFieldValue from '../mixins/HandlesFieldValue';

export default {
  mixins: [HandlesFieldValue],

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    values() {
      const valuesArray = this.getInitialFieldValuesArray();
      if (!valuesArray || !valuesArray.length) return;

      return valuesArray
        .map(this.getValueFromOptions)
        .filter(Boolean)
        .map(val => `${this.isOptionGroups ? `[${val.group}] ` : ''}${val.label}`);
    },

    value() {
      return this.getValueFromOptions(this.field.value);
    },
  },
};
</script>
