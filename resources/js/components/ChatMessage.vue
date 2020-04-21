<template>
  <p class="font-bold">
    <span v-if="message.name">{{ message.name }}:</span>
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
