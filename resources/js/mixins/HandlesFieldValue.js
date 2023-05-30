export default {
  data() {
    return {
      isInitialized: true,
    };
  },

  beforeMount() {
    this.options = this.currentField.options || [];
  },

  methods: {
    getInitialFieldValuesArray() {
      try {
        if (Array.isArray(this.currentField.value)) return this.currentField.value;

        // Attempt to parse the field value
        if (typeof this.currentField.value === 'string') {
          let value = this.currentField.value;
          while (typeof value === 'string') value = JSON.parse(value);
          if (Array.isArray(value)) return value;
        }

        return void 0;
      } catch (e) {
        return void 0;
      }
    },

    getValueFromOptions(value) {
      let options = this.currentField.options || [];

      if (this.currentField.optionsDependOn) {
        const valueGroups = Object.values(this.currentField.optionsDependOnOptions || {});
        options = [];
        valueGroups.forEach(values =>
          Object.keys(values).forEach(value => options.push({ value, label: values[value] }))
        );
      }

      if (this.isOptionGroups) {
        return this.currentField.options
          .map(optGroup => optGroup.values.map(values => ({ ...values, group: optGroup.label })))
          .flat()
          .find(opt => String(opt.value) === String(value));
      }

      const option = options.find(opt => String(opt.value) === String(value));
      if (option) return option;

      // Taggable support
      if (this.currentField.taggable) return { label: value, value };
    },
  },
  computed: {
    currentField() {
      return this.syncedField || this.field;
    },

    isMultiselect() {
      return !this.currentField.singleSelect;
    },

    isOptionGroups() {
      return (
        !!this.currentField.options && !!this.currentField.options.find(opt => opt.values && Array.isArray(opt.values))
      );
    },

    computedOptions() {
      // Return empty array if the multiselect has not been opened yet.
      if (!this.isInitialized) return [];
      let options = this.options || [];

      if (this.isOptionGroups) {
        const allLabels = options.map(opt => opt.values.map(o => o.label)).flat();
        options = options.map(option => {
          return {
            ...option,
            values: option.values.map(opt => {
              const isDuplicate = this.mode === 'form' ? false : allLabels.filter(l => l === opt.label).length > 1;
              return { ...opt, label: isDuplicate ? `${opt.label} (${option.label})` : opt.label };
            }),
          };
        });
      }

      return options;
    },
  },
};
