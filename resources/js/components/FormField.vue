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
        :placeholder="field.placeholder || field.name"
        :close-on-select="false"
        :clear-on-select="false"
        :multiple="true"
        :max="field.max || null"
        :optionsLimit="field.optionsLimit || 1000"
      ></multiselect>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import Multiselect from 'vue-multiselect';

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
        const valuesArray = JSON.parse(this.field.value);
        if (!Array.isArray(valuesArray)) return (this.value = []);

        this.value = valuesArray
          .map(val => this.field.options.find(opt => String(opt.value) === String(val)))
          .filter(val => !!val);
      } else {
        this.value = [];
      }
    },

    fill(formData) {
      if (this.value && this.value.length) {
        formData.append(this.field.attribute, JSON.stringify(this.value.map(v => v.value)));
      }
    },

    handleChange(value) {
      this.value = value;
      this.$nextTick(() => this.repositionDropdown());
    },

    repositionDropdown(onOpen = false) {
      const ms = this.$refs.multiselect;
      const el = this.$el.children[1].children[0];

      const handlePositioning = () => {
        const { top, height, bottom } = el.getBoundingClientRect();
        if (onOpen) ms.$refs.list.scrollTop = 0;

        const fromBottom = (window.innerHeight || document.documentElement.clientHeight) - bottom;

        ms.$refs.list.style.position = 'fixed';
        ms.$refs.list.style.width = `${el.clientWidth}px`;

        if (fromBottom < 300) {
          ms.$refs.list.style.top = 'auto';
          ms.$refs.list.style.bottom = `${fromBottom + height}px`;
        } else {
          ms.$refs.list.style.bottom = 'auto';
          ms.$refs.list.style.top = `${top + height}px`;
        }
      };

      if (onOpen) this.$nextTick(handlePositioning);
      else handlePositioning();
    },
  },
};
</script>
