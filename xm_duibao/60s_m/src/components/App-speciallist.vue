<template>
  <div class="app-speciallist">
    <div class="middle-content">
      <p class="title" id="videolist">特辑列表</p>
      <template v-if="isgetdata">
        <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
      </template>
      <template v-else>
        <ul>
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
      </template>
      <app-paging :pagemsg="pagemsg"></app-paging>
    </div>
  </div>
</template>

<script>
  import app_paging from '@/components/App-paging'

  export default {
    name: 'app-speciallist',
    data() {
      return {
        isgetdata: true,
        special_data: [],
        pagemsg: {
          nowpage: 0,
          sumpage: 0,
        }
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
        self.$http.get(self.app_config.basePath + '/speciallist', {
          params: {
            page: self.$route.query.page ? self.$route.query.page : 1,
          }
        }).then(function (response) {
          self.isgetdata = false;
          self.special_data = response.data.data.list;
          self.pagemsg.nowpage = parseInt(response.data.data.pagemsg.nowpage);
          self.pagemsg.sumpage = parseInt(response.data.data.pagemsg.sumpage);
        }).catch(function (err) {
          console.log(err);
        });
      }
    },
    mounted() {
      let self = this;
      self.videolist_func();
    },
    components: {
      'app-paging': app_paging,
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .app-speciallist {
    width: 100%;
  }

  .app-speciallist > .middle-content {
    width: 1000px;
    margin: auto;
    padding: 40px 0;
    overflow: hidden;
  }

  .app-speciallist > .middle-content > .title {
    margin: 0;
    font-size: 26px;
    padding-bottom: 10px;
    font-weight: 700;
    border-bottom: 1px solid #c6b363;
  }

  .app-speciallist > .middle-content > ul {
    width: 100%;
    list-style: none;
    margin: 0 -20px 0 0;
    padding: 0;
    overflow: hidden;
  }

  .app-speciallist > .middle-content > ul > li {
    width: 320px;
    float: left;
    margin: 20px 20px 0 0;
  }

  .app-speciallist > .middle-content > ul > li:nth-child(3n) {
    margin-right: 0;
  }

  .app-speciallist > .middle-content > ul > li > a {
    text-decoration: none;
    display: block;
    width: 100%;
  }

  .app-speciallist > .middle-content > ul > li > a:hover {
    opacity: .8;
    filter: alpha(opacity=80);
  }

  .app-speciallist > .middle-content > ul > li > a > .img {
    position: relative;
    width: 100%;
    height: 180px;
  }

  .app-speciallist > .middle-content > ul > li > a > .img > img {
    width: 100%;
    height: 180px;
  }

  .app-speciallist > .middle-content > ul > li > a > .title {
    margin: 10px 0;
    font-size: 16px;
    line-height: 1.3;
    font-weight: 700;
    color: #262626;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .app-speciallist > .middle-content > ul > li > a > .description {
    height: 14px;
    color: #5e5e5e;
    font-size: 13px;
    margin: 8px 0;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>
