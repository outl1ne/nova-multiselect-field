<template>
  <span class="multiselect__tag">
    <span>{{ option.label }}</span>
    <i class="multiselect__tag-icon" @click="remove(option)"></i>
  </span>
</template>

<script>
export default {
  props: ['option', 'remove'],
};
</script>
