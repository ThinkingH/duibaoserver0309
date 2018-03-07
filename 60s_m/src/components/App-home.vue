<template>

  <div class="app-home">
    <div class="video-content">
      <div class="inner">
        <!--<app-video :video_data="video_data"></app-video>-->
        <ul>
          <li v-for="listitem in video_data">
            <router-link :to="{name:'App-product',query:{id:listitem.id}}">
              <div class="img">
                <img v-lazy="listitem.img_url" class="bg">
                <img src="../../static/images/play.png" class="play">
              </div>
              <div class="title">{{listitem.title}}</div>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
    <div class="middle-content">
      <div class="left">
        <div class="classify">
          <p class="title">全部分类</p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else>
            <ul class="classify-list">
              <li v-for="listitem in classify_data">
                <router-link :to="{name:'App-classifyinfo',query:{type:listitem.type,name:listitem.name}}">
                  {{listitem.name}}（{{listitem.number}}）
                </router-link>
              </li>
            </ul>
          </template>
        </div>
        <app-videolist :videolist_data="videolist_data" :isgetdata="isgetdata"></app-videolist>
      </div>
      <div class="right">
        <app-special :special_data="special_data" :isgetdata="isgetdata"></app-special>
      </div>
    </div>
  </div>
</template>

<script>
  import app_video from '@/components/App-video'
  import app_special from '@/components/App-special'
  import app_videolist from '@/components/App-videolist'

  export default {
    name: 'App-home',
    data() {
      return {
        isgetdata: true,
        video_data: this.defaultData.home_video,
        classify_data: [],
        videolist_data: {
          title: '最新食谱',
          pagemsg: {
            nowpage: 0,
            sumpage: 0
          },
          list: []
        },
        special_data: this.defaultData.special,
      }
    },
    watch: {
      '$route': function (route) {
        this.videolist_func();
      }
    },
    methods: {
      videolist_func() {
        let self = this;
        self.$http.get(self.app_config.basePath + '/getvideolist', {
          params: {
            classify: 'classify1',
            name: '美食',
            page: self.$route.query.page ? self.$route.query.page : 1,
          }
        }).then(function (response) {
          self.videolist_data = response.data.data;
          self.videolist_data.title = '最新食谱';
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      }
    },
    created() {
      let self = this;
      /*-------------------- 获取主页信息 --------------------*/
      self.$http.get(self.app_config.basePath + '/home', {
        params: {
          page: self.$route.query.page ? self.$route.query.page : 1
        }
      }).then(function (response) {
        self.isgetdata = false;
        self.video_data = response.data.data.home_video;
        self.special_data = response.data.data.special_list;
        self.classify_data = response.data.data.classify_info;
        self.videolist_data = response.data.data.video_list;
        self.videolist_data.title = '最新食谱';
        self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
        self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
      }).catch(function (err) {
        console.log(err);
      });
    },
    components: {
      'app-video': app_video,
      'app-special': app_special,
      'app-videolist': app_videolist,
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .app-home {
    position: relative;
    width: 100%;
    min-width: 1000px;
  }

  .app-home > .video-content {
    background-color: #f1f1f2;
    padding: 40px 0;
    width: 100%;
    border-bottom: 1px solid #cfcfcf;
  }

  .app-home > .video-content > .inner {
    width: 1000px;
    margin: auto;
  }

  .app-home > .video-content > .inner > ul {
    list-style: none;
    padding: 0;
    display: block;
    margin: 0 auto;
    overflow: hidden;
  }

  .app-home > .video-content > .inner > ul > li {
    float: left;
    width: 33.333%;
    position: relative;
    box-sizing: border-box;
  }

  .app-home > .video-content > .inner > ul > li:nth-child(1) {
    padding-right: 13.333px;
  }

  .app-home > .video-content > .inner > ul > li:nth-child(2) {
    padding: 0 6.666px;
  }

  .app-home > .video-content > .inner > ul > li:nth-child(3) {
    padding-left: 13.333px;
  }

  .app-home > .video-content > .inner > ul > li > a > div.img {
    position: relative;
    width: 320.016px;
    height: 320.016px;
  }

  .app-home > .video-content > .inner > ul > li > a > div.img > img.bg {
    display: block;
    width: 100%;
  }

  .app-home > .video-content > .inner > ul > li > a > div.img > img.play {
    position: absolute;
    top: 0;
    left: 0;
    display: block;
    width: 100%;
    padding: 35%;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
  }

  .app-home > .video-content > .inner > ul > li > a > div.title {
    padding: 0 16px 16px;
    background-image: linear-gradient(to bottom, rgba(238, 238, 238, 0), rgba(0, 0, 0, 0.5));
    color: #FFF;
    font-size: 20px;
    line-height: 24px;
  }

  .app-home > .video-content > .inner > ul > li:nth-child(1) > a > div.title {
    position: absolute;
    right: 13.333px;
    bottom: 0;
    left: 0;
  }

  .app-home > .video-content > .inner > ul > li:nth-child(2) > a > div.title {
    position: absolute;
    right: 6.666px;
    bottom: 0;
    left: 6.666px;
  }

  .app-home > .video-content > .inner > ul > li:nth-child(3) > a > div.title {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 13.333px;
  }

  .classify {
    width: 100%;
    margin-bottom: 25px;
  }

  .classify > .title {
    font-size: 20px;
    font-weight: 700;
    margin: 0 20px 25px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #c6b363;
  }

  .classify > ul.classify-list {
    margin: 0;
    padding: 0 0 10px;
    list-style: none;
    overflow: hidden;
  }

  .classify > .classify-list > li {
    float: left;
    padding: 0 15px;
    font-size: 14px;
    border-left: 1px solid #eee;
    border-right: 1px solid #eee;
  }

  .classify > .classify-list > li:first-child {
    padding-left: 0;
    border-left: 0;
  }

  .classify > .classify-list > li:last-child {
    padding-right: 0;
    border-right: 0;
  }

  .classify > .classify-list > li > a {
    text-decoration: none;
    color: #262626;
  }

</style>
