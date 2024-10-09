<template>
  <span>{{ option ? option.label : '' }}</span>
</template>
<script>
export default {
  props: ['option', 'search', 'index'],
};
</script>
