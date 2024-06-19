<template>
  <Card>
    <template>
      <div class="title">Вход</div>
      <el-form ref="loginForm" :model="form" :rules="rules">
        <el-form-item label="Логин" prop="username">
          <el-input v-model="form.username" placeholder="Имя пользователя"></el-input>
        </el-form-item>
        <el-form-item label="Пароль" prop="password">
          <el-input v-model="form.password" type="password" placeholder="Ваш пароль"></el-input>
        </el-form-item>
        <el-button @click="login">
          Войти
        </el-button>
      </el-form>
      <div class="links">
        <nuxt-link to="/register">Регистрация</nuxt-link>
        <nuxt-link to="/forgot">Забыли пароль?</nuxt-link>
      </div>
    </template>
  </Card>
</template>

<script>
export default {
  name: "login.vue",
  data(){
    return {
      form: {
        username: null,
        password: null,
      },
      rules: {
        username: [
          { required: true, message: 'Введите имя пользователя', trigger: 'blur' },
        ],
        password: [
          { required: true, message: 'Введите пароль', trigger: 'blur' },
          { min: 8, max: 12, message: 'Пароль должен содержать от 8 до 12 символов', trigger: 'blur' },
        ],
      }
    };
  },
  methods: {
    login(){
      this.$refs.loginForm.validate((valid) => {
        console.log(valid);
        if(valid){
          this.$store.dispatch('auth/login', {...this.form}).then(() => {
            this.$router.push('/');
          })
        }
      })
    }
  }
};
</script>

<style scoped>
.title{
  font-weight: bold;
  font-size: 20px;
  font-family: sans-serif;
  text-align: center;
  text-transform: uppercase;
}
.links{
  font-family: sans-serif;
  display: flex;
  justify-content: space-between;
}
.links a{
  text-decoration: none;
}
</style>
<style>
.el-button{
  width: 100%;
}
</style>
