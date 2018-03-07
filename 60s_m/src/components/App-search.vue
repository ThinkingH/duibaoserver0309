<template>
  <div class="app-search">
    <app-breadcrumb :breadcrumb_data="breadcrumb_data"></app-breadcrumb>
    <div class="middle-content">
      <div class="left">
        <template v-if="isgetdata">
          <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
        </template>
        <template v-else>
          <app-videolist :videolist_data="videolist_data"></app-videolist>
        </template>
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
    name: 'app-search',
    data() {
      return {
        isgetdata: true,
        breadcrumb_data: ['首页', '搜索结果'],
        videolist_data: {
          title: '搜索結果',
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
      '$route'(to, from) {
        if (to.query.search_data == from.query.search_data) {
          this.videolist_func();
        } else {
          this.search_func();
        }
      }
    },
    methods: {
      videolist_func() {
        let self = this;
        self.$http.get(self.app_config.basePath + '/getvideolist', {
          params: {
            type: 1,
            pagesize: 12,
            name: self.$route.query.search_data ? self.$route.query.search_data : '',
            page: self.$route.query.page ? self.$route.query.page : 1,
          }
        }).then(function (response) {
          self.isgetdata = false;
          self.videolist_data = response.data.data;
          self.videolist_data.title = '“ ' + self.$route.query.search_data + ' ”  搜索結果';
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      },
      search_func() {
        let self = this;
        self.$http.get(self.app_config.basePath + '/search', {
          params: {
            pagesize: 12,
            search_data: self.$route.query.search_data ? self.$route.query.search_data : '',
            page: self.$route.query.page ? self.$route.query.page : 1,
          }
        }).then(function (response) {
          self.isgetdata = false;
          self.special_data = response.data.data.special_list;
          self.videolist_data = response.data.data.video_list;
          self.videolist_data.title = '“ ' + self.$route.query.search_data + ' ”  搜索結果';
          self.videolist_data.pagemsg.nowpage = parseInt(self.videolist_data.pagemsg.nowpage);
          self.videolist_data.pagemsg.sumpage = parseInt(self.videolist_data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      }
    },
    created() {
      let self = this;
      self.search_func();
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
  .app-search {
    width: 100%;
  }
</style>
