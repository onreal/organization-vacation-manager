<template>
  <user-form v-if="isLoaded" :is-edit="true" :user="user"></user-form>
</template>

<script>
import UserForm from "@/components/User/UserForm.vue";
import store from "@/store";
import { useRoute } from 'vue-router';
import {onMounted} from "vue";

export default {
  name: "User",
  components: {UserForm},
  data() {
    return {
      userId: null,
      user: null,
      isLoaded: false
    }
  },
  methods: {
    initUser() {
      fetch(import.meta.env.VITE_API_URL + 'api/users/' + this.userId, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      })
          .then((response) => {
            if (response.status >= 400) {
              throw response.json()
            }
            return response.json()
          })
          .then((payload) => {
            this.user = payload
            this.isLoaded = true
          })
          .catch((error) => {
            Promise.resolve(error).then((data) => this.setError(data.message))
          })
    }
  },
  created() {
    console.log('yes')
    const route = useRoute()
    this.userId = route.params.userId
    this.initUser()
  }
}
</script>

<style scoped>

</style>
