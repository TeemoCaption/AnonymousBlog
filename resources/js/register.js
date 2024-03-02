import './bootstrap';
import {createApp} from 'vue' 

import app from './components/RegisterPage.vue'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
})

createApp(app).use(vuetify).mount('#register-app')