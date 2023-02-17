import { createApp } from 'vue'
import router from './routes'
import App from './App.vue'
import VCalendar from 'v-calendar'

import './assets/main.css'
const app = createApp(App)

app.use(router)
app.use(VCalendar, {})

app.mount('#app')
