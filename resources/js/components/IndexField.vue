<template>
  <div :class="`text-${field.textAlign}`" v-if="field.belongsToResourceName && field.viewable && field.value">
    <span>
      <span v-if="field.viewable && field.value">
        <router-link
          :to="{
            name: 'detail',
            params: {
              resourceName: field.belongsToResourceName,
              resourceId: field.value,
            },
          }"
          class="no-underline dim text-primary font-bold"
        >
          {{ field.belongsToDisplayValue }}
        </router-link>
      </span>

      <span v-else-if="field.value">{{ field.value }}</span>

      <span v-else>&mdash;</span>
    </span>
  </div>
  <span v-else-if="!field.asHtml">{{ value }}</span>
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

        const joinedValues = values.join(this.delimiter);

        if (this.valueDisplayLimit >= values.length && this.charDisplayLimit >= joinedValues.length) {
          return joinedValues;
        }

        return this.__('novaMultiselect.nItemsSelected', { count: String(values.length || '') });
      } else {
        const value = this.field.options.find(opt => String(opt.value) === String(this.field.value));
        return (value && value.label) || '—';
      }
    },

    delimiter() {
      return this.field.indexDelimiter || ', ';
    },

    valueDisplayLimit() {
      return this.field.indexValueDisplayLimit || 9999;
    },

    charDisplayLimit() {
      // Set char limit to 9999 if we have value limit, but not char limit
      if (!!this.field.indexValueDisplayLimit && !this.field.indexCharDisplayLimit) return 9999;
      return this.field.indexCharDisplayLimit || 40;
    },
  },
};
</script>
