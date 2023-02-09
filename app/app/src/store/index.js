import { reactive } from 'vue'

const state = reactive({
    user: null,
    applications: [],
    errors: [],
    users: [],
})

const methods = {
    getUser() {
        if (!state.user) {
            state.user = JSON.parse(localStorage.getItem('user'))
        }
        return state.user;
    },
    isLoggedIn() {
        if (!this.getUser()) {
            return false;
        }
        return document.cookie.indexOf('vacation_user_email=' + state.user.email) >= 0
    },
    isRole(role) {
        const user = this.getUser()
        if (!user) {
            return false
        }
        return (user.role === role)
    },
    setError(error) {
        state.errors.push(error)
    },
    clearErrors() {
        state.errors = []
    },
    initApplications(applications) {
        state.applications = applications
    },
    setApplication(application) {
        state.applications.unshift(application)
    },
    initUsers(users) {
        state.users = users
    },
    setUser(user) {
        state.users.unshift(user)
    }
}

export default {
    state, methods
}
