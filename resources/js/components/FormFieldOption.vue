<template>
  <span>
    {{ option.$isLabel ? option.$groupLabel : option.label }}
  </span>
</template>
<script>
export default {
  props: ['option', 'search', 'index'],
};
</script>
