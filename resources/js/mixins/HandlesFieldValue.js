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

    getValueFromOptions(value) {
      if (this.isOptionGroups) {
        return this.field.options
          .map(optGroup => optGroup.values.map(values => ({ ...values, group: optGroup.label })))
          .flat()
          .find(opt => String(opt.value) === String(value));
      }

      return this.field.options.find(opt => String(opt.value) === String(value));
    },
  },
  computed: {
    isMultiselect() {
      return !this.field.singleSelect;
    },

    isOptionGroups() {
      return !!this.field.options.find(opt => opt.values && Array.isArray(opt.values));
    },

    options() {
      return this.field.options || [];
    },
  },
};
