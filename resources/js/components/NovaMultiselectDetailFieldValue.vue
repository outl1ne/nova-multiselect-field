<template>
  <div
    class="multiselect-detail-field-value relative rounded-lg border bg-white dark:bg-gray-900 dark:border-gray-700"
    v-if="values"
  >
    <div class="overflow-hidden rounded-lg">
      <div
        class="multiselect-detail-row border-b dark:border-gray-700 cursor-text font-mono text-sm py-2 px-4"
        v-for="(value, i) of values"
        :key="i"
      >
        {{ value }}
      </div>
    </div>
  </div>

  <div v-else>—</div>
</template>

<script>
export default {
  props: ['field', 'values'],
};
</script>

<style lang="scss" scoped>
.multiselect-detail-field-value {
  .multiselect-detail-row {
    &:last-child {
      border-bottom: none;
    }
  }
}
</style>
