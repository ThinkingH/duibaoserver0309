<template>
  <div class="app-popup">
    <div class="popup-content">
      <div class="app-download">
        <div class="app-link-banner-title">用APP观看食谱视频吧</div>
        <div class="app-link-banner-description">60秒APP可以方便的收藏用户喜爱的视频食谱，每周还有APP限定食谱视频持续更新哦！</div>
        <div class="app-link-banner-btn">
          <a href=""><img src="../../../static/images/ios.png" alt="APP苹果下载"></a>
          <a href=""><img src="../../../static/images/android.png" alt="APP安卓下载"></a>
        </div>
        <img data-v-725d310a="" src="../../../static/images/phone.png" alt="APP示例" class="banner-phone">
      </div>
      <img @click="colse"
           src="data:image/png;base64,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"
           alt="关闭弹出层" class="colse">
    </div>
  </div>
</template>

<script>
  export default {
    name: 'app-popup',
    props: ['show_popup'],
    data() {
      return {}
    },
    methods: {
      colse() {
        this.$emit('c_show_popup', false);
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .app-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .7);
    z-index: 99999;
  }

  .app-popup > .popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    box-sizing: border-box;
    width: 840px;
    height: 240px;
    padding: 40px;
    margin: -120px 0 0 -420px;
    background-color: #f1f1f2;
    border-radius: 2px;
  }

  .app-popup > .popup-content > .colse {
    cursor: pointer;
    position: absolute;
    top: 15px;
    right: 15px;
  }

  .app-popup > .popup-content > .app-download > .app-link-banner-title {
    font-size: 28px;
    color: #262626;
    letter-spacing: 1px;
    font-weight: 700;
  }

  .app-popup > .popup-content > .app-download > .app-link-banner-description {
    width: 450px;
    margin-top: 20px;
    font-size: 16px;
    line-height: 1.5;
    letter-spacing: 2px;
    color: #5e5e5e;
  }

  .app-popup > .popup-content > .app-download > .banner-phone {
    position: absolute;
    right: 40px;
    bottom: 0;
    display: block;
    width: 300px;
  }

  .app-popup > .popup-content > .app-download > .app-link-banner-btn {
    position: absolute;
    left: 40px;
    bottom: 40px;
    height: 40px;
  }

  .app-popup > .popup-content > .app-download > .app-link-banner-btn > a > img {
    display: inline-block;
    width: 130px;
  }

  .app-popup > .popup-content > .app-download > .app-link-banner-btn > a[data-v-725d310a]:last-child {
    margin-left: 30px;
  }
</style>
