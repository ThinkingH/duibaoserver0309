import Vue from 'vue'
import Router from 'vue-router'
import App_home from '@/components/App-home'
import App_product from '@/components/App-product'
import App_search from '@/components/App-search'
import App_specialinfo from '@/components/App-specialinfo'
import App_speciallist from '@/components/App-speciallist'
import App_classifyinfo from '@/components/App-classifyinfo'
import App_protocol from '@/components/App-protocol'

Vue.use(Router)

export default new Router({
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else if (to.hash) {
      return {selector: to.hash}
    } else {
      return {x: 0, y: 0}
    }
  },
  routes: [
    {
      path: '/',
      name: 'App-home',
      component: App_home
    },
    {
      path: '/product',
      name: 'App-product',
      component: App_product
    },
    {
      path: '/search',
      name: 'App-search',
      component: App_search
    },
    {
      path: '/specialinfo',
      name: 'App-specialinfo',
      component: App_specialinfo
    },
    {
      path: '/speciallist',
      name: 'App-speciallist',
      component: App_speciallist
    },
    {
      path: '/classifyinfo',
      name: 'App-classifyinfo',
      component: App_classifyinfo
    },
    {
      path: '/protocol',
      name: 'App-protocol',
      component: App_protocol
    },
  ]
})
