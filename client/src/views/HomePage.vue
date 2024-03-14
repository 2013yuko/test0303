<!-- HomePage.vue またはその他の名前で保存されているファイル -->
<template>
  <div>
    <h1>Home Page</h1>
    <!-- ContactForm コンポーネントをテンプレートに追加 -->
    <ContactForm />
  </div>
</template>

<script>
// ContactForm.vue をインポート
import ContactForm from '@/components/ContactForm.vue';

export default {
  name: 'HomePage',
  components: {
    // ContactForm コンポーネントを登録
    ContactForm
  }
};
</script>
