export default {
  data() {
    return {
      options: [],
    };
  },

  beforeMount() {
    this.options = this.field.options || [];
  },

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
      let options = this.field.options;

      if (this.field.dependsOn) {
        const valueGroups = Object.values(this.field.dependsOnOptions || {});
        options = [];
        valueGroups.forEach(values =>
          Object.keys(values).forEach(value => options.push({ value, label: values[value] }))
        );
      }

      if (this.isOptionGroups) {
        return this.field.options
          .map(optGroup => optGroup.values.map(values => ({ ...values, group: optGroup.label })))
          .flat()
          .find(opt => String(opt.value) === String(value));
      }

      return options.find(opt => String(opt.value) === String(value));
    },
  },
  computed: {
    isMultiselect() {
      return !this.field.singleSelect;
    },

    isOptionGroups() {
      return !!this.field.options && !!this.field.options.find(opt => opt.values && Array.isArray(opt.values));
    },

    computedOptions() {
      let options = this.options || [];

      if (this.isOptionGroups) {
        const allLabels = options.map(opt => opt.values.map(o => o.label)).flat();
        options = options.map(option => {
          return {
            ...option,
            values: option.values.map(opt => {
              const isDuplicate = allLabels.filter(l => l === opt.label).length > 1;
              return { ...opt, label: isDuplicate ? `${opt.label} (${option.label})` : opt.label };
            }),
          };
        });
      }

      return options;
    },
  },
};
