<template>
  <div class="app-special">
    <p class="title">特辑</p>
    <ul class="special-list">
      <li v-for="listitem in special_data">
        <router-link :to="{name:'App-specialinfo',query:{id:listitem.id}}">
          <div class="img">
            <img v-lazy="listitem.img_url">
          </div>
          <div class="title">{{listitem.title}}</div>
          <div class="description">{{listitem.description}}</div>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    name: 'app-special',
    props: ['special_data', 'isgetdata'],
    data() {
      return {}
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .app-special {
    width: 100%;
    box-sizing: border-box;
    padding-left: 20px;
  }

  .app-special > .title {
    font-size: 20px;
    font-weight: 700;
    margin: auto;
    padding-bottom: 8px;
    border-bottom: 1px solid #c6b363
  }

  .app-special > .special-list {
    width: 100%;
    list-style: none;
    margin: auto;
    padding: 0;
    overflow: hidden
  }

  .app-special > .special-list > li {
    width: 100%;
    margin-top: 25px;
    float: left
  }

  .app-special > .special-list > li > a {
    width: 100%;
    display: block;
    text-decoration: none
  }

  .app-special > .special-list > li > a:hover {
    opacity: .8;
    filter: alpha(opacity=80)
  }

  .app-special > .special-list > li > a > div.img {
    position: relative;
    width: 100%;
    height: 170px;
    background-color: #b2b2b2;
  }

  .app-special > .special-list > li > a > div.img > img {
    display: block;
    width: 100%;
    height: 100%;
  }

  .app-special > .special-list > li > a > .title {
    width: 100%;
    margin: 10px 0 6px;
    font-size: 16px;
    display: block;
    color: #262626;
    font-weight: 700;
    line-height: 1.5
  }

  .app-special > .special-list > li > a > .description {
    width: 100%;
    color: #5e5e5e;
    font-size: 12px;
    margin: 6px 0 10px;
    line-height: 1.5
  }
</style>
