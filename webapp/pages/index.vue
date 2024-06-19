<template>
  <div class="card">
    <div>Личный кабинет</div>
    <el-button @click="logout">Выйти</el-button>
  </div>
</template>

<script>
export default {
  middleware: ['auth'],
  name: 'IndexPage',
  methods: {
    logout(){
      this.$store.dispatch('auth/logout').finally(() => {
        this.$router.push('/login');
      })
    }
  }
}
</script>

<style scoped>
.card{
  position: absolute;
  top: 40%; left: 50%;
  transform: translateX(-50%) translateY(-50%);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  text-align: center;
  text-transform: uppercase;
  font-family: sans-serif;
  gap: 12px;
}
</style>
