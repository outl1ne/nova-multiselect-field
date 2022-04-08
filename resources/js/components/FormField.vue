<template>
  <DefaultField :field="field" :showHelpText="showHelpText" :errors="errors">
    <template #field>
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
          :limit="field.limit"
          :limitText="count => __('novaMultiselect.limitText', { count: String(count || '') })"
          selectLabel=""
          :loading="isLoading"
          selectGroupLabel=""
          selectedLabel=""
          deselectLabel=""
          deselectGroupLabel=""
          :clearOnSelect="field.clearOnSelect || false"
          :taggable="field.taggable || false"
          @tag="addTag"
        >
          <template #maxElements>
            {{ __('novaMultiselect.maxElements', { max: String(field.max || '') }) }}
          </template>

          <template #noResult>
            {{ __('novaMultiselect.noResult') }}
          </template>

          <template #noOptions>
            {{ field.apiUrl ? __('novaMultiSelect.startTypingForOptions') : __('novaMultiselect.noOptions') }}
          </template>

          <template #clear>
            <div
              class="multiselect__clear"
              v-if="field.nullable && (isMultiselect ? value.length : value)"
              @mousedown.prevent.stop="value = isMultiselect ? [] : null"
            ></div>
          </template>

          <template #singleLabel>
            <span>{{ value ? value.label : '' }}</span>
          </template>
        </multiselect>

        <!-- Reorder mode field -->
        <div v-if="reorderMode" class="form-input-bordered py-1 px-2 rounded-lg">
          <ul class="flex flex-col pl-0" style="list-style: none; margin-top: 5px">
            <vue-draggable v-model="value" tag="transition-group">
              <template #item="{ element }">
                <li class="reorder__tag text-sm mb-1 px-2 py-1 text-white">
                  {{ element.label }}
                </li>
              </template>
            </vue-draggable>
          </ul>
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
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import HandlesFieldValue from '../mixins/HandlesFieldValue';
import Multiselect from 'vue-multiselect/src/Multiselect';
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
    distinctValues: [],
    isLoading: false,
    isInitialized: false,
  }),

  mounted() {
    window.addEventListener('scroll', this.repositionDropdown);

    if (this.field.optionsDependOn) {
      this.options = [];

      Nova.$on(`multiselect-${this.safeDependsOnAttribute}-input`, values => {
        values = Array.isArray(values) ? values : [values]; // Handle singleSelect

        // Clear options
        this.options = [];

        const newOptions = [];
        values.forEach(option => {
          if (!option) return;

          Object.keys(this.field.optionsDependOnOptions[option.value] || {}).forEach(value => {
            // Only add unique
            if (newOptions.find(o => o.value === value)) return;

            let label = this.field.optionsDependOnOptions[option.value][value];
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
        const dependsOnMax = this.field.optionsDependOnMax;
        if (dependsOnMax) {
          const maxValues = values.map(option => {
            return option && (this.field.optionsDependOnMax[option.value] || null);
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
      if (this.field.optionsDependOnOutsideFlexible) {
        return this.field.optionsDependOn;
      }

      const flexibleKey = this.flexibleKey;
      if (!flexibleKey) return this.field.optionsDependOn;
      return `${flexibleKey}__${this.field.optionsDependOn}`;
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
      // For some reason, after upgrading to Vue 3, this callback
      // Sometimes receives an InputEvent as an argument - my only
      // fix is to filter out the InputEvent and only accept arrays
      if (this.isMultiselect) {
        if (!Array.isArray(value)) return;
      } else {
        if (value && !value.value) return;
      }

      this.value = value;
      this.$nextTick(() => this.repositionDropdown());
      Nova.$emit(`multiselect-${this.field.attribute}-input`, this.value);
    },

    handleOpen() {
      this.repositionDropdown(true);
      if (!this.isInitialized) this.isInitialized = true;
      if (this.field.distinct) this.distinctOptions();
    },

    /**
     * Creates new array of values that have been used by another multiselect.
     * If an options is used by another multiselect, we disable it.
     */
    distinctOptions() {
      this.distinctValues = [];

      // Fetch other select values in current distinct group
      Nova.$emit(`multiselect-${this.field.distinct}-distinct`, values => {
        if (!values) return;

        // Validate that current value is not disabled.
        if (values !== this.selected) {
          // Add already used values to distinctValues
          if (Array.isArray(values)) this.distinctValues.push(...values.map(value => value.value));
          else this.distinctValues.push(values.value);
        }
      });

      this.options = this.options.map(option => {
        if (this.isOptionGroups) {
          // Support for option groups
          return {
            ...option,
            values: option.values.map(option => this.newDistinctOption(option)),
          };
        }

        return this.newDistinctOption(option);
      });
    },

    newDistinctOption(option) {
      // Only return $disabled option if values match
      if (this.distinctValues.includes(option.value)) return { ...option, $isDisabled: true };

      // Force remove $isDisabled
      delete option.$isDisabled;
      return option;
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

    addTag(newTag) {
      const tag = {
        label: newTag,
        value: newTag,
      };

      this.options.push(tag);

      if (this.isMultiselect) {
        this.value.push(tag);
      } else {
        this.value = tag;
      }
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
          const label = resource.display || resource.title || '-';
          const value = resource.value || resource.id.value || null;
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
  .multiselect__tags {
    --tw-border-opacity: 1;
    border-width: 1px;

    border-color: rgba(var(--colors-gray-300), var(--tw-border-opacity));
    background-color: rgba(var(--colors-white), var(--tw-bg-opacity));
    color: rgba(var(--colors-gray-600), var(--tw-text-opacity));

    @media (prefers-color-scheme: dark) {
      border-color: rgba(var(--colors-gray-700), var(--tw-border-opacity));
      background-color: rgba(var(--colors-gray-900), var(--tw-bg-opacity));
      color: rgba(var(--colors-gray-400), var(--tw-text-opacity));
    }
  }

  .multiselect__input {
    border: none;
    background-color: rgba(var(--colors-white), var(--tw-bg-opacity));
    color: rgba(var(--colors-gray-600), var(--tw-text-opacity));

    @media (prefers-color-scheme: dark) {
      background-color: rgba(var(--colors-gray-900), var(--tw-bg-opacity));
      color: rgba(var(--colors-gray-400), var(--tw-text-opacity));
    }
  }

  .multiselect__tag {
    background-color: rgba(var(--colors-primary-500));
    color: rgba(var(--colors-white), var(--tw-text-opacity));
    --tw-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    font-weight: 700;

    /* @media (prefers-color-scheme: dark) {
      color: rgba(var(--colors-gray-900), var(--tw-text-opacity));
    } */

    .multiselect__tag-icon {
      &::after {
        color: rgba(var(--colors-white));
      }

      &:hover {
        background: rgba(var(--colors-primary-400));

        &::after {
          color: rgba(var(--colors-red-500));
        }
      }
    }
  }

  .multiselect > .multiselect__clear {
    &::before,
    &::after {
      width: 2px;
      background: rgba(var(--colors-gray-400));
    }

    &:hover {
      &::before,
      &::after {
        background: rgba(var(--colors-red-400));
      }
    }
  }

  .multiselect__single {
    background-color: rgba(var(--colors-white), var(--tw-bg-opacity));
    color: rgba(var(--colors-gray-600), var(--tw-text-opacity));

    @media (prefers-color-scheme: dark) {
      background-color: rgba(var(--colors-gray-900), var(--tw-bg-opacity));
      color: rgba(var(--colors-gray-400), var(--tw-text-opacity));
    }
  }

  .multiselect__spinner {
    background-color: rgba(var(--colors-white), var(--tw-bg-opacity));
    color: rgba(var(--colors-gray-600), var(--tw-text-opacity));

    @media (prefers-color-scheme: dark) {
      background-color: rgba(var(--colors-gray-900), var(--tw-bg-opacity));
      color: rgba(var(--colors-gray-400), var(--tw-text-opacity));
    }

    &:before,
    &:after {
      border-color: rgba(var(--colors-primary-500)) transparent transparent;
    }
  }

  .multiselect__content-wrapper {
    border-color: rgba(var(--colors-gray-300), var(--tw-border-opacity));

    @media (prefers-color-scheme: dark) {
      border-color: rgba(var(--colors-gray-700), var(--tw-border-opacity));
    }

    li > span.multiselect__option {
      background-color: #fff;
      color: rgba(var(--colors-gray-400));

      @media (prefers-color-scheme: dark) {
        background-color: rgba(var(--colors-gray-900));
      }
    }

    .multiselect__element {
      background-color: rgba(var(--colors-white), var(--tw-bg-opacity));
      color: rgba(var(--colors-gray-600), var(--tw-text-opacity));

      @media (prefers-color-scheme: dark) {
        background-color: rgba(var(--colors-gray-900), var(--tw-bg-opacity));
        color: rgba(var(--colors-gray-400), var(--tw-text-opacity));
      }

      .multiselect__option {
        color: #fff;

        &.multiselect__option--selected {
          color: rgba(var(--colors-primary-400));
          background-color: #fff;
          text-decoration: underline;

          @media (prefers-color-scheme: dark) {
            background-color: rgba(var(--colors-gray-900));
          }
        }

        &.multiselect__option--highlight {
          background-color: rgba(var(--colors-primary-500));

          &.multiselect__option--selected {
            background-color: rgba(var(--colors-red-500));
            color: #fff;

            @media (prefers-color-scheme: dark) {
              background-color: rgba(var(--colors-red-500));
            }
          }
        }
      }
    }
  }

  .reorder__tag {
    background-color: rgba(var(--colors-primary-500));
    border-radius: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 5px;
    font-weight: 700;
    transition: all 0.2s ease-in-out;

    &:hover {
      cursor: pointer;
      opacity: 0.8;
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
