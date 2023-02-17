<template>
  <button @click="openModal()">+ Create a new user</button>
  <div v-if="isModal" id="formWrapper" class="up-modal">
    <div class="container">
<!--      <form @submit.prevent="create()">-->
<!--        <ul class="flex-outer">-->
<!--          <li>-->
<!--            <label for="firstName">First Name</label>-->
<!--            <input v-model="user.firstName" id="firstName" placeholder="Set user first name">-->
<!--          </li>-->
<!--          <li>-->
<!--            <label for="lastName">Last Name</label>-->
<!--            <input v-model="user.lastName" id="lastName" placeholder="Set user last name">-->
<!--          </li>-->
<!--          <li>-->
<!--            <label for="email">Email</label>-->
<!--            <input v-model="user.email" type="email" id="email" placeholder="Set user email">-->
<!--          </li>-->
<!--          <li>-->
<!--            <label for="password">Password</label>-->
<!--            <input v-model="user.password" type="password" id="password" placeholder="Set the password">-->
<!--          </li>-->
<!--          <li>-->
<!--            <label for="confirmPassword">Confirm Password</label>-->
<!--            <input v-model="user.confirmPassword" type="password" id="confirmPassword" placeholder="Confirm Password">-->
<!--          </li>-->
<!--          <li>-->
<!--            <label for="role">Role</label>-->
<!--            <select v-model="user.role" id="role">-->
<!--              <option selected value="0">Select a role</option>-->
<!--              <option value="admin">Admin</option>-->
<!--              <option value="employee">Employee</option>-->
<!--            </select>-->
<!--          </li>-->
<!--          <li>-->
<!--            <button type="submit">Create</button>-->
<!--            <button @click="closeModal()" type="button">Close</button>-->
<!--          </li>-->
<!--        </ul>-->
<!--      </form>-->
      <user-form :is-edit="false" :user="user" @on-finish="closeModal()"></user-form>
    </div>
  </div>
</template>

<script>
import CalendarRange from "@/components/Application/CalendarRange.vue";
import errorMixin from "@/components/Mixins/errorMixin";
import store from "@/store";
import {provide} from "vue";
import UserForm from "@/components/User/UserForm.vue";
export default {
  data() {
    return {
      isModal: false,
      user: {
        userId: null,
        firstName: null,
        lastName: null,
        email: null,
        role: 0,
        password: null,
        confirmPassword: null,
      }
    }
  },
  setup() {
    provide( 'store', store)
  },
  components: {UserForm, CalendarRange},
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
