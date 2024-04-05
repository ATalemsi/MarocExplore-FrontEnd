<template>
  <div class="relative">
    <div
      class="absolute inset-0 bg-cover bg-center z-0"
      style="
        background-image: url('https://www.maroc-explore.com/wp-content/uploads/2022/01/cropped-lotphy-TEezQooRk-4-unsplash-scaled-1.jpg');
      "
    ></div>
    <layout-div>
      <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md z-10">
          <h2 class="text-center text-red-800 text-3xl font-semibold mb-8">Sign In</h2>
          <form @submit.prevent="loginAction">
            <div class="mb-4">
              <label for="email" class="block text-red-400 text-sm font-bold mb-2"
                >Email address</label
              >
              <input
                v-model="email"
                type="email"
                id="email"
                name="email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 text-black"
                placeholder="Enter your email"
              />
            </div>
            <div class="mb-4">
              <label for="password" class="block text-red-400 text-sm font-bold mb-2"
                >Password</label
              >
              <input
                v-model="password"
                type="password"
                id="password"
                name="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 text-black"
                placeholder="Enter your password"
              />
            </div>
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center">
                <input type="checkbox" class="h-4 w-4 text-red-600 border-gray-300 rounded" />
                <label for="remember_me" class="ml-2 block text-sm text-gray-900"
                  >Remember me</label
                >
              </div>
              <div class="text-sm">
                <a href="#" class="font-medium text-red-600 hover:text-red-500"
                  >Forgot your password?</a
                >
              </div>
            </div>
            <button
              :disabled="isSubmitting"
              type="submit"
              class="w-full py-3 px-4 bg-red-700 hover:bg-red-500 text-gray-200 font-semibold rounded-md focus:outline-none focus:ring focus:ring-red-500 transition-all duration-300 ease-in-out"
            >
              Sign In
            </button>
          </form>
          <p class="mt-4 text-sm text-gray-600 text-center">
            Don't have an account?
            <router-link to="/register" class="font-medium text-red-600 hover:text-indigo-500"
              >Register here</router-link
            >
          </p>
        </div>
      </div>
    </layout-div>
  </div>
</template>

<script>
import axios from 'axios'
import LayoutDiv from '@/components/LayoutDiv.vue'

export default {
  name: 'LoginPage',
  components: {
    LayoutDiv
  },
  data() {
    return {
      email: '',
      password: '',
      validationErrors: {},
      isSubmitting: false
    }
  },
  created() {
    if (localStorage.getItem('token') !== '' && localStorage.getItem('token') !== null) {
      this.$router.push('/itineraries')
    }
  },
  methods: {
    loginAction() {
      this.isSubmitting = true
      const payload = {
        email: this.email,
        password: this.password
      }
      axios
        .post('http://localhost:8000/api/login', payload)
        .then((response) => {
          localStorage.setItem('token', response.data.token)
          localStorage.setItem('userId', response.data.userId)

          this.$router.push('/itineraries')
        })
        .catch((error) => {
          this.isSubmitting = false
          if (error.response.data.errors !== undefined) {
            this.validationErrors = error.response.data.errors
          }
          if (error.response.data.error !== undefined) {
            this.validationErrors = error.response.data.error
          }
        })
    }
  }
}
</script>

<style scoped>
/* Add additional styles here */
.w-96 {
  width: 24rem; /* Set the desired width */
}
.absolute.inset-0 {
  z-index: -1; /* Ensure the background image is behind other content */
}
</style>
