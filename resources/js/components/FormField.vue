<template>
  <default-field :errors="errors" :field="field">
    <template slot="field">
      <div class="flex flex-col">
        <input
          :class="errorClasses"
          :placeholder="field.name"
          :type="field.inputType || 'text'"
          class="w-full form-control form-input form-input-bordered mb-2"
          ref="input"
          v-model="inputValue"
        />
        <!-- Multi select field -->
        <multiselect
          :class="errorClasses"
          :clear-on-select="false"
          :close-on-select="field.max === 1 || !isMultiselect"
          :deselectGroupLabel="__('novaMultiselect.deselectGroupLabel')"
          :deselectLabel="__('novaMultiselect.deselectLabel')"
          :limitText="count => __('novaMultiselect.limitText', { count })"
          :max="field.max || null"
          :multiple="isMultiselect"
          :options="options"
          :optionsLimit="field.optionsLimit || 1000"
          :placeholder="field.placeholder || field.name"
          :selectGroupLabel="__('novaMultiselect.selectGroupLabel')"
          :selectLabel="__('novaMultiselect.selectLabel')"
          :selectedLabel="__('novaMultiselect.selectedLabel')"
          :value="selected"
          @input="handleChange"
          @open="() => repositionDropdown(true)"
          label="label"
          ref="multiselect"
          track-by="value"
          v-if="!reorderMode"
        >

          <template slot="maxElements">
            {{ __('novaMultiselect.maxElements', { max: field.max }) }}
          </template>

          <template slot="noResult">
            {{ __('novaMultiselect.noResult') }}
          </template>

          <template slot="noOptions">
            {{ __('novaMultiselect.noOptions') }}
          </template>
        </multiselect>

        <!-- Reorder mode field -->
        <div class="form-input-bordered py-1" v-if="reorderMode">
          <vue-draggable class="flex flex-col pl-0" style="list-style: none; margin-top: 5px;" tag="ul" v-model="value">
            <transition-group>
              <li :key="s" class="reorder__tag text-sm mb-1 px-2 py-1 text-white" v-for="s in selected">
                {{ s.label }}
              </li>
            </transition-group>
          </vue-draggable>
        </div>

        <div
          @click="reorderMode = !reorderMode"
          class="ml-auto mt-2 text-sm font-bold text-primary cursor-pointer dim"
          v-if="field.reorderable"
        >
          {{ __(reorderMode ? 'novaMultiselect.doneReordering' : 'novaMultiselect.reorder') }}
        </div>

        <div class="input-group-append mt-4 text-center">
          <button
            @click="addOption"
            class="btn btn-default btn-primary inline-flex items-center relative"
            type="button"
          >Add {{field.name}}
          </button>
        </div>

      </div>
    </template>
  </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova';
    import HandlesFieldValue from '../mixins/HandlesFieldValue';
    import Multiselect from 'vue-multiselect';
    import VueDraggable from 'vuedraggable';

    export default {
        components: {Multiselect, VueDraggable},

        mixins: [FormField, HandlesValidationErrors, HandlesFieldValue],

        props: ['resourceName', 'resourceId', 'field'],

        data: () => ({
            reorderMode: false,
            items: []
        }),

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
                return this.field.options || this.items || [];
            }
        },

        methods: {
            addOption() {
                if (this.inputValue.length <= 3) return;

                let data = {
                    label: this.inputValue,
                    value: this.inputValue
                };

                if (this.field.options) this.field.options.push(data);
                else this.items.push(data);

                this.value.push(data);

                this.inputValue = '';
            },

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
                    let value;
                    if (this.value && this.value.length) {
                        value = JSON.stringify(this.value.map(v => v.value));
                    } else {
                        value = this.field.nullable ? '' : JSON.stringify([]);
                    }

                    formData.append(this.field.attribute, value);
                } else {
                    formData.append(this.field.attribute, (this.value && this.value.value) || '');
                }
            },

            handleChange(value) {
                this.value = value;
                this.$nextTick(() => this.repositionDropdown());
            },

            repositionDropdown(onOpen = false) {
                const ms = this.$refs.multiselect;
                if (!ms) return;

                const el = ms.$el;

                const handlePositioning = () => {
                    const {top, height, bottom} = el.getBoundingClientRect();
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
            }
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
