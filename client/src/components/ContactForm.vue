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

    const submitForm = () => {
      axios.post('/api/contact', form.value)
          .then(() => {
            // 成功時の処理
            ElNotification({
              title: '成功',
              message: 'お問い合わせありがとうございます。',
              type: 'success',
            });
            Object.keys(form.value).forEach(key => {
              form.value[key] = '';
            });
          })
          .catch(error => {
            if (error.response && error.response.status === 422 && error.response.data.errors) {
              const fieldLabels = {
                name: '名前',
                furigana: 'ふりがな',
                email: 'メールアドレス',
                tel: '電話番号',
                message: 'お問い合わせ内容'
              };
              const messages = Object.entries(error.response.data.errors)
                  .map(([field, _]) => `${fieldLabels[field] || field}を入力してください。`) // eslint-disable-line no-unused-vars
                  .join('<br>');
              ElNotification({
                title: '必須項目が未入力です。',
                message: messages,
                dangerouslyUseHTMLString: true,
                type: 'error',
                customClass: 'custom-notification'
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
    return { form, submitForm };
  }
};
</script>

<style>
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

  .custom-notification {
    width: 350px;
  }
</style>
