<script setup>
defineProps({
  items: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <table class="w-full text-sm text-left text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <slot name="table-header"></slot>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(item, index) in items"
        :key="item.id"
        :class="{
          'border-b': index !== items.length - 1,
        }"
      >
        <slot name="table-data" :item="item" />
      </tr>
    </tbody>
  </table>
</template>
