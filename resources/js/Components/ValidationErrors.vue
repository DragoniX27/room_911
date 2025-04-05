<template>
    <div
      v-if="hasErrors"
      class="bg-red-100 text-red-800 text-sm font-bold p-4 rounded-xl border border-red-300 text-left"
      role="alert"
    >
      <ul>
        <li
          v-for="(error, key) in errors"
          :key="key"
          class="list-none"
          v-html="error"
        ></li>
      </ul>
    </div>
  </template>

  <script>
  import { defineComponent } from 'vue'

  export default defineComponent({
    computed: {
      errors() {
        return this.$page.props.errors
      },
      hasErrors() {
        return Object.keys(this.errors).length > 0
      }
    }
  })
  </script>
