<template>
<Card>
  <template>
    <div class="title">Подтверждение</div>
<p>На вашу почту отправлено письмо с кодом</p>
    <el-form ref="codeForm" :model="form" :rules="rules">
      <el-form-item label="Код из сообщения" prop="username">
        <el-input v-model="form.code"></el-input>
      </el-form-item>
      <el-button @click="confirm">
        Продолжить
      </el-button>
    </el-form>
  </template>
</Card>
</template>

<script>
export default {
  name: "code",
  data(){
    return {
      form: {
        code: null,
      },
      rules: {
        code: [
          { required: true, message: 'Введите имя пользователя', trigger: 'blur' },
        ],
      }
    };
  },
  methods: {
    confirm(){
      console.log('confirm sms code');
      this.$router.push('/register/complete');
    }
  }
}
</script>

<style scoped>
.title{
  font-weight: bold;
  font-size: 20px;
  font-family: sans-serif;
  text-align: center;
  text-transform: uppercase;
}
</style>
