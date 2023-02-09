<template>
  <div v-if="isLoggedIn">
    <p>Hello {{getUser.firstName}},</p>
    <button @click="logout" v-if="isLoggedIn">Log out</button>
  </div>
  <div>
    <router-view></router-view>
  </div>
  <div class="app-errors">
    <div class="error" v-for="(error, index) in this.getErrors" :key="index">
      <strong>Error:</strong> {{error}}
    </div>
  </div>
</template>
<script>
import errorMixin from "@/components/Mixins/errorMixin";
import store from "@/store";
import {provide} from "vue";
export default {
  mixins: [errorMixin],
  setup() {
    provide( 'store', store)
  },
  methods: {
    logout() {
      const user = store.methods.getUser()
      if (!user) {
        return;
      }
      document.cookie = 'vacation_user_email=; Max-Age=0; path=/; domain=' + location.hostname;
      localStorage.removeItem('user')
      store.state.user = null
      this.$router.push({ path: '/' });
    }
  },
  computed: {
    isLoggedIn () {
      return store.methods.isLoggedIn()
    },
    getUser () {
      return store.methods.getUser()
    }
  }
}
</script>
<style scoped>
header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style>
