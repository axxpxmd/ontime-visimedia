import Vue from 'vue'
import Router from 'vue-router'
import NotFound from './views/notfound.vue'
import Maps from './views/maps/index.vue'


Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/s/404',
      name: 'not-found',
      component: NotFound
    },
    {
      path: '/s/users/maps',
      name: 'users_maps',
      component: Maps
    },

    {
      path: '*',
      redirect: { name: 'not-found' }
    }
  ]
})
