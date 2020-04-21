<template>
  <p class="text-gray-600">{{ message.text }}</p>
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
