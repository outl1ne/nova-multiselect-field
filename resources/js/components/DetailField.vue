<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <router-link
        v-if="field.belongsToResourceName && field.viewable && field.value"
        :to="{
          name: 'detail',
          params: {
            resourceName: field.belongsToResourceName,
            resourceId: field.value,
          },
        }"
        class="no-underline font-bold dim text-primary"
      >
        {{ field.belongsToDisplayValue }}
      </router-link>

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
