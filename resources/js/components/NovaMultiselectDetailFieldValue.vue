<template>
  <div
    class="multiselect-detail-field-value relative rounded-md border-gray-300 border bg-white dark:bg-gray-900 dark:border-gray-700"
    v-if="values"
  >
    <div class="overflow-hidden rounded-md">
      <div
        class="multiselect-detail-row border-b border-gray-300 dark:border-gray-700 cursor-text text-sm py-2 px-4"
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
  .rounded-md {
    border-radius: 0.25rem;
  }
}
</style>
