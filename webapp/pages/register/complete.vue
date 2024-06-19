<template>
  <Card>
    <template>
      <div class="title">Завершение</div>
      <p>Придумайте пароль</p>
      <el-form ref="completeForm" :model="form" :rules="rules">
        <el-form-item label="Пароль" prop="password">
          <el-input v-model="form.password" type="password"></el-input>
        </el-form-item>
        <el-form-item label="Подтверждение" prop="confirm">
          <el-input v-model="form.confirm" type="password"></el-input>
        </el-form-item>
        <el-button @click="complete">
          Продолжить
        </el-button>
      </el-form>
    </template>
  </Card>
</template>

<script>
export default {
  name: "complete",
  data(){
    return {
      form: {
        password: null,
        confirm: null,
      },
      rules: {
        password: [
          { required: true, message: 'Введите пароль', trigger: 'blur' },
        ],
        confirm: [
          { required: true, message: 'Повторите пароль', trigger: 'change' }
        ],
      }
    };
  },
  methods:{
    complete(){
      console.log('complete');
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
