<template>
  <el-form @submit.prevent="submitForm">
    <el-form-item label="名前">
      <el-input
          v-model="form.name"
          placeholder="山田奈緒子"
      ></el-input>
    </el-form-item>
    <el-form-item label="ふりがな">
      <el-input
          v-model="form.furigana"
          placeholder="やまだなおこ"
      ></el-input>
    </el-form-item>
    <el-form-item label="メールアドレス">
      <el-input
          v-model="form.email"
          placeholder="trick@mail.com"
      ></el-input>
    </el-form-item>
    <el-form-item label="電話番号">
      <el-input
          v-model="form.tel"
          placeholder="888-8888-8888"
      ></el-input>
    </el-form-item>
    <el-form-item label="お問い合わせ内容">
      <el-input
          type="textarea"
          v-model="form.message"
          placeholder="お問い合わせ内容を入力してください。"
      ></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" native-type="submit" :disabled="isSubmitting">送信</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { reactive, ref } from 'vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';

export default {
  setup() {
    const form = reactive({
      name: '',
      furigana: '',
      email: '',
      tel: '',
      message: '',
      errors: {
        name: false,
        furigana: false,
        email: false,
        tel: false,
        message: false,
      }
    });
    const isSubmitting = ref(false);

    const validateEmail = (email) => {
      const errors = [];
      const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (!email) {
        errors.push("メールアドレスを入力してください。");
        form.errors.email = true;
      } else if (!emailRegex.test(email)) {
        errors.push("メールアドレスの形式が正しくありません。");
        form.errors.email = true;
      }
      return errors;
    };
    const validateTel = (tel) => {
      const errors = [];
      const telRegex = /^[0-9０-９-ー]+$/;
      if (!tel) {
        errors.push("電話番号を入力してください。");
        form.errors.tel = true;
      } else if (!telRegex.test(tel)) {
        errors.push("電話番号に数字とハイフン(-)以外の文字が含まれています。")
        form.errors.tel = true;
      }
      return errors;
    };

    const submitForm = async () => {
      if (isSubmitting.value) return;
      isSubmitting.value = true;
      let errors = [];
      errors = errors.concat(validateEmail(form.email), validateTel(form.tel));

      // バリデーション処理...// 必須フィールドの検証
      if (!form.name) errors.push("名前を入力してください。");
      if (!form.furigana) errors.push("ふりがなを入力してください。");
      if (!form.message) errors.push("お問い合わせ内容を入力してください。");

      if (errors.length > 0) {
        ElNotification({
          title: '必須項目の入力に誤りがあります',
          message: errors.join('<br>'),
          dangerouslyUseHTMLString: true,
          type: 'error',
          customClass: 'custom-notification',
          duration: 0,
        });
        isSubmitting.value = false;
        return;
      }

      try {
        const response = await axios.post('/api/contact', {
          name: form.name,
          furigana: form.furigana,
          email: form.email,
          tel: form.tel,
          message: form.message,
        });
        console.log(response);
        ElNotification({
          title: '成功',
          message: 'お問い合わせありがとうございます。',
          type: 'success',
          duration: 0,
        });
        Object.keys(form).forEach(key => {
          if (typeof form[key] === 'object' && key !== 'errors') {
            Object.keys(form[key]).forEach(subKey => {
              form[key][subKey] = '';
            });
          } else if (key !== 'errors') {
            form[key] = '';
          }
        });
      } catch (error) {
        console.error("送信エラー:", error);
        if (error.response) {
          // サーバーからのレスポンスがある場合、その詳細を表示
          console.error("エラー詳細:", error.response.data);
        }
        ElNotification({
          title: 'エラー',
          message: '送信に失敗しました。もう一度お試しください。',
          type: 'error',
          duration: 0,
        });
      } finally {
        isSubmitting.value = false;
      }
    };

    return { form, submitForm, isSubmitting };
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
    width: 370px;
  }
</style>
