<template>
  <div class="app-head">
    <div class="head-content">
      <router-link :to="{name:'App-home'}" class="logo">
        <img src="../../static/images/logo.png" alt="logo" title="60seconds">
      </router-link>
      <div class="subhead">美味视频（食谱视频）<br>你身边最好看的美食教程！</div>
      <div class="search">
        <img
          @click="search_func"
          src="../../static/images/search.png"
          alt="搜索">
        <input
          @keyup.enter="search_func"
          v-model="search_data"
          oninput="if(value.length>15)value=value.slice(0,15)"
          type="text"
          placeholder="菜名、食材等关键词搜索">
      </div>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'app-head',
    data() {
      return {
        search_data: this.$route.query.search_data ? this.$route.query.search_data : ''
      }
    },
    watch: {
      '$route': function (route) {
        this.search_data = this.$route.query.search_data ? this.$route.query.search_data : ''
      }
    },
    methods: {
      search_func(event) {
        let self = this;
        self.search_data = self.search_data.trim();
        let pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
        if (self.search_data != '') {
          if (pattern.test(self.search_data)) {
            alert("非法字符！");
            event.target.focus();
            return false;
          } else {
            self.$router.push({
              name: 'App-search', query: {
                search_data: self.search_data
              }
            });
          }
        } else {
          alert('输入不能为空');
        }
      }
    },
    created() {

    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .app-head {
    background-color: #fff;
    /*box-shadow: 0 0 4px #cfcfcf;*/
    border-bottom: 1px solid #cfcfcf;
    width: 100%;
    min-width: 1000px;
  }

  .app-head > .head-content {
    width: 1000px;
    height: 50px;
    margin: auto;
    padding: 16px 0;
  }

  .app-head > .head-content > .logo {
    float: left;
    display: block;
    color: #c6b363;
    text-decoration: none;
    cursor: pointer;
  }

  .app-head > .head-content > .logo > img {
    display: block;
    margin: 5px auto;
    width: 200px;
    height: 40px;
  }

  .app-head > .head-content > .subhead {
    float: left;
    margin-left: 10px;
    color: #5e5e5e;
    line-height: 25px;
  }

  .app-head > .head-content > .search {
    position: relative;
    float: right;
    background-color: #eee;
    height: 40px;
    width: 320px;
    margin: 5px auto;
    border-radius: 2px;
    color: #5e5e5e;
  }

  .app-head > .head-content > .search > img {
    position: absolute;
    top: 0;
    left: 0;
    box-sizing: border-box;
    width: 40px;
    height: 40px;
    padding: 12.5px;
    z-index: 1;
    cursor: pointer;
  }

  .app-head > .head-content > .search > input {
    display: block;
    box-sizing: border-box;
    border: 0;
    border-radius: 2px;
    position: absolute;
    top: 0;
    left: 0;
    height: 40px;
    width: 320px;
    background-color: transparent;
    padding: 0 16px 0 40px;
    outline: 0;
  }

</style>
