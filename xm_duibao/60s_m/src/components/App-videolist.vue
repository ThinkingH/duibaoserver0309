<template>
  <div class="app-videolist">
    <p id="videolist" class="title">{{videolist_data.title}}</p>
    <template v-if="isgetdata">
      <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
    </template>
    <template v-else>
      <ul class="video-list" v-if="videolist_data.list.length!=0">
        <li v-for="listitem in videolist_data.list">
          <router-link :to="{name:'App-product',query:{id:listitem.id}}">
            <div class="img">
              <img v-lazy="listitem.img_url" class="bg">
              <img src="../../static/images/play.png" class="play">
            </div>
            <div class="title">{{listitem.title}}</div>
            <div class="description">{{listitem.description}}</div>
            <div class="ingredients">{{listitem.ingredients}}</div>
          </router-link>
        </li>
      </ul>
      <p v-else>没有匹配的菜谱。</p>
      <app-paging :pagemsg="videolist_data.pagemsg"></app-paging>
    </template>

  </div>
</template>

<script>
  import app_paging from '@/components/App-paging'

  export default {
    name: 'app-videolist',
    props: ['videolist_data', 'isgetdata'],
    data() {
      return {}
    },
    watch: {},
    components: {
      'app-paging': app_paging,
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .app-videolist {
    width: 100%;
  }

  .app-videolist > .title {
    font-size: 20px;
    font-weight: 700;
    margin: 0 20px 0 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #c6b363;
  }

  .app-videolist > .video-list {
    width: 100%;
    list-style: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }

  .app-videolist > .video-list > li {
    margin-top: 25px;
    width: 33.33%;
    padding-right: 20px;
    box-sizing: border-box;
    float: left;
  }

  .app-videolist > .video-list > li > a {
    position: relative;
    width: 100%;
    display: block;
    text-decoration: none;
  }

  .app-videolist > .video-list > li > a:hover {
    opacity: .8;
    filter: alpha(opacity=80);
  }

  .app-videolist > .video-list > li > a > div.img {
    width: 210px;
    height: 210px;
    position: relative;
    background-color: #b2b2b2;
  }

  .app-videolist > .video-list > li > a > div.img > img.bg {
    display: block;
    width: 210px;
    height: 210px;
  }

  .app-videolist > .video-list > li > a > div.img > img.play {
    position: absolute;
    top: 0;
    left: 0;
    display: none;
    width: 100%;
    padding: 35%;
    box-sizing: border-box;
  }

  .app-videolist > .video-list > li > a > div.img > img.bg[lazy="loaded"] + img.play {
    display: block;
  }

  .app-videolist > .video-list > li > a > .title {
    width: 100%;
    margin: 10px 0 6px;
    font-size: 16px;
    display: block;
    color: #262626;
    font-weight: 700;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .app-videolist > .video-list > li > a > .description {
    width: 100%;
    height: 14px;
    color: #5e5e5e;
    font-size: 13px;
    margin: 6px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .app-videolist > .video-list > li > a > .ingredients {
    width: 100%;
    height: 13px;
    color: #8e8e8e;
    font-size: 11px;
    margin: 6px 0 10px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

</style>
