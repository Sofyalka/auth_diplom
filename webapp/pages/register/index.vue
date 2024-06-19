<template>
  <Card>
    <template>
      <div class="title">Регистрация</div>
      <el-form ref="regForm" :model="form" :rules="rules">
        <el-form-item label="Логин" prop="username">
          <el-input v-model="form.username"></el-input>
        </el-form-item>
        <el-form-item label="Почта" prop="email">
          <el-input v-model="form.email"></el-input>
        </el-form-item>
        <el-button @click="prepare">
          Продолжить
        </el-button>
      </el-form>
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
        email: null,
      },
      rules: {
        username: [
          { required: true, message: 'Введите имя пользователя', trigger: 'blur' },
        ],
        email: [
          { required: true, message: 'Введите почту', trigger: 'change' }
        ],
      }
    };
  },
  methods: {
    prepare(){
      this.$refs.regForm.validate((valid) => {
        if(valid){
          this.$store.dispatch('auth/register', {...this.form}).then(() =>{
            this.$router.push('/register/code');
          })
        }
      })
    }
  }
};
</script>

<style scoped>
.register{
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
.title{
  font-weight: bold;
  font-size: 20px;
  font-family: sans-serif;
  text-align: center;
  text-transform: uppercase;
}
</style>
<style>
.el-button{
  width: 100%;
}
</style>
