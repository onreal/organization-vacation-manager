<template>
  <CreateApplication></CreateApplication>
  <table-component v-if="isLoaded" :configure="header" :data="getApplications"></table-component>
  <div class="notification-txt" v-if="empty">You don't have any application yet, create you first one!</div>
</template>

<script>
import errorMixin from "@/components/Mixins/errorMixin";
import { provide } from 'vue'
import store from '@/store'
import Application from "@/components/Application/Application.vue";
import CreateApplication from "@/components/Application/CreateApplication.vue";
import TableComponent from "@/components/TableComponent.vue";

export default {
  name: "Applications",
  components: {Application,CreateApplication,TableComponent},
  setup() {
    provide( 'store', store)
  },
  mixins: [errorMixin],
  data() {
    return {
      empty: false,
      email: null,
      password: null,
      applications: [],
      header: [{
        title: 'Submitted',
        property: 'createdDatetime'
      },{
        title: 'Period',
        property: ['fromDate', 'toDate']
      },{
        title: 'Days',
        property: 'days'
      },{
        title: 'Status',
        property: 'status'
      }]
    };
  },
  methods: {
    initApplications() {
      fetch(import.meta.env.VITE_API_URL + this.getApplicationsEndpoint(), {
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
            store.methods.initApplications(payload)
            if (this.getApplications.length < 0) {
              this.empty = true
            }
          })
          .catch((error) => {
            Promise.resolve(error).then((data) => this.setError(data.message))
          })
    },
    getApplicationsEndpoint() {
      if (store.methods.isRole('admin')) {
        return 'api/applications';
      } else {
        return 'api/applications/user/' + store.methods.getUser().userId
      }
    },
    validateApplication() {},
    createApplication() {}
  },
  computed: {
    getApplications () {
      return store.state.applications
    },
    isLoaded () {
      return this.getApplications.length > 0
    }
  },
  created() {
    this.initApplications()
  }
  // Create a new application
  // Show user applications
}
</script>

<style scoped>
</style>
