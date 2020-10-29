<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <div class="flex flex-col">
        <!-- Multi select field -->
        <multiselect
          v-if="!reorderMode"
          @input="handleChange"
          @open="() => repositionDropdown(true)"
          @search-change="asyncFindApi"
          track-by="value"
          label="label"
          :group-label="isOptionGroups ? 'label' : void 0"
          :group-values="isOptionGroups ? 'values' : void 0"
          :group-select="field.groupSelect || false"
          ref="multiselect"
          :value="selected"
          :options="field.apiUrl ? asyncOptions : computedOptions"
          :class="errorClasses"
          :placeholder="field.placeholder || field.name"
          :close-on-select="field.max === 1 || !isMultiselect"
          :clear-on-select="false"
          :multiple="isMultiselect"
          :max="max || field.max || null"
          :optionsLimit="field.optionsLimit || 1000"
          :limitText="count => __('novaMultiselect.limitText', { count: String(count || '') })"
          :selectLabel="__('novaMultiselect.selectLabel')"
          :selectGroupLabel="__('novaMultiselect.selectGroupLabel')"
          :selectedLabel="__('novaMultiselect.selectedLabel')"
          :deselectLabel="__('novaMultiselect.deselectLabel')"
          :deselectGroupLabel="__('novaMultiselect.deselectGroupLabel')"
          :clearOnSelect="field.clearOnSelect || false"
        >
          <template slot="maxElements">
            {{ __('novaMultiselect.maxElements', { max: String(field.max || '') }) }}
          </template>

          <template slot="noResult">
            {{ field.apiUrl && isLoading ? __('novaMultiSelect.LookingForMatches') : __('novaMultiselect.noResult') }}
          </template>

          <template slot="noOptions">
            {{ field.apiUrl ? __('novaMultiSelect.startTypingForOptions') : __('novaMultiselect.noOptions') }}
          </template>
        </multiselect>

        <!-- Reorder mode field -->
        <div v-if="reorderMode" class="form-input-bordered py-1">
          <vue-draggable tag="ul" v-model="value" class="flex flex-col pl-0" style="list-style: none; margin-top: 5px">
            <transition-group>
              <li v-for="(s, i) in selected" :key="i + 0" class="reorder__tag text-sm mb-1 px-2 py-1 text-white">
                {{ s.label }}
              </li>
            </transition-group>
          </vue-draggable>
        </div>

        <div
          v-if="field.reorderable"
          class="ml-auto mt-2 text-sm font-bold text-primary cursor-pointer dim"
          @click="reorderMode = !reorderMode"
        >
          {{ __(reorderMode ? 'novaMultiselect.doneReordering' : 'novaMultiselect.reorder') }}
        </div>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import HandlesFieldValue from '../mixins/HandlesFieldValue';
import Multiselect from 'vue-multiselect';
import VueDraggable from 'vuedraggable';
import debounce from 'lodash/debounce';

