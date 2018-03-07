<template>
  <div class="app-classifyinfo">
    <app-breadcrumb :breadcrumb_data="breadcrumb_data"></app-breadcrumb>
    <div class="middle-content">
      <div class="left">
        <p class="title">{{classifyinfo_data.name}}的食谱{{classifyinfo_data.number}}个</p>
        <p class="description">{{classifyinfo_data.content}}</p>
        <div class="content">
          <p class="title" v-if="classifyinfo_data.list.length!=0">“{{classifyinfo_data.name}}”详细分类</p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else>
            <ul>
              <li v-for="listitem in classifyinfo_data.list" @click="goto(listitem)">{{listitem.name}}</li>
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
  import app_breadcrumb from '@/components/App-breadcrumb'
  import app_special from '@/components/App-special'
  import app_videolist from '@/components/App-videolist'

  export default {
    name: 'app-classifyinfo',
    data() {
      return {
        isgetdata: true,
        breadcrumb_data: [],
        classifyinfo_data: {
          'name': '',
          'number': '',
          'content': '',
          'list': [],
        },
        videolist_data: {
          title: ' 的食谱视频一览',
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
      '$route': function (to, from) {
        if (to.query.type != from.query.type || to.query.name != from.query.name) {
          this.isgetdata = true;
          this.fetchData();
        } else {
          this.videolist_func();
        }
      }
    },
    methods: {
      goto(listItem) {
        let self = this;
        self.$router.push({
          name: 'App-classifyinfo',
          query: {
            type: listItem.type,
            name: listItem.name
          }
        });
      },
      videolist_func() {
        let self = this;
        self.$http.get(self.app_config.basePath + '/getvideolist', {
          params: {
            classify: self.$route.query.type ? self.$route.query.type : 'classify1',
            name: self.$route.query.name ? self.$route.query.name : '美食',
            page: self.$route.query.page ? self.$route.query.page : 1,
            pagesize: '9'
          }
        }).then(function (response) {
          self.videolist_data = response.data.data;
          self.videolist_data.title = self.$route.query.name + ' 的食谱视频一览';
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      },
      fetchData() {
        let self = this;

        self.$http.get(self.app_config.basePath + '/classifyinfo', {
          params: {
            type: self.$route.query.type ? self.$route.query.type : 'classify1',
            name: self.$route.query.name ? self.$route.query.name : '美食',
            page: self.$route.query.page ? self.$route.query.page : 1,
          }
        }).then(function (response) {
          self.isgetdata = false;

          self.breadcrumb_data = response.data.data.classify_info.classifys;
          self.breadcrumb_data.splice(0, 0, '首页');

          self.classifyinfo_data.name = response.data.data.classify_info.name;
          self.classifyinfo_data.number = response.data.data.classify_info.number;
          self.classifyinfo_data.content = response.data.data.classify_info.content;
          self.classifyinfo_data.list = response.data.data.classify_info.list;

          self.special_data = response.data.data.special_list;

          self.videolist_data = response.data.data.video_list;
          self.videolist_data.title = self.$route.query.name + ' 的食谱视频一览';
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      }
    },
    created() {
      // 组件创建完后获取数据，
      // 此时 data 已经被 observed 了
      this.fetchData();
    },
    components: {
      'app-breadcrumb': app_breadcrumb,
      'app-special': app_special,
      'app-videolist': app_videolist,
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .app-classifyinfo {
    width: 100%
  }

  .app-classifyinfo > .middle-content {
    width: 1000px;
    margin: auto;
    padding: 40px 0;
    overflow: hidden
  }

  .app-classifyinfo > .middle-content > .left {
    float: left;
    width: 689px;
    font-size: 16px;
    box-sizing: border-box
  }

  .app-classifyinfo > .middle-content > .left > .title {
    font-size: 26px;
    font-weight: 700;
    margin: 0 20px 0 0
  }

  .app-classifyinfo > .middle-content > .left > .description {
    margin: 16px 20px 40px 0;
    font-size: 14px;
    line-height: 1.5
  }

  .app-classifyinfo > .middle-content > .left > .content > .title {
    font-size: 20px;
    font-weight: 700;
    margin: 40px 20px 24px 0
  }

  .app-classifyinfo > .middle-content > .left > .content > ul {
    list-style: none;
    overflow: hidden;
    padding: 0;
    margin: 0
  }

  .app-classifyinfo > .middle-content > .left > .content > ul > li {
    float: left;
    width: 200px;
    height: 30px;
    line-height: 30px;
    border: 1px solid #cfcfcf;
    font-size: 14px;
    box-sizing: border-box;
    margin: 0 20px 20px 0;
    padding: 0 10px;
    color: #262626;
    cursor: pointer
  }

  .app-classifyinfo > .middle-content > .left > .content > ul > li:after {
    content: ">";
    float: right;
    color: #c6b363;
    font-size: 16px;
    font-weight: 700
  }

  .app-classifyinfo > .middle-content > .right {
    float: right;
    width: 311px
  }

</style>
