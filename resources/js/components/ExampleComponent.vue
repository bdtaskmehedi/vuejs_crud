<template>
  <div>
    <button @click="showToast">Show Toast</button>
  </div>
</template>

<script>
export default {
  methods: {
    showToast() {
      this.$toast.success("Hello from Vue Toastification!");
    },
  },
};
</script>
