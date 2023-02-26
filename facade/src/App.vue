<template>
  <router-view />
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'App',
  created() { this.$store.dispatch("oauth/recoverCredentials");},
})
</script>
<style lang="scss">
.table-primary-field {
    color: teal;
    font-weight: 700;
    cursor: pointer;
}

.table-primary-field:hover {
    text-decoration: underline;
}
</style>