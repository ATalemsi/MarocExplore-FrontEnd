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
          <h2 class="text-center text-red-800 text-3xl font-semibold mb-8">Register</h2>
          <form @submit.prevent="registerAction">
            <div class="mb-4">
              <label for="name" class="block text-red-400 text-sm font-bold mb-2">Name</label>
              <input
                v-model="name"
                type="text"
                id="name"
                name="name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 text-black"
                placeholder="Enter your name"
              />
            </div>
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
            <div class="mb-4">
              <label for="c_password" class="block text-red-400 text-sm font-bold mb-2"
                >Confirm Password</label
              >
              <input
                v-model="c_password"
                type="password"
                id="c_password"
                name="confirm_password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 text-black"
                placeholder="Confirm your password"
              />
            </div>
            <button
              type="submit"
              class="w-full py-3 px-4 bg-red-700 hover:bg-red-500 text-gray-200 font-semibold rounded-md focus:outline-none focus:ring focus:ring-red-500 transition-all duration-300 ease-in-out"
            >
              Register
            </button>
          </form>
          <p class="mt-4 text-sm text-gray-600 text-center">
            Already have an account?
            <router-link to="/" class="font-medium text-red-600 hover:text-indigo-500"
              >Login here</router-link
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
  name: 'RegisterPage',
  components: {
    LayoutDiv
  },
  data() {
    return {
      name: '',
      email: '',
      password: '',
      c_password: '',
      validationErrors: {},
      isSubmitting: false
    }
  },
  methods: {
    registerAction() {
      if (this.password !== this.c_password) {
        // Passwords do not match, handle accordingly (e.g., show error message)
        return
      }
      this.isSubmitting = true
      const payload = {
        name: this.name,
        email: this.email,
        password: this.password,
        c_password: this.c_password
      }
      axios
        .post('http://localhost:8000/api/register', payload) // Adjust the API endpoint to your backend registration route
        .then((response) => {
          alert('Registration successful! Please log in.')

          this.$router.push('/')
          console.log('Additional data from server:', response.data)
        })
        .catch((error) => {
          this.isSubmitting = false
          if (error.response.data.errors !== undefined) {
            this.validationErrors = error.response.data.errors
            console.log('Validation error:', this.validationErrors)
          }
          if (error.response.data.error !== undefined) {
            this.validationErrors = error.response.data.error
            console.log('Validation error 2 :', this.validationErrors)
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
