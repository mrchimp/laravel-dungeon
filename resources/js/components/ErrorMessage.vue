<template>
  <p class="text-red-700">
    <span>Error:</span>
    {{ message.text }}
  </p>
</template>

<script>
export default {
  props: {
    message: {
      type: Object,
      required: true,
    },
  },
};
</script>
