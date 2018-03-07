import Vue from 'vue';
import App from './App';
import router from './router';
import VueLazyload from 'vue-lazyload';//图片懒加载
import VueVideoPlayer from 'vue-video-player';
import defaultConfig from './defaultConfig';


require('video.js/dist/video-js.css');
require('vue-video-player/src/custom-theme.css');
require('video.js/dist/lang/zh-CN');

Vue.config.productionTip = false;

defaultConfig.install(Vue);


Vue.use(VueLazyload, {
  preLoad: 1.3,//预加载高度比例
  error: '../../static/images/404.png',//这个是请求失败后显示的图片
  loading: '../../static/images/loading.svg',//这个是加载的loading过渡效果
  try: 2, // 这个是加载图片数量
  attempt: 3,//尝试次数
});

Vue.use(VueVideoPlayer);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  render: h => h(App)
});
