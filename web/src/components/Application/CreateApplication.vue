<template>
  <button @click="openModal()">+ Create a new application</button>
  <div v-if="isModal" id="formWrapper" class="up-modal">
    <div class="container">
      <form @submit.prevent="create()">
        <ul class="flex-outer">
<!--          <li>-->
<!--            <label for="from-date">From Date</label>-->
<!--            <input v-model="application.fromDate" type="text" id="from-date" placeholder="Select start date">-->
<!--          </li>-->
          <li>
            <label for="to-date">Pickup dates</label>
            <CalendarRange @date-range="getRange" id="to-date"></CalendarRange>
<!--            <input v-model="application.toDate" type="text" id="to-date" placeholder="Select date range">-->
          </li>
          <li>
            <label for="reason">Reason</label>
            <textarea v-model="application.reason" id="reason" placeholder="Set a good reason, think twice"></textarea>
          </li>
          <li>
            <label for="status">Status</label>
            <input disabled v-model="application.status" type="text" id="status" placeholder="Pending for new applications">
          </li>
          <li>
            <button type="submit">Create</button>
            <button @click="closeModal()" type="button">Close</button>
          </li>
        </ul>
      </form>
    </div>
  </div>
</template>

<script>
import CalendarRange from "@/components/Application/CalendarRange.vue";
import errorMixin from "@/components/Mixins/errorMixin";
import store from "@/store";
import {provide} from "vue";
export default {
  data() {
    return {
      isModal: false,
      application: {
        fromDate: null,
        toDate: null,
        reason: null,
        status: "pending",
      }
    }
  },
  setup() {
    provide( 'store', store)
  },
  components: {CalendarRange},
  mixins: [errorMixin],
  name: "CreateApplication",
  methods: {
    openModal() {
      this.isModal = true
    },
    closeModal() {
      this.clearErrors()
      this.isModal = false
    },
    create() {
      this.clearErrors()
      this.validate()
      this.application.userId = store.methods.getUser().userId
      if (this.getErrors.length > 0) {
        return
      }
      fetch(import.meta.env.VITE_API_URL + 'api/applications?XDEBUG_SESSION_START=PHPSTORM', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.application)
      })
          .then((response) => {
            if (response.status >= 400) {
              throw response.json()
            }
            return response.json()
          })
          .then((payload) => {
            store.methods.setApplication(payload)
            this.closeModal()
          })
          .catch((error) => {
            Promise.resolve(error).then((data) => this.setError(data.message))
          })
    },
    validate() {
      if (!this.application.fromDate || !this.application.toDate) {
        this.setError("Please enter a valid range")
      }
      if (!this.application.reason) {
        this.setError("Please write on a reason for this application")
      }
      if (this.application.status !== 'pending') {
        this.setError("Only status pending is allowed for new applications")
      }
    },
    getRange(range) {
      // handle to bigger than from
      // console.log(range.toDate)
      if (range.fromDate > range.toDate) {
        this.application.fromDate = range.toDate
        this.application.toDate = range.fromDate
        return
      }
      this.application.fromDate = range.fromDate
      this.application.toDate = range.toDate
    }
  },
  computed: {
    buttonShow() {
      return this.isModal
    }
  }
}
</script>

<style scoped>
 #formWrapper{
   width: 100%;
   height: 100%;
   position: fixed;
   display: block;
   top: 0;
   left: 0;
   z-index: 1000;
   background-color: #343434;
 }

 .container {
   width: 80%;
   max-width: 1200px;
   margin: 0 auto;
   padding: 7vh 2.5vh 2.5vh 2.5vh;
 }

 .container * {
   box-sizing: border-box;
 }

 .flex-outer,
 .flex-inner {
   list-style-type: none;
   padding: 0;
 }

 .flex-outer {
   max-width: 800px;
   margin: 0 auto;
 }

 .flex-outer li,
 .flex-inner {
   display: flex;
   flex-wrap: wrap;
   align-items: center;
 }

 .flex-inner {
   padding: 0 8px;
   justify-content: space-between;
 }

 .flex-outer > li:not(:last-child) {
   margin-bottom: 20px;
 }

 .flex-outer li label,
 .flex-outer li p {
   padding: 8px;
   font-weight: 300;
   letter-spacing: .09em;
   text-transform: uppercase;
 }

 .flex-outer > li > label,
 .flex-outer li p {
   flex: 1 0 120px;
   max-width: 220px;
 }

 .flex-outer > li > label + *,
 .flex-inner {
   flex: 1 0 220px;
 }

 .flex-outer li p {
   margin: 0;
 }

 .flex-outer li input:not([type='checkbox']),
 .flex-outer li textarea {
   padding: 15px;
   border: none;
 }

 .flex-outer li button {
   margin-left: auto;
   padding: 8px 16px;
   border: none;
   background: #333;
   color: #f2f2f2;
   text-transform: uppercase;
   letter-spacing: .09em;
   border-radius: 2px;
 }

 .flex-inner li {
   width: 100px;
 }
 #status {
   color:#fff;
 }
</style>
