<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <multiselect
        @input="handleChange"
        @open="() => repositionDropdown(true)"
        track-by="value"
        label="label"
        ref="multiselect"
        :value="selected"
        :options="options"
        :class="errorClasses"
        :placeholder="field.name"
        :close-on-select="false"
        :clear-on-select="false"
        :multiple="true"
        :max="field.max"
      ></multiselect>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import Multiselect from 'vue-multiselect';
import SEPARATOR from '../separator';

export default {
  components: { Multiselect },

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  mounted() {
    window.addEventListener('scroll', this.repositionDropdown);
  },

  destroyed() {
    window.removeEventListener('scroll', this.repositionDropdown);
  },

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
          .map(val => this.field.options.find(opt => String(opt.value) === val))
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
      this.$nextTick(() => this.repositionDropdown());
    },

    repositionDropdown(onOpen = false) {
      const ms = this.$refs.multiselect;
      const el = this.$el.children[1].children[0];

      const handlePositioning = () => {
        const { top, height } = el.getBoundingClientRect();
        if (onOpen) ms.$refs.list.scrollTop = 0;
        ms.$refs.list.style.width = `${el.clientWidth}px`;
        ms.$refs.list.style.position = 'fixed';
        ms.$refs.list.style.bottom = 'auto';
        ms.$refs.list.style.top = `${top + height}px`;
      };

      if (onOpen) this.$nextTick(handlePositioning);
      else handlePositioning();
    },
  },
};
</script>
