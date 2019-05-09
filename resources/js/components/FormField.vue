<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <multiselect
        @input="handleChange"
        track-by="value"
        label="label"
        :value="selected"
        :options="options"
        :class="errorClasses"
        :placeholder="field.name"
        :close-on-select="false"
        :clear-on-select="false"
        :multiple="true"
      ></multiselect>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import Multiselect from 'vue-multiselect';

const SEPARATOR = '|,|';

export default {
  components: { Multiselect },

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  computed: {
    selected() {
      return this.value || [];
    },
    options() {
      return this.field.options || [];
    },
  },

  methods: {
    setInitialValue() {
      if (this.field.value) {
        const selectedValues = this.field.value.split(SEPARATOR);
        this.value = selectedValues
          .map(val => this.field.options.find(opt => String(opt.value) === String(val)))
          .filter(val => !!val);
      } else {
        this.value = [];
      }
    },

    fill(formData) {
      const value = this.value && this.value.length ? this.value.map(v => v.value).join(SEPARATOR) : '';
      formData.append(this.field.attribute, value);
    },

    handleChange(value) {
      this.value = value;
    },
  },
};
</script>
