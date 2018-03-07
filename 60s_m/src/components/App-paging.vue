<template>
  <div class="app-paging" v-if="pagemsg.sumpage>1" @click="skip">
    <template v-if="pagemsg.sumpage<=5">
      <template v-for="page in pagemsg.sumpage">
        <template v-if="page==pagemsg.nowpage">
          <strong>{{page}}</strong>
        </template>
        <template v-else>
          <a>{{page}}</a>
        </template>
      </template>
    </template>
    <template v-else-if="pagemsg.nowpage<5">
      <template v-for="page in 5">
        <template v-if="page==pagemsg.nowpage">
          <strong>{{page}}</strong>
        </template>
        <template v-else>
          <a>{{page}}</a>
        </template>
      </template>
      <span>...</span>
      <a>{{pagemsg.sumpage}}</a>
    </template>
    <template v-else-if="pagemsg.nowpage>pagemsg.sumpage-4">
      <a>1</a>
      <span>...</span>
      <template v-for="page in 5">
        <template v-if="(pagemsg.sumpage-5+page)==pagemsg.nowpage">
          <strong>{{(pagemsg.sumpage - 5 + page)}}</strong>
        </template>
        <template v-else>
          <a>{{(pagemsg.sumpage - 5 + page)}}</a>
        </template>
      </template>
    </template>
    <template v-else>
      <a>1</a>
      <span>...</span>
      <a>{{pagemsg.nowpage - 2}}</a>
      <a>{{pagemsg.nowpage - 1}}</a>
      <strong>{{pagemsg.nowpage}}</strong>
      <a>{{pagemsg.nowpage + 1}}</a>
      <a>{{pagemsg.nowpage + 2}}</a>
      <span>...</span>
      <a>{{pagemsg.sumpage}}</a>
    </template>
  </div>
</template>

<script>
  export default {
    name: 'app-paging',
    props: ['pagemsg'],
    data() {
      return {}
    },
    methods: {
      skip(event) {
        let self = this;
        if (event.target.nodeName === 'A') {
          let push_data = {
            name: self.$route.name,
            query: {
              page: parseInt(event.target.innerText),
            },
            hash: '#videolist'
          };
          if (self.$route.query.id) {
            push_data.query.id = self.$route.query.id;
          }
          if (self.$route.query.name) {
            push_data.query.name = self.$route.query.name;
          }
          if (self.$route.query.type) {
            push_data.query.type = self.$route.query.type;
          }
          if (self.$route.query.search_data) {
            push_data.query.search_data = self.$route.query.search_data;
          }
          self.$router.push(push_data);
        }
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .app-paging {
    margin-top: 30px;
    width: 100%;
    text-align: center;
  }

  .app-paging > a,
  .app-paging > span,
  .app-paging > strong {
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

  .app-paging > a {
    cursor: pointer;
  }

  .app-paging > strong {
    background-color: #c6b363;
    color: #fff;
  }

  .app-paging > span {
    border: 1px solid transparent;
    border-bottom: 2px solid transparent;
  }
</style>
