<template>
  <div class="container">
    <form @submit.prevent="login">
      <h2 class="mb-3">Login</h2>
      <div class="input">
        <label for="email">Email address</label>
        <input
            class="form-control"
            type="text"
            name="email"
            placeholder="email@adress.com"
        />
      </div>
      <div class="input">
        <label for="password">Password</label>
        <input
            class="form-control"
            type="password"
            name="password"
            placeholder="password123"
        />
      </div>
      <div class="alternative-option mt-4">
      </div>
      <button type="submit" class="mt-4 btn-pers" id="login_button">
        Login
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    login(submitEvent) {
      this.email = submitEvent.target.elements.email.value;
      this.password = submitEvent.target.elements.password.value;
    },
    moveToRegister() {
    },
  },
};
</script>
