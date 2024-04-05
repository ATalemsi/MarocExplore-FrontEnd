<template>
  <div>
    <Navbar />
    <div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
      <!-- Form for adding an itinerary -->
      <form @submit.prevent="addItinerary" class="mx-auto mt-16 max-w-xl sm:mt-20" enctype="multipart/form-data">
        <div class="mx-auto max-w-2xl text-center mb-8">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Add Itinerary</h2>
        </div>

        <!-- Title -->
        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
          <div>
            <label for="title" class="block text-sm font-semibold leading-6 text-black">Title</label>
            <div class="mt-2.5">
              <input v-model="title" type="text" id="title" name="title" autocomplete="off" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-blue-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-400 sm:text-sm sm:leading-6">
              <p v-if="validationErrors.title" class="text-red-500 text-sm">{{ validationErrors.title[0] }}</p>
            </div>
          </div>
          <!-- Category -->
          <div>
            <label for="category" class="block text-sm font-semibold leading-6 text-black">Category</label>
            <div class="mt-2.5">
              <select v-model="category" id="category" name="category" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-blue-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-400 sm:text-sm sm:leading-6">
                <option value="" disabled>Select Category</option>
                <option value="plage">Plage</option>
                <option value="montagne">Montagne</option>
                <option value="rivière">Revierre</option>
                <option value="monument">Monument</option>
              </select>
              <p v-if="validationErrors.category" class="text-red-500 text-sm">{{ validationErrors.category[0] }}</p>
            </div>
          </div>
          <!-- Duration -->
          <div class="sm:col-span-2">
            <label for="duration" class="block text-sm font-semibold leading-6 text-black">Duration (days)</label>
            <div class="mt-2.5">
              <input v-model.number="duration" type="number" id="duration" name="duration" autocomplete="off"  class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-blue-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-400 sm:text-sm sm:leading-6">
              <p v-if="validationErrors.duration" class="text-red-500 text-sm">{{ validationErrors.duration[0] }}</p>
            </div>
          </div>
          <!-- Image Upload -->
          <div class="sm:col-span-2">
            <label for="image" class="block text-sm font-semibold leading-6 text-black">Image</label>
            <div class="mt-2.5">
              <input @change="handleFileChange" type="file" accept="image/*" id="image" name="image" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-blue-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-400 sm:text-sm sm:leading-6">
              <p v-if="validationErrors.image" class="text-red-500 text-sm">{{ validationErrors.image[0] }}</p>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-10">
          <button type="submit" class="block w-full rounded-md bg-cyan-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Itinerary</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Navbar from '@/components/Nav-bar.vue'

export default {
  name: 'CreateItineraryPage',
  components: {
    Navbar
  },
  data() {
    return {
      title: '',
      category: '',
      duration: '',
      image: '',
      validationErrors: {},
      isSubmitting: false
    }
  },
  methods: {
    addItinerary() {
      this.isSubmitting = true
      const payload = new FormData()
      payload.append('title', this.title)
      payload.append('category', this.category)
      payload.append('duration', this.duration)
      payload.append('image', this.image)
      this.createItinerary(payload)
    },
    createItinerary(payload) {
      axios.post('http://localhost:8000/api/itineraries', payload)
        .then((response) => {
          alert('Itinerary added successfully!')
          this.$router.push('/itineraries')
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
            console.log('Validation error 2:', this.validationErrors)
          }
        })
    },
    handleFileChange(event) {
      this.image = event.target.files[0]
    }
  }
}
</script>

<style scoped></style>
