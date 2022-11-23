<template>
  <div :class="`text-${field.textAlign}`">
    <Link
      @click.stop
      v-if="field.belongsToResourceName && field.viewable && field.value"
      :href="$url(`/resources/${field.belongsToResourceName}/${field.value}`)"
      class="link-default no-underline font-bold dim"
    >
      {{ value }}
    </Link>
    <span v-else-if="!field.asHtml">{{ value }}</span>
    <span v-else-if="field.value" :html="field.value" />
    <span v-else>&mdash;</span>
  </div>
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
        // BelongsTo
        if (this.field.belongsToResourceName && this.field.viewable && this.field.value) {
          return this.limitIfNecessary(this.field.belongsToDisplayValue);
        }

        let value = this.field.options.find(opt => String(opt.value) === String(this.field.value));
        return this.limitIfNecessary((value && value.label) || '—');
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

  methods: {
    limitIfNecessary(value) {
      if (!value) return value;

      if (value.length > this.charDisplayLimit) {
        value = value.slice(0, this.charDisplayLimit) + '...';
      }

      return value;
    },
  },
};
</script>
