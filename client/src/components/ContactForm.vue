<template>
  <el-form @submit.prevent="submitForm">
    <el-form-item label="名前">
      <el-input v-model="form.name" placeholder="お名前"></el-input>
    </el-form-item>
    <el-form-item label="ふりがな">
      <el-input v-model="form.furigana" placeholder="ふりがな"></el-input>
    </el-form-item>
    <el-form-item label="メールアドレス">
      <el-input v-model="form.email" placeholder="メールアドレス"></el-input>
    </el-form-item>
    <el-form-item label="電話番号">
      <el-input v-model="form.tel" placeholder="電話番号"></el-input>
    </el-form-item>
    <el-form-item label="お問い合わせ内容">
      <el-input
          type="textarea"
          v-model="form.message"
          placeholder="お問い合わせ内容"
      ></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" native-type="submit">送信</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';

export default {
  setup() {
    // `ref` を使用してリアクティブなデータを作成
    const form = ref({
      name: '',
      furigana: '',
      email: '',
      tel: '',
      message: ''
    });
    const errors = ref({});

    // submitForm関数では、`this` を使用せずに `form.value` に直接アクセス
    const submitForm = () => {
      axios.post('/api/contact', form.value)
          .then(() => {
            // 成功時の処理
            ElNotification({
              title: '成功',
              message: 'お問い合わせありがとうございます。',
              type: 'success',
            });
            form.value = { name: '', furigana: '', email: '', tel: '', message: '' };
          })
          .catch(error => {
            // 422 Unprocessable Entityの場合、バリデーションエラーの詳細を表示
            if (error.response && error.response.status === 422) {
              const messages = Object.values(error.response.data.errors).flat().join('\n');
              ElNotification({
                title: 'エラー',
                message: messages, // バリデーションエラーの詳細を表示
                type: 'error',
              });
            } else {
              // その他のエラーの場合、一般的なエラーメッセージを表示
              ElNotification({
                title: 'エラー',
                message: '送信に失敗しました。もう一度お試しください。',
                type: 'error',
              });
            }
          });
    };

    // setup関数から返す値
    return { form, errors, submitForm };
  }
};
</script>

<style scoped>
  .el-form {
    max-width: 600px;
    margin: auto;
  }

  .el-form-item {
    margin-bottom: 20px;
  }

  .el-input,
  .el-input[type="textarea"] {
    width: 100%;
  }
</style>
