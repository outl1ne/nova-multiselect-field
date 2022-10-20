<template>
  <div
    class="multiselect-detail-field-value relative o1-rounded border bg-white o1-border-gray-200 dark:o1-bg-gray-900 dark:o1-border-gray-700"
    v-if="values"
  >
    <div v-if="field.belongsToMany" class="overflow-hidden">
      <div v-for="(option, i) of values" :key="i" class="multiselect-detail-row border-b o1-border-gray-200 dark:o1-border-slate-800 o1-text-sm hover:o1-bg-slate-50 dark:hover:o1-bg-gray-800">
        <Link
            class="link-default inline-block o1-py-1 o1-px-2"
            :href="$url(`/resources/${field.attribute}/${field.value[i]}`)"
        >
          {{ option }}
        </Link>
      </div>
    </div>
    <div v-else class="overflow-hidden">
      <div
        class="multiselect-detail-row border-b o1-border-gray-200 dark:o1-border-slate-800 o1-text-sm o1-py-1 o1-px-2 hover:o1-bg-slate-50 dark:hover:o1-bg-gray-800"
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
