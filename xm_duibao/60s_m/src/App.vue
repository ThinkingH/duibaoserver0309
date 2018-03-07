<template>
  <div id="app">
    <app-head></app-head>
    <router-view></router-view>
    <app-foot></app-foot>
  </div>
</template>

<script>
  import app_head from '@/components/App-head'
  import app_foot from '@/components/App-foot'

  export default {
    name: 'app',
    components: {
      'app-head': app_head,
      'app-foot': app_foot
    },
    mounted () {
      const script = document.createElement('script')
      script.src = 'https://s95.cnzz.com/z_stat.php?id=1111111111&web_id=1111111111'
      script.language = 'JavaScript'
      document.body.appendChild(script)
    },
    watch: {
      '$route' () {
        if (window._czc) {
          let location = window.location
          let contentUrl = location.pathname + location.hash
          let refererUrl = '/'
          window._czc.push(['_trackPageview', contentUrl, refererUrl])
        }
      }
    }
  }
</script>

<style>
  @import '/static/normalize.css';

  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #262626;
    width: 100%;
    min-width: 1040px;
    margin: auto;
    font-size: 14px;
    font-weight: 400;
  }

  /*-------------------- 去vue闪屏 --------------------*/
  [v-cloak] {
    display: none;
  }

  /*-------------------- 左右布局css --------------------*/
  .middle-content {
    width: 1000px;
    padding: 40px 0;
    margin: auto;
    overflow: hidden;
  }

  .middle-content > .left {
    float: left;
    width: 689px;
  }

  .middle-content > .right {
    box-sizing: border-box;
    border-left: 1px solid #cfcfcf;
    float: right;
    width: 311px;
  }

  /*-------------------- 图片css --------------------*/
  div.loadingdiv {
    height: 50px;
    width: 130px;
    overflow: hidden;
    margin: 20px auto;
  }

  div.loadingdiv > img.loading {
    height: 90px;
    width: 545px;
    margin: -16px 0 0 -200px;
    display: block;
  }

  /*-------------------- 图片css --------------------*/
  @-webkit-keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  img[lazy=loaded] {
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
  }

  img[lazy=loading] {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 40px !important;
    height: 40px !important;
    margin: -20px 0 0 -20px;
  }

  img[lazy=error] {
    border-radius: 2px;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
  }

  /*-------------------- 视频css --------------------*/
  .vjs-custom-skin * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    outline: none
  }

  .video-player.vjs-custom-skin > .video-js > .vjs-big-play-button {
    font-size: 3em;
    border-radius: 50%;
    height: 3em !important;
    line-height: 3em !important;
    margin-top: -1.5em !important;
  }

  .vjs-custom-skin > .video-js .vjs-big-play-button > span {
    font-size: 1.5em;
  }
</style>
