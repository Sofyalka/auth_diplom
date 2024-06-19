<template>
<div class="card">
  <slot></slot>
</div>
</template>

<script>
export default {
  name: "Card"
}
</script>

<style scoped>
.card{
  position: absolute;
  top: 40%; left: 50%;
  transform: translateX(-50%) translateY(-50%);
  width: 400px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
</style>
