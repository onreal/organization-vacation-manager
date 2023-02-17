<template>
  <form @submit.prevent="action()">
    <ul class="flex-outer">
      <li>
        <label for="firstName">First Name</label>
        <input v-model="user.firstName" id="firstName" placeholder="Set user first name">
      </li>
      <li>
        <label for="lastName">Last Name</label>
        <input v-model="user.lastName" id="lastName" placeholder="Set user last name">
      </li>
      <li>
        <label for="email">Email</label>
        <input v-model="user.email" type="email" id="email" placeholder="Set user email">
      </li>
      <li>
        <label for="password">Password</label>
        <input v-model="user.password" type="password" id="password" placeholder="Set the password">
      </li>
      <li>
        <label for="confirmPassword">Confirm Password</label>
        <input v-model="user.confirmPassword" type="password" id="confirmPassword" placeholder="Confirm Password">
      </li>
      <li>
        <label for="role">Role</label>
        <select v-model="user.role" id="role">
          <option selected value="0">Select a role</option>
          <option value="admin">Admin</option>
          <option value="employee">Employee</option>
        </select>
      </li>
      <li>
        <button type="submit">{{isEdit ? 'Update' : 'Create'}}</button>
        <button @click="finish()" type="button">{{isEdit ? 'Back to users' : 'Close'}}</button>
      </li>
    </ul>
  </form>
</template>

<script>
import store from "@/store";
import errorMixin from "@/components/Mixins/errorMixin";
export default {
  name: "UserForm",
  mixins: [errorMixin],
  props: {
    isEdit: Boolean,
    user: Object
  },
  methods: {
    action() {
      this.clearErrors()
      this.validate()
      if (this.getErrors.length > 0) {
        return
      }
      let userId = this.user.userId
      let urlParams = this.isEdit && userId ? '/' + userId : ''
      let url = import.meta.env.VITE_API_URL + 'api/users' + urlParams
      fetch(url + '?XDEBUG_SESSION_START=PHPSTORM', {
        method: this.isEdit && userId ? 'PUT' : 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.user)
      })
          .then((response) => {
            if (response.status >= 400) {
              throw response.json()
            }
            return response.json()
          })
          .then((payload) => {
            store.methods.setUser(payload)
            this.finish(true)
          })
          .catch((error) => {
            Promise.resolve(error).then((data) => this.setError(data.message))
          })
    },
    validate() {
      if (!this.user.firstName) {
        this.setError('User first name is required')
      }
      if (!this.user.lastName) {
        this.setError('User last name is required')
      }
      if (!this.validateEmail()) {
        this.setError('Please set a proper email')
      }
      if (this.user.hasOwnProperty('password')) {
        if (!this.validatePassword()) {
          this.setError('password should contain at least one lowercase, one capital, one special character, one number and at least 8 character length. eg. P@assw0rd')
        }
      }
      if (this.user.password !== this.user.confirmPassword) {
        this.setError('Password and confirm password should be the same')
      }
      console.log(this.user.role)
      if (!(this.user.role === 'admin' || this.user.role === 'employee')) {
        this.setError('Select a role for the user')
      }
    },
    validateEmail() {
      if (!this.user.email) {
        return false
      }
      const mailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
      return this.user.email.match(mailRegex);
    },
    validatePassword() {
      if (!this.user.password) {
        return false
      }
      const passRegex = "(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})"
      return this.user.password.match(passRegex);
    },
    finish(clear = false) {
      if (this.isEdit) {
        this.$router.push({ name: 'Users' });
      } else {
        if (clear) this.clearForm()
        this.$emit('onFinish')
      }
    },
    clearForm() {
      this.user = {
        userId: null,
        firstName: null,
        lastName: null,
        email: null,
        role: 0,
        password: null,
        confirmPassword: null,
      }
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
