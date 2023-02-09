import { provide } from 'vue'
import store from '@/store'
export default {
    name: 'errorMixin',
    data() {
        return {
            email: null
        };
    },
    setup() {
        provide( 'store', store)
    },
    methods: {
        setError (error) {
            store.methods.setError(error)
        },
        clearErrors () {
            store.methods.clearErrors()
        }
    },
    computed: {
        getErrors () {
            return store.state.errors
        }
    }
}
