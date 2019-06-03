<template>
  <panel-item :field="field">
    <template slot="value">
      <div class="relative rounded-t-lg rounded-b-lg shadow bg-30 border border-60" style="background-clip: border-box;" v-if="values">
        <div class="bg-white overflow-hidden rounded-b-lg rounded-t-lg">
          <div class="border-b border-50 cursor-text font-mono text-sm py-2 px-4" v-for="(value, i) of values" :key="i">
            {{ value }}
          </div>
        </div>
      </div>

      <div v-else>—</div>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    values() {
      if (!this.field.value) return;

      const valuesArray = JSON.parse(this.field.value);
      if (!Array.isArray(valuesArray) || !valuesArray.length) return;

      return valuesArray
        .map(val => this.field.options.find(opt => String(opt.value) === String(val)))
        .filter(val => !!val)
        .map(val => val.label);
    },
  },
};
</script>
