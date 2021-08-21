//require('bootstrap');
require('datatables.net-dt')
require('datatables.net-rowgroup-dt')

import { createInertiaApp } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import i18n from '@/assets/i18n'

import AppLayout from '@/layouts/AppLayout'

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '../css/app.scss'

import JQuery from 'jquery'
window.$ = JQuery

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.withCredentials = true

window.bootstrap = require('bootstrap')

window.capitalize = function (value) {
  if (!value) {
    return ''
  } else {
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
  }
}

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use(require('vue-moment'));

const appName =
  window.document.getElementsByTagName('title')[0]?.innerText || 'Symfony'

createInertiaApp({
  //resolve: (name) => require(`./Pages/${name}`),
  resolve: (name) => {
    const module = require(`./Pages/${name}`)

    if (name != 'Auth/Login' && !module.default.layout) {
      module.default.layout = AppLayout
    }

    return module.default
  },
  setup({ el, app, props }) {
    new Vue({
      render: (h) => h(app, props),
      i18n,
      methods: {
        switchLocale(locale, fallbackLocale) {
          i18n.locale = locale
          i18n.fallbackLocale = fallbackLocale
        },
      },
    }).$mount(el)
  },
})
