<template>
  <div class="app-product">
    <app-breadcrumb :breadcrumb_data="cate_data.breadcrumb_info"></app-breadcrumb>
    <div class="middle-content">
      <div class="left">
        <div class="video-content">
          <app-video :video_data="cate_data.video_info"></app-video>
        </div>
        <template v-if="isgetdata">
          <div class="loadingdiv" style="margin: 40px auto">
            <img src="../../static/images/loading.gif" alt="loading" class="loading">
          </div>
        </template>
        <template v-else>
          <p class="title">{{cate_data.title}}</p>
          <p class="meta-introduction">{{cate_data.subhead}}</p>
          <p class="meta-description">{{cate_data.description}}</p>
          <p class="necessary-time">烹饪时间：{{cate_data.time_needed}}</p>
          <!--<p class="expense">预计费用：{{cate_data.estimated_amount}}</p>-->
        </template>
        <div @click="change_show_popup(true)" class="btn favorite">
          <img src="../../static/images/collection.png" alt="收藏">
          <span>收藏</span>
        </div>
        <div class="line"></div>
        <div class="materials">
          <p class="title">材料 <span>（{{cate_data.people}}）</span></p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else>
            <ul>
              <li v-for="listitem in cate_data.materials">
                <span>{{listitem.name}}</span>
                <span>{{listitem.dosage}}</span>
              </li>
            </ul>
          </template>
        </div>
        <div class="step">
          <p class="title">步骤</p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else>
            <ul>
              <li v-for="listitem in cate_data.step">
                <span>{{listitem.order}}</span>.
                <span>{{listitem.content}}</span>
              </li>
            </ul>
          </template>
        </div>
        <div class="prompt_information">
          <p class="title">提示说明</p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else>
            <div>{{cate_data.prompt_informaton}}</div>
          </template>
        </div>
        <div class="submission">
          <p class="title">成品投稿</p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else-if="contribute_list.list.length==0">
            <p style="font-size:15px;">暂无投稿</p>
          </template>
          <template v-else>
            <ul>
              <li v-for="listitem in contribute_list.list">
                <div class="img">
                  <img v-lazy="listitem.img_url" alt="投稿图片">
                </div>
                <div class="user-info">
                  <div class="img-min">
                    <img v-lazy="listitem.portrait" alt="头像">
                  </div>
                  <span class="name">{{listitem.name}}</span>
                  <span class="time">{{listitem.create_date}}</span>
                </div>
                <div class="content">{{listitem.content}}</div>
              </li>
            </ul>
            <a @click="change_show_popup(true)"
               v-if="contribute_list.allcount> contribute_list.list.length">
              查看更多（共{{contribute_list.allcount}}个）
            </a>
          </template>
          <div @click="change_show_popup(true)" class="btn favorite middle">
            <img src="../../static/images/camera.png" alt="投稿">
            <span>成品投稿</span>
          </div>
        </div>


        <div class="comment">
          <p class="title">评论</p>
          <template v-if="isgetdata">
            <div class="loadingdiv"><img src="../../static/images/loading.gif" alt="loading" class="loading"></div>
          </template>
          <template v-else-if="comment_list.list.length==0">
            <p style="font-size:15px;">暂无评论</p>
          </template>
          <template v-else>

            <ul>
              <template v-for="listitem in comment_list.list">
                <li>
                  <div class="img-min">
                    <img v-lazy="listitem.portrait" alt="头像">
                  </div>
                  <div class="right">
                    <span class="name">{{listitem.name}}</span>
                    <div class="content">{{listitem.content}}</div>
                    <span class="time">{{listitem.create_date}}</span>
                  </div>
                </li>

                  <template v-for="listitemss in listitem.back">
                    <li>
                      <div class="img-min">
                        <img v-lazy="listitemss.touxiang" alt="头像">
                      </div>
                      <div class="right">
                        <span class="name">{{listitemss.nickname}}</span>
                        <div class="content">{{listitemss.content}}</div>
                        <span class="time">{{listitemss.create_date}}</span>
                      </div>
                    </li>
                  </template>

              </template>
            </ul>


          </template>
          <div @click="change_show_popup(true)" class="btn favorite middle">
            <img src="../../static/images/message.png" alt="评论">
            <span>发表评论</span>
          </div>
        </div>
      </div>


      <div class="right">
        <app-special :special_data="special_data"></app-special>
      </div>
      <app-popup v-show="show_popup" :show_popup="show_popup" @c_show_popup="change_show_popup"></app-popup>
    </div>
  </div>