export default {
  components: { Multiselect, VueDraggable },

  mixins: [FormField, HandlesValidationErrors, HandlesFieldValue],

  props: ['resourceName', 'resourceId', 'field'],

  data: () => ({
    reorderMode: false,
    options: [],
    max: void 0,
    asyncOptions: [],
    isLoading: false,
  }),

  mounted() {
    window.addEventListener('scroll', this.repositionDropdown);

    if (this.field.dependsOn) {
      this.options = [];

      Nova.$on(`multiselect-${this.safeDependsOnAttribute}-input`, values => {
        values = Array.isArray(values) ? values : [values]; // Handle singleSelect

        // Clear options
        this.options = [];

        const newOptions = [];
        values.forEach(option => {
          if (!option) return;

          Object.keys(this.field.dependsOnOptions[option.value]).forEach(value => {
            // Only add unique
            if (newOptions.find(o => o.value === value)) return;

            let label = this.field.dependsOnOptions[option.value][value];
            newOptions.push({ label, value });
          });
        });

        this.options = newOptions;

        // Remove values that no longer apply
        const hasValue = value => this.options.find(v => v.value === value);
        if (this.isMultiselect) {
          if (Array.isArray(this.value)) {
            this.value = this.value.filter(v => !!v && !!hasValue(v.value));
          }
        } else {
          this.value = this.value && !!hasValue(this.value.value) ? this.value : void 0;
        }

        // Calculate max values
        const dependsOnMax = this.field.dependsOnMax;
        if (dependsOnMax) {
          const maxValues = values.map(option => {
            return option && (this.field.dependsOnMax[option.value] || null);
          });
          this.max = Math.max(...maxValues) || null;
        }
      });
    }

    // Emit initial value
    this.$nextTick(() => {
      Nova.$emit(`multiselect-${this.field.attribute}-input`, this.value);
    });
  },

  destroyed() {
    window.removeEventListener('scroll', this.repositionDropdown);
  },

  computed: {
    selected() {
      return this.value || [];
    },

    flexibleKey() {
      const flexRegex = /^([a-zA-Z0-9]+)(?=__)/;
      const match = this.field.attribute.match(flexRegex);
      if (match && match[0] && match[0].length === 16) return match[0];
    },

    safeDependsOnAttribute() {
      const flexibleKey = this.flexibleKey;
      if (!flexibleKey) return this.field.dependsOn;
      return `${flexibleKey}__${this.field.dependsOn}`;
    },
  },

  methods: {
    setInitialValue() {
      if (this.isMultiselect) {
        const valuesArray = this.getInitialFieldValuesArray();
        this.value = valuesArray && valuesArray.length ? valuesArray.map(this.getValueFromOptions).filter(Boolean) : [];
      } else {
        this.value = this.getValueFromOptions(this.field.value);
      }
    },

    fill(formData) {
      if (this.isMultiselect) {
        if (this.value && this.value.length) {
          this.value.forEach((v, i) => {
            formData.append(`${this.field.attribute}[${i}]`, v.value);
          });
        } else {
          formData.append(this.field.attribute, '');
        }
      } else {
        formData.append(this.field.attribute, (this.value && this.value.value) || '');
      }
    },

    handleChange(value) {
      this.value = value;
      this.$nextTick(() => this.repositionDropdown());
      Nova.$emit(`multiselect-${this.field.attribute}-input`, this.value);
    },

    repositionDropdown(onOpen = false) {
      const ms = this.$refs.multiselect;
      if (!ms) return;

      const el = ms.$el;

      const handlePositioning = () => {
        const { top, height, bottom } = el.getBoundingClientRect();
        if (onOpen) ms.$refs.list.scrollTop = 0;

        const fromBottom = (window.innerHeight || document.documentElement.clientHeight) - bottom;

        ms.$refs.list.style.position = 'fixed';
        ms.$refs.list.style.width = `${el.clientWidth}px`;

        if (fromBottom < 300) {
          ms.$refs.list.style.top = 'auto';
          ms.$refs.list.style.bottom = `${fromBottom + height}px`;
          ms.$refs.list.style['border-radius'] = '5px 5px 0 0';
        } else {
          ms.$refs.list.style.bottom = 'auto';
          ms.$refs.list.style.top = `${top + height}px`;
          ms.$refs.list.style['border-radius'] = '0 0 5px 5px';
        }
      };

      if (onOpen) this.$nextTick(handlePositioning);
      else handlePositioning();
    },

    fetchOptions: debounce(function (query) {
      fetch(`${this.field.apiUrl}?query=${query}`)
        .then(response => response.json())
        .then(response => {
          this.asyncOptions = Array.isArray(response)
            ? response
            : Object.entries(response).map(entry => ({ label: entry[1], value: entry[0] }));
          this.isLoading = false;
        });
    }, 500),

    asyncFindApi(query) {
      if (!this.field.apiUrl) return;

      if (query.length >= 1) {
        this.asyncOptions = [];
        try {
          this.isLoading = true;
          this.fetchOptions(query);
        } catch (error) {
          console.error('Error performing search:', error);
        }
      } else {
        this.asyncOptions = [];
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.reorder__tag {
  background: #41b883;
  border-radius: 5px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  transition: all 0.25s ease;
  margin-bottom: 5px;

  &:hover {
    cursor: pointer;
    background: #3dab7a;
    transition-duration: 0.05s;
  }
}
</style>
