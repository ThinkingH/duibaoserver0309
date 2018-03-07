<template>
  <div class="app-foot">
    <div class="foot-inner">
      <div class="app-download">
        <div class="app-link-banner-title">用APP观看食谱视频吧</div>
        <div class="app-link-banner-description">60秒APP可以方便的收藏用户喜爱的视频食谱，每周还有APP限定食谱视频持续更新哦！</div>
        <div class="app-link-banner-btn">
          <a href=""><img src="../../static/images/ios.png" alt="APP苹果下载"></a>
          <a href=""><img src="../../static/images/android.png" alt="APP安卓下载"></a>
        </div>
        <img src="../../static/images/phone.png" alt="APP示例" class="banner-phone">
      </div>
    </div>
    <div class="foot-about">
      <p class="title">关于60秒（60SEC）</p>
      <p class="content">
        60秒是国内最新的创意美食类短视频网站。
        我们以“简单好看的美食”为主题，将传统食谱介绍与短视频完美融合，为您的餐桌增添更多种创意选择。
      </p>
      <p class="title">60秒的制作人</p>
      <p class="content">
        精彩美食视频的背后，是优秀制作团队通力协作的体现。
        60秒聘请了专业的营养师、厨师学校老师以及食品安全管理人员作为基础，精挑细选每道食谱，并邀请60秒专属厨师亲自下厨完成菜品制作，为内容质量提供了保障。
      </p>
      <p class="title">食谱视频的优势</p>
      <p class="content">
        60秒（60SEC）中的所有食谱视频均采用“让用户可以简单上手”的拍摄手法和剪辑方式。
        食材的切法、分量的多少、混合的程度以及烧制的方法都有明确体现。
        即使是没有做菜经验的人也可以看着视频制作出简单的美味。
        食谱视频还可以打破时间限制，无论你是在上班途中，又或是闲暇修饰时光，拿起手机打开60秒，随时随地感受美食带来的魅力。
      </p>
      <p class="title">60秒APP</p>
      <p class="content">
        每周更新最新的美味视频食谱，看到自己喜欢的食谱时可以一键收藏。
        登录用户还可以拍下自己根据食谱视频制作的成品照片并上传至APP中展示。
        用户还可以根据自己的喜好搜索关键词进行食谱的检索。
      </p>
    </div>
    <div class="foot-info">
      <div class="foot-info-content">
        <img src="../../static/images/logo.png" alt="logo" @click="gohome">
        <ul>
          <li>分类</li>
          <li v-for="listitem in list">
            <router-link :to="{name:'App-classifyinfo',query:{type:listitem.type,name:listitem.name}}">
              {{listitem.name}}
            </router-link>
          </li>
        </ul>
        <ul>
          <li>菜单</li>
          <li><a @click="gohome">首页</a></li>
          <li>
            <router-link :to="{name: 'App-speciallist'}">特辑列表</router-link>
          </li>
        </ul>
        <ul>
          <li>关于</li>
          <li><a href="">经营公司</a></li>
          <li>
            <router-link :to="{name: 'App-protocol'}">用户协议</router-link>
          </li>
        </ul>
      </div>
    </div>
    <div class="foot-copyrights-inner">
      <div class="foot-copyrights-desc">60秒（60SEC） 看着开心、做着开心的创意食谱视频服务</div>
      <div class="foot-copyrights-copyrights">版权所有©60秒（60SEC），Inc.保留所有权利。</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'app-foot',
    data() {
      return {
        list: [],
      }
    },
    created() {
      let self = this;
      /*-------------------- 获取主页信息 --------------------*/
      self.$http.get(self.app_config.basePath + '/foot', {
        params: {}
      }).then(function (response) {
        self.list = response.data.data.classify_info.list;
      }).catch(function (err) {
        console.log(err);
      });
    },
    methods: {
      gohome() {
        let self = this;
        if (self.$route.name === 'App-home') {
          document.documentElement.scrollTop = document.body.scrollTop = 0;
        } else {
          self.$router.push({name: 'App-home'});
        }
      }
    }
  }
</script>




<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .app-foot {
    width: 100%;
    min-width: 1000px;
    margin: 30px auto 70px;
  }

  .app-foot > .foot-inner {
    width: 100%;
    background-color: #f1f1f2;
    border-top: 1px solid #cfcfcf;
    border-bottom: 1px solid #cfcfcf;
  }

  .app-foot > .foot-inner > .app-download {
    position: relative;
    box-sizing: border-box;
    width: 1000px;
    margin: auto;
    padding: 40px 80px;
    height: 240px
  }

  .app-foot > .foot-inner > .app-download > .app-link-banner-title {
    font-size: 28px;
    color: #262626;
    letter-spacing: 1px;
    font-weight: 700;
  }

  .app-foot > .foot-inner > .app-download > .app-link-banner-description {
    width: 480px;
    margin-top: 20px;
    font-size: 16px;
    line-height: 1.5;
    letter-spacing: 2px;
    color: #5e5e5e;
  }

  .app-foot > .foot-inner > .app-download > .banner-phone {
    position: absolute;
    right: 80px;
    bottom: 0;
    display: block;
    width: 300px;
  }

  .app-foot > .foot-inner > .app-download > .app-link-banner-btn {
    position: absolute;
    left: 80px;
    bottom: 40px;
    height: 40px;
  }

  .app-foot > .foot-inner > .app-download > .app-link-banner-btn > a > img {
    width: 130px;
  }

  .app-foot > .foot-inner > .app-download > .app-link-banner-btn > a:last-child {
    margin-left: 30px;
  }

  .app-foot > .foot-about {
    width: 1000px;
    margin: auto;
    padding: 45px 0;
  }

  .app-foot > .foot-about > p {
    margin: auto;
  }

  .app-foot > .foot-about > p.title {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 10px;
  }

  .app-foot > .foot-about > p.content {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 25px;
  }

  .app-foot > .foot-about > p:last-child {
    margin-bottom: 0;
  }

  .app-foot > .foot-info {
    border-top: 1px solid #cfcfcf;
    border-bottom: 1px solid #cfcfcf;
  }

  .app-foot > .foot-info > .foot-info-content {
    box-sizing: border-box;
    padding: 40px;
    height: 280px;
    width: 1000px;
    margin: auto;
    overflow: hidden;
  }

  .app-foot > .foot-info > .foot-info-content > img {
    margin-left: -40px;
    margin-top: 75px;
    height: 40px;
    display: block;
    float: left;
    cursor: pointer;
  }

  .app-foot > .foot-info > .foot-info-content > ul {
    list-style: none;
    display: block;
    float: left;
    margin-left: 40px;
  }

  .app-foot > .foot-info > .foot-info-content > ul > li {
    margin-bottom: 15px;
  }

  .app-foot > .foot-info > .foot-info-content > ul > li:first-child {
    font-weight: 700;
    margin-bottom: 25px;
  }

  .app-foot > .foot-info > .foot-info-content > ul > li > a {
    text-decoration: none;
    color: #262626;
    cursor: pointer;
  }

  .app-foot > .foot-copyrights-inner {
    width: 1000px;
    margin: auto;
    font-size: 12px;
    line-height: 70px;
  }

  .app-foot > .foot-copyrights-inner .foot-copyrights-desc {
    float: left;
  }

  .app-foot > .foot-copyrights-inner .foot-copyrights-copyrights {
    float: right;
  }
</style>
