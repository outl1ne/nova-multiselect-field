<template>
  <default-field :field="field" :showHelpText="showHelpText" :errors="errors">
    <template slot="field">
      <div class="multiselect-field flex flex-col">
        <!-- Multi select field -->
        <multiselect
          v-if="!reorderMode"
          @input="handleChange"
          @open="handleOpen"
          @search-change="tryToFetchOptions"
          track-by="value"
          label="label"
          :group-label="isOptionGroups ? 'label' : void 0"
          :group-values="isOptionGroups ? 'values' : void 0"
          :group-select="field.groupSelect || false"
          ref="multiselect"
          :value="selected"
          :options="field.apiUrl ? asyncOptions : computedOptions"
          :internal-search="!field.apiUrl"
          :class="errorClasses"
          :disabled="isReadonly"
          :placeholder="field.placeholder || field.name"
          :close-on-select="field.max === 1 || !isMultiselect"
          :multiple="isMultiselect"
          :max="max || field.max || null"
          :optionsLimit="field.optionsLimit || 1000"
          :limitText="count => __('novaMultiselect.limitText', { count: String(count || '') })"
          selectLabel=""
          :loading="isLoading"
          selectGroupLabel=""
          selectedLabel=""
          deselectLabel=""
          deselectGroupLabel=""
          :clearOnSelect="field.clearOnSelect || false"
        >
          <template slot="maxElements">
            {{ __('novaMultiselect.maxElements', { max: String(field.max || '') }) }}
          </template>

          <template slot="noResult">
            {{ __('novaMultiselect.noResult') }}
          </template>

          <template slot="noOptions">
            {{ field.apiUrl ? __('novaMultiSelect.startTypingForOptions') : __('novaMultiselect.noOptions') }}
          </template>

          <template slot="clear">
            <div
              class="multiselect__clear"
              v-if="field.nullable && (isMultiselect ? value.length : value)"
              @mousedown.prevent.stop="value = isMultiselect ? [] : null"
            ></div>
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

          Object.keys(this.field.dependsOnOptions[option.value] || {}).forEach(value => {
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

    if (this.field.distinct) {
      // Handle distinct callback.
      Nova.$on(`multiselect-${this.field.distinct}-distinct`, callback => {
        return callback(this.value);
      });
    }

    // Emit initial value
    this.$nextTick(() => {
      Nova.$emit(`multiselect-${this.field.attribute}-input`, this.value);
    });
  },

  destroyed() {
    window.removeEventListener('scroll', this.repositionDropdown);
    if (this.field.distinct) Nova.$off(`multiselect-${this.field.distinct}-distinct`);
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

    handleOpen() {
      this.repositionDropdown(true);
      if (this.field.distinct) this.distinctOptions();
    },

    /**
     * Creates new array of values that have been used by another multiselect.
     * If an options is used by another multiselect, we disable it.
     */
    distinctOptions() {
      const distinctValues = [];

      // Fetch other select values in current distinct group
      Nova.$emit(`multiselect-${this.field.distinct}-distinct`, values => {
        // Validate that current value is not disabled.
        if (values !== this.value) {
          // Add already used values to distinctValues
          if (this.isMultiselect) distinctValues.push(...values.map(value => value.value));
          else distinctValues.push(values.value);
        }
      });

      this.options = this.options.map(option => {
        // Only update option if values match
        if (distinctValues.includes(option.value)) return { ...option, $isDisabled: true };
        return option;
      });
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

    fetchOptions: debounce(async function (search) {
      const { data } = await Nova.request().get(`${this.field.apiUrl}`, { params: { search } });

      // Response is not an array or an object
      if (typeof data !== 'object') throw new Error('Server response was invalid.');

      // Is array
      if (Array.isArray(data)) {
        this.asyncOptions = data;
        this.isLoading = false;
        return;
      }

      // Nova resource response
      if (data.resources) {
        const newOptions = [];

        for (const resource of data.resources) {
          const label = resource.title;
          const value = resource.id.value;
          newOptions.push({ value, label });
        }

        this.asyncOptions = newOptions;
        this.isLoading = false;
        return;
      }

      this.asyncOptions = Object.entries(data).map(entry => ({ label: entry[1], value: entry[0] }));
      this.isLoading = false;
    }, 500),

    tryToFetchOptions(query) {
      if (!this.field.apiUrl) return;

      if (query.length >= 1) {
        this.asyncOptions = [];
        this.isLoading = true;
        try {
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

<style lang="scss">
.multiselect-field {
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

  .multiselect__clear {
    position: absolute;
    right: 41px;
    height: 40px;
    width: 40px;
    display: block;
    cursor: pointer;
    z-index: 2;

    &::before,
    &::after {
      content: '';
      display: block;
      position: absolute;
      width: 3px;
      height: 16px;
      background: #aaa;
      top: 12px;
      right: 4px;
    }

    &::before {
      transform: rotate(45deg);
    }

    &::after {
      transform: rotate(-45deg);
    }
  }
}
</style>