</template>

<script>
  import app_breadcrumb from '@/components/App-breadcrumb'
  import app_video from '@/components/App-video'
  import app_special from '@/components/App-special'
  import app_popup from '@/components/popup/App-popup'

  export default {
    name: 'app-product',
    data() {
      return {
        isgetdata: true,
        show_popup: false,
        breadcrumb_data: [],
        cate_data: this.defaultData.cate_data,
        contribute_list: this.defaultData.contribute_list,
        comment_list: this.defaultData.comment_list,
        comment_list2: this.defaultData.comment_list.list,
        special_data: this.defaultData.special,
      }
    },
    watch: {},
    methods: {
      change_show_popup(data) {
        this.show_popup = data;
      }
    },
    created() {
      let self = this;

      /*-------------------- 获取美食信息 --------------------*/
      self.$http.get(self.app_config.basePath + '/product', {
        params: {
          id: self.$route.query.id ? self.$route.query.id : 1
        }
      }).then(function (response) {
        self.isgetdata = false;
        self.cate_data = response.data.data.cate_info;
        self.special_data = response.data.data.special_list;
        self.contribute_list = response.data.data.contribute_list;
        self.comment_list = response.data.data.comment_list;
        self.comment_list2 = response.data.data.comment_list.list;
      }).catch(function (err) {
        console.log(err);
      });
    },
    components: {
      'app-video': app_video,
      'app-breadcrumb': app_breadcrumb,
      'app-special': app_special,
      'app-popup': app_popup
    },


  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .app-product {
    width: 100%;
  }

  .app-product > .middle-content > .left > .video-content {
    height: 560px;
    width: 560px;
  }

  .app-product > .middle-content > .left > .title {
    font-size: 26px;
    font-weight: 700;
    margin: 40px 20px 8px 0;
  }

  .app-product > .middle-content > .left > .meta-introduction {
    padding-top: 8px;
    margin: 0 20px 8px 0;
    font-size: 14px;
    color: #5e5e5e;
  }

  .app-product > .middle-content > .left > .meta-description {
    padding-top: 8px;
    line-height: 1.5;
    margin-right: 20px;
  }

  .app-product > .middle-content > .left > .necessary-time {
    margin: 16px 0 16px;
    line-height: 1.5;
  }

  /*.app-product > .middle-content > .left > .expense {*/
  /*margin: 4px 0 16px;*/
  /*line-height: 1.5;*/
  /*}*/

  .app-product > .middle-content > .left > .comment,
  .app-product > .middle-content > .left > .materials,
  .app-product > .middle-content > .left > .prompt_information,
  .app-product > .middle-content > .left > .step,
  .app-product > .middle-content > .left > .submission {
    margin-bottom: 40px;
    margin-right: 20px;
  }

  .app-product > .middle-content > .left > .comment > p.title,
  .app-product > .middle-content > .left > .materials > p.title,
  .app-product > .middle-content > .left > .prompt_information > p.title,
  .app-product > .middle-content > .left > .step > p.title,
  .app-product > .middle-content > .left > .submission > p.title {
    font-size: 20px;
    margin: 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #cfcfcf;
    font-weight: 700;
  }

  .app-product > .middle-content > .left > .materials > p.title > span,
  .app-product > .middle-content > .left > .step > p.title > span {
    font-weight: 400;
    font-size: 12px;
  }

  .app-product > .middle-content > .left > .prompt_information > div {
    padding-top: 25px;
    font-size: 15px;
  }

  .app-product > .middle-content > .left > .submission > ul {
    list-style: none;
    margin: 0 -20px 0 0;
    padding: 0;
    overflow: hidden;
    display: table-cell;
    min-width: 669px;
  }

  .app-product > .middle-content > .left > .comment > ul {
    list-style: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }

  .app-product > .middle-content > .left > .submission > ul > li {
    float: left;
    width: 33.33%;
    padding-right: 20px;
    margin-top: 25px;
    box-sizing: border-box;
  }

  .app-product > .middle-content > .left > .comment > ul > li {
    overflow: hidden;
    border-bottom: 1px solid #cfcfcf;
  }

  .app-product > .middle-content > .left > .submission > ul > li > .img {
    position: relative;
    background-color: #b2b2b2;
    width: 210px;
    height: 210px;
  }

  .app-product > .middle-content > .left > .submission > ul > li > .img > img {
    width: 210px;
    height: 210px;
  }

  .app-product > .middle-content > .left > .comment > ul > li > .img-min {
    float: left;
    height: 50px;
    width: 50px;
    margin: 25px 20px 25px 0;
    position: relative;
  }

  .app-product > .middle-content > .left > .comment > ul > li > .img-min > img {
    display: block;
    height: 50px;
    width: 50px;
    border-radius: 50%;
  }

  .app-product > .middle-content > .left > .comment > ul > li > div.right {
    float: left;
    width: 599px;
    padding: 25px 0;
  }

  .app-product > .middle-content > .left > .comment > ul > li > div > span {
    font-size: 14px;
  }

  .app-product > .middle-content > .left > .comment > ul > li > div > span.name {
    color: #262626;
    font-weight: 700;
  }

  .app-product > .middle-content > .left > .comment > ul > li > div > div.content {
    font-size: 14px;
    line-height: 1.6;
    margin: 8px 0;
    width: 100%;
    word-wrap: break-word;
  }

  .app-product > .middle-content > .left > .comment > ul > li > div > span.time {
    color: #5e5e5e
  }

  .app-product > .middle-content > .left > .submission > ul > li > div.user-info {
    overflow: hidden;
    margin: 10px 0;
  }

  .app-product > .middle-content > .left > .submission > ul > li > div.user-info > .img-min {
    position: relative;
    float: left;
    display: block;
    height: 50px;
    width: 50px;
  }

  .app-product > .middle-content > .left > .submission > ul > li > div.user-info > .img-min > img {
    display: block;
    height: 50px;
    width: 50px;
    border-radius: 50%;
  }

  .app-product > .middle-content > .left > .submission > ul > li > div.user-info > span {
    display: block;
    height: 25px;
    line-height: 25px;
    padding-left: 60px;
    font-size: 14px;
    color: #5e5e5e;
  }

  .app-product > .middle-content > .left > .submission > ul > li > div.user-info > span.name {
    font-weight: 700;
    color: #262626;
  }

  .app-product > .middle-content > .left > .submission > ul > li > .content {
    width: 100%;
    word-wrap: break-word;
    margin-top: 10px;
    font-size: 14px;
    line-height: 1.6;
  }

  .app-product > .middle-content > .left > .submission > a {
    text-align: center;
    display: block;
    margin-top: 40px;
    font-size: 18px;
    color: #c6b363;
    text-decoration: none;
    cursor: pointer;
  }

  .app-product > .middle-content > .left > .materials > ul,
  .app-product > .middle-content > .left > .step > ul {
    margin: auto;
    padding: 10px 0 0;
    list-style: none;
    font-size: 15px;
  }

  .app-product > .middle-content > .left > .materials > ul > li,
  .app-product > .middle-content > .left > .step > ul > li {
    height: 35px;
    line-height: 35px;
    display: block;
    width: 100%;
    overflow: hidden;
    border-bottom: 1px solid #f1f1f2;
  }

  .app-product > .middle-content > .left > .materials > ul > li > span:first-child {
    float: left;
  }

  .app-product > .middle-content > .left > .materials > ul > li > span:last-child {
    float: right;
  }

  .app-product > .middle-content > .left > .line {
    height: 1px;
    background-color: #cfcfcf;
    margin: 40px 20px 40px 0;
  }

  .app-product > .middle-content > .left .btn {
    margin-left: 2px;
    width: 300px;
    height: 40px;
    border-radius: 2px;
    border: 1px solid #bfae68;
    box-shadow: inset 0 0 3px #bfae68, 0 0 3px #bfae68;
    font-weight: 700;
    color: #bfae68;
    text-align: center;
    cursor: pointer;
  }

  .app-product > .middle-content > .left .btn.middle {
    margin: 40px auto;
  }

  .app-product > .middle-content > .left .btn > img {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin: 10px 0;
    vertical-align: middle;
  }

  .app-product > .middle-content > .left .btn > span {
    display: inline-block;
    margin-left: 5px;
    line-height: 40px;
    vertical-align: middle;
  }

</style>
