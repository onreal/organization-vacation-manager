<template>
<div v-if="success">
  <p>
    Application with ID <strong>#{{applicationId}}</strong> and status <strong>{{status}}</strong>, is updated successfully!
  </p>
  <p>Employee will be notified by email about the decision.</p>
</div>
</template>

<script>
import { useRoute } from 'vue-router'
import store from "@/store";
import errorMixin from "@/components/Mixins/errorMixin";

export default {
  name: "ApplicationStatus",
  data() {
    return {
      userId: null,
      status: null,
      applicationId: null,
      success: false
    }
  },
  mixins:[errorMixin],
  methods: {
    changeApplicationStatus () {
      fetch(import.meta.env.VITE_API_URL + 'api/applications/'+ this.applicationId +'?XDEBUG_SESSION_START=PHPSTORM', {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          status: this.status
        })
      })
          .then((response) => {
            if (response.status >= 400) {
              throw response.json()
            }
            this.success = true
            return response.json()
          })
          .then((payload) => {
          })
          .catch((error) => {
            Promise.resolve(error).then((data) => this.setError(data.message))
          })
    }
  },
  created() {
    const route = useRoute()
    console.log(route.params)
    if (!route.params.status || !route.params.applicationId) {
      this.setError('Page parameters are not initialized properly')
    }
    this.userId = route.params.userId
    this.status = route.params.status
    this.applicationId = route.params.applicationId
    this.changeApplicationStatus()
  }
}
</script>

<style scoped>

</style>
