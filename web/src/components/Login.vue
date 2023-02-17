<template>
  <div class="container">
    <form @submit.prevent="login">
      <h2 class="mb-3">Login</h2>
      <div class="input">
        <label for="email">Email address</label>
        <input
            v-model="email"
            class="form-control"
            type="text"
            name="email"
            placeholder="email@adress.com"
        />
      </div>
      <div class="input">
        <label for="password">Password</label>
        <input
            v-model="password"
            class="form-control"
            type="password"
            name="password"
            placeholder="password123"
        />
      </div>
      <div class="alternative-option mt-4">
      </div>
      <button :disabled="disabled" type="submit" class="mt-4 btn-pers" id="login_button">
        Login
      </button>
    </form>
  </div>
</template>

<script>
import errorMixin from "@/components/Mixins/errorMixin";
export default {
  data() {
    return {
      email: null,
      password: null,
      disabled: false
    };
  },
  mixins: [errorMixin],
  methods: {
    login() {
      this.clearErrors()
      if (!this.validateEmail()) {
        this.setError('please set a proper email')
      }
      if (!this.validatePassword()) {
        this.setError('password should contain at least one lowercase, one capital, one special character, one number and at least 8 character length. eg. P@assw0rd')
      }
      if (this.getErrors.length > 0) {
        return
      }
      this.disabled = true
      const data = {
        email: this.email,
        password: this.password
      }
      fetch(import.meta.env.VITE_API_URL + 'api/users/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
          .then((response) => {
            if (response.status >= 400) {
              this.disabled = false
              throw response.json()
            }
            return response.json()
          })
          .then((payload) => {
            this.loginOperations(payload)
          })
          .catch((error) => {
            this.disabled = false
            Promise.resolve(error).then((data) => this.setError(data.message))
          })
    },
    loginOperations(payload) {
      const cookie = 'vacation_user_email=' + payload.email + ';'
      console.log(cookie)
      document.cookie = cookie
      localStorage.removeItem('user')
      localStorage.setItem('user', JSON.stringify(payload))
      if (payload.role === 'admin') {
        this.$router.push({ name: 'Users' });
      } else {
        this.$router.push({ name: 'Applications' });
      }
      this.disabled = false
    },
    validateEmail() {
      if (!this.email) {
        return false
      }
      const mailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
      return this.email.match(mailRegex);
    },
    validatePassword() {
      if (!this.password) {
        return false
      }
      const passRegex = "(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})"
      return this.password.match(passRegex);
    }
  }
};
</script>
