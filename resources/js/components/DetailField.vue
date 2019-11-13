<template>
  <panel-item :field="field">
    <template slot="value">
      <nova-multiselect-detail-field-value v-if="isMultiselect" :values="values" />

      <div v-else>
        {{ (value && value.label) || '—' }}
      </div>
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
      if (!valuesArray) return;

      return valuesArray
        .map(val => this.field.options.find(opt => String(opt.value) === String(val)))
        .filter(Boolean)
        .map(val => val.label);
    },
    value() {
      return this.field.options.find(opt => String(opt.value) === String(this.field.value));
    },
  },
};
</script>
