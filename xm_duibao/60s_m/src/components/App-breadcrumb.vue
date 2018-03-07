<template>
  <div class="app-breadcrumb">
    <ul>
      <li v-for=" (Item,Index) in breadcrumb_data">
        <template v-if="Index==0">
          <router-link :to="{name:'App-home'}">{{Item}}</router-link>
        </template>
        <template v-else-if="Index==breadcrumb_data.length-1">
          <span>{{Item}}</span>
        </template>
        <template v-else-if="Item=='特辑列表'">
          <span :data-id="Index" @click="gotospeciallist">{{Item}}</span>
        </template>
        <template v-else>
          <span :data-id="Index" @click="gotoclassifyinfo">{{Item}}</span>
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    name: 'app-breadcrumb',
    props: ['breadcrumb_data'],
    data() {
      return {}
    },
    methods: {
      gotospeciallist: function () {
        let self = this;
        self.$router.push({
          name: 'App-speciallist'
        });
      },
      gotoclassifyinfo: function (event) {
        let self = this,
          n = event.target.getAttribute('data-id');
        self.$router.push({
          name: 'App-classifyinfo',
          query: {
            name: event.target.innerHTML,
            type: 'classify' + n
          }
        });
      }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .app-breadcrumb {
    width: 100%;
    height: 45px;
    /*box-shadow: 0 3px 3px -3px #cfcfcf;*/
    border-bottom: 1px solid #cfcfcf;
  }

  .app-breadcrumb > ul {
    display: block;
    width: 1000px;
    margin: auto;
    padding: 0;
    line-height: 45px;
    list-style: none;
    overflow: hidden;
  }

  .app-breadcrumb > ul > li {
    float: left;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
  }

  .app-breadcrumb > ul > li:after {
    content: ">";
    font-size: 14px;
    font-weight: 700;
    color: #cfcfcf;
    margin: 0 5px;
  }

  .app-breadcrumb > ul > li:last-child:after {
    content: "";
    margin: 0;
  }

  .app-breadcrumb > ul > li > a,
  .app-breadcrumb > ul > li > span {
    text-decoration: none;
    color: #5e5e5e;
    cursor: pointer;
  }

  .app-breadcrumb > ul > li:last-child > span {
    font-weight: 700;
    cursor: default;
  }
</style>
