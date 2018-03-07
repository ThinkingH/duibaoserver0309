<template>
  <div class="app-video">
    <video-player
      class="vjs-custom-skin"
      ref="videoPlayer"
      :options="playerOptions"
      :playsinline="true">
    </video-player>
  </div>
</template>

<script>
  export default {
    name: 'app-video',
    props: ['video_data'],
    data() {
      return {
        playerOptions: {}
      }
    },
    watch: {
      'video_data'() {
        this.playerOptions = {
          muted: false,
          language: 'zh-CN',
          playbackRates: [0.7, 1.0, 1.5, 2.0],
          sources: [{
            type: "video/mp4",
            src: this.video_data.video_url,
          }],
          poster: this.video_data.img_url,
        }
      }
    },
    methods: {
      player() {
        return this.$refs.videoPlayer.player
      }
    }
  }
</script>

<style>

  .app-video {
    position: relative;
    width: 100%;
    height: 100%;
    background-color: #ddd;

  }

  .app-video > .vjs-custom-skin {
    width: 100% !important;
    height: 100% !important;
  }

  .app-video > .vjs-custom-skin > div {
    width: 100% !important;
    height: 100% !important;
  }
</style>
