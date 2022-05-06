<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <Link
        v-if="field.belongsToResourceName && field.viewable && field.value"
        :href="$url(`/resources/${field.belongsToResourceName}/${field.value}`)"
        class="link-default no-underline font-bold dim"
      >
        {{ field.belongsToDisplayValue }}
      </Link>

      <nova-multiselect-detail-field-value v-else-if="isMultiselect" :field="field" :values="values" />

      <div v-else>{{ (value && value.label) || '—' }}</div>
    </template>
  </PanelItem>
</template>

<script>
import HandlesFieldValue from '../mixins/HandlesFieldValue';

export default {
  mixins: [HandlesFieldValue],

  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

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
