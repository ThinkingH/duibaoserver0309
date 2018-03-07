<template>
  <div class="app-specialinfo">
    <app-breadcrumb :breadcrumb_data="breadcrumb_data"></app-breadcrumb>
    <div class="middle-content">
      <div class="left">
        <div class="img-big">
          <img v-lazy="specialinfo_data.img_url">
        </div>
        <p class="title" id="videolist">{{specialinfo_data.title}}</p>
        <p class="description">{{specialinfo_data.description}}</p>
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
        </template>
        <app-paging :pagemsg="videolist_data.pagemsg"></app-paging>
      </div>
      <div class="right">
        <app-special :special_data="special_data" :isgetdata="isgetdata"></app-special>
      </div>
    </div>
  </div>
</template>

<script>
  import app_breadcrumb from '@/components/App-breadcrumb'
  import app_special from '@/components/App-special'
  import app_paging from '@/components/App-paging'

  export default {
    name: 'app-specialinfo',
    data() {
      return {
        isgetdata: true,
        breadcrumb_data: ['首页', '特辑列表'],
        specialinfo_data: this.defaultData.special_info,
        videolist_data: {
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
        this.special_func();
      }
    },
    methods: {
      videolist_func() {
        let self = this;
        self.$http.get(self.app_config.basePath + '/getvideolist', {
          params: {
            type: 2,
            pagesize: 12,
            name: self.$route.query.id ? self.$route.query.id : '1',
            page: self.$route.query.page ? self.$route.query.page : 1,
          }
        }).then(function (response) {
          self.isgetdata = false;
          self.videolist_data = response.data.data;
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      },
      special_func() {
        let self = this;
        /*-------------------- 获取特辑信息 --------------------*/
        self.$http.get(self.app_config.basePath + '/specialinfo', {
          params: {
            id: self.$route.query.id ? self.$route.query.id : 1,
            page: self.$route.query.page ? self.$route.query.page : 1
          }
        }).then(function (response) {
          self.isgetdata = false;
          self.special_data = response.data.data.special_list;
          self.breadcrumb_data = ['首页', '特辑列表'];
          self.breadcrumb_data.push(response.data.data.special_info.title);
          self.specialinfo_data = response.data.data.special_info;
          self.videolist_data = response.data.data.video_list;
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });

      },
    },
    created() {
      let self = this;
      self.special_func();
    },
    components: {
      'app-breadcrumb': app_breadcrumb,
      'app-special': app_special,
      'app-paging': app_paging,
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .app-specialinfo {
    width: 100%;
  }

  .app-specialinfo > .middle-content > .left > .img-big {
    position: relative;
    height: 376px;
    margin-right: 20px;
    background-color: #b2b2b2;
    box-sizing: border-box;
  }

  .app-specialinfo > .middle-content > .left > .img-big > img {
    width: 100%;
    height: 376px;
    display: block;
  }

  .app-specialinfo > .middle-content > .left > .title {
    margin: 40px 20px 12px 0;
    padding: 0 0 16px;
    font-size: 26px;
    font-weight: 700;
  }

  .app-specialinfo > .middle-content > .left > .description {
    padding-bottom: 24px;
    margin: 0 20px 12px 0;
    font-size: 14px;
    line-height: 1.5;
    border-bottom: 1px solid #c6b363;
  }

  .app-specialinfo > .middle-content > .left > ul {
    width: 100%;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow: hidden;
  }

  .app-specialinfo > .middle-content > .left > ul > li {
    width: 50%;
    float: left;
    margin: 20px 0;
    padding: 0 20px 0 0;
    box-sizing: border-box;
  }

  .app-specialinfo > .middle-content > .left > ul > li > a {
    position: relative;
    width: 100%;
    display: block;
    text-decoration: none;
  }

  .app-specialinfo > .middle-content > .left > ul > li > a:hover {
    opacity: .8;
    filter: alpha(opacity=80);
  }

  .app-specialinfo > .middle-content > .left > ul > li > a > .img {
    width: 324.5px;
    height: 324.5px;
  }

  .app-specialinfo > .middle-content > .left > ul > li > a > .img > img.bg {
    display: block;
    width: 100%;
    height: 324.5px;
  }

  .app-specialinfo > .middle-content > .left > ul > li > a > .img > img.play {
    position: absolute;
    top: 0;
    left: 0;
    display: block;
    width: 100%;
    padding: 40%;
    box-sizing: border-box;
  }

  .app-specialinfo > .middle-content > .left > ul > li > a > .title {
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

  .app-specialinfo > .middle-content > .left > ul > li > a > .description {
    width: 100%;
    height: 14px;
    color: #5e5e5e;
    font-size: 13px;
    margin: 6px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .app-specialinfo > .middle-content > .left > ul > li > a > .ingredients {
    width: 100%;
    height: 13px;
    color: #8e8e8e;
    font-size: 11px;
    margin: 6px 0 10px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .app-specialinfo > .middle-content > .left > .paginate {
    margin-top: 30px;
    width: 100%;
    text-align: center;
  }

  .app-specialinfo > .middle-content > .left > .paginate > a,
  .app-specialinfo > .middle-content > .left > .paginate > span,
  .app-specialinfo > .middle-content > .left > .paginate > strong {
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 40px;
    border: 1px solid #eee;
    border-bottom: 2px solid #eee;
    font-size: 16px;
    color: #262626;
    text-decoration: none;
    margin: auto 5px;
    cursor: default;
  }

  .app-specialinfo > .middle-content > .left > .paginate > a {
    cursor: pointer;
  }

  .app-specialinfo > .middle-content > .left > .paginate > strong {
    background-color: #c6b363;
    color: #fff;
  }

  .app-specialinfo > .middle-content > .left > .paginate > span {
    border: 1px solid transparent;
    border-bottom: 2px solid transparent;
  }
</style>
