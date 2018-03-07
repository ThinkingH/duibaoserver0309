<template>
  <div class="app-protocol">
    <template v-for="i in 6">
      <img :src="'../../static/images/protocol'+i +'.jpg'" alt="">
    </template>
  </div>
</template>

<script>
  export default {
    name: "app-protocol"
  }
</script>

<style scoped>
  .app-protocol {
    width: 1000px;
    margin: auto;
  }

  .app-protocol > img {
    display: block;
    width: 100%;
  }
</style>
