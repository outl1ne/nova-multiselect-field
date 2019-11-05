export default {
  methods: {
    getInitialFieldValuesArray() {
      try {
        if (!this.field.value) return void 0;
        if (Array.isArray(this.field.value)) return this.field.value;

        // Attempt to parse the field value
        if (typeof this.field.value === 'string') {
          let value = this.field.value;
          while (typeof value === 'string') value = JSON.parse(value);
          if (Array.isArray(value)) return value;
        }

        return void 0;
      } catch (e) {
        return void 0;
      }
    },
  },
  computed: {
    isMultiselect() {
      return !this.field.singleSelect;
    },
  },
};
