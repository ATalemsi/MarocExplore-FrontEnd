<template>
  <div class="bg-gray-100 min-h-screen">
    <Navbar />
    <div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
      <!-- Destination Form -->
      <form @submit.prevent="submitDestinations" class="mx-auto mt-16 max-w-xl sm:mt-20" enctype="multipart/form-data">
        <div class="mx-auto max-w-2xl text-center mb-8">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Add Destinations</h2>
        </div>
        <!-- Destination Inputs -->
        <div v-for="(destination, index) in destinations" :key="index" class="bg-white rounded-md shadow-md p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2">Destination {{ index + 1 }}</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
              <input v-model="destination.name" type="text" :id="'name-' + index" :name="'name-' + index" autocomplete="off" class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
              <p v-if="errors[index]?.name" class="text-red-500 text-sm mt-1">{{ errors[index]?.name[0] }}</p>
            </div>
            <div>
              <label for="accommodation" class="block text-sm font-semibold text-gray-700 mb-1">Accommodation</label>
              <input v-model="destination.accommodation" type="text" :id="'accommodation-' + index" :name="'accommodation-' + index" autocomplete="off" class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
              <p v-if="errors[index]?.accommodation" class="text-red-500 text-sm mt-1">{{ errors[index]?.accommodation[0] }}</p>
            </div>
            <div class="col-span-2">
              <label for="placesToVisit" class="block text-sm font-semibold text-gray-700 mb-1">Places to Visit</label>
              <input v-model="destination.placesToVisitString" type="text" :id="'placesToVisit-' + index" :name="'placesToVisit-' + index" autocomplete="off" class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" >
              <p v-if="errors[index]?.placesToVisit" class="text-red-500 text-sm mt-1">{{ errors[index]?.placesToVisit[0] }}</p>
            </div>
          </div>
          <!-- Allow deletion only for additional destination forms -->
          <template v-if="index > 0">
            <button @click="removeDestination(index)" type="button" class="mt-4 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md">Delete Destination</button>
          </template>
        </div>
        <!-- Add Destination Button -->
        <div class="flex justify-center">
          <button @click="addDestination" type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md mr-4">Add Destination</button>
          <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md">Submit Destinations</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Navbar from '@/components/Nav-bar.vue'

export default {
  name: 'AddDestination',
  components: { Navbar },

  data() {
    return {
      destinations: [{ name: '', accommodation: '', placesToVisit: [] }],
      itineraryId: null,
      errors: [],
      isSubmitting: false
    }
  },
  mounted() {
    this.itineraryId = this.$route.params.id;
    console.log(this.itineraryId)
  },
  computed: {
    destinationsWithPlacesToVisitString() {
      return this.destinations.map(dest => {
        return {
          ...dest,
          placesToVisitString: dest.placesToVisit.join(', ')
        }
      });
    }
  },
  methods: {
    addDestination() {
      this.destinations.push({ name: '', accommodation: '', placesToVisit: [] });
      this.errors.push({});
    },
    removeDestination(index) {
      // Prevent deletion of the first destination form
      if (index > 0) {
        this.destinations.splice(index, 1);
        this.errors.splice(index, 1); // Remove validation errors for the deleted destination
      }
    },
    submitDestinations() {
      this.isSubmitting = true;
      const payload = {
        destinations: this.destinations
      };
      axios.post(`http://localhost:8000/api/itineraries/${this.itineraryId}/destinations`, payload)
        .then((response) => {
          alert('Destinations added successfully!');
          this.$router.push('/itineraries');
          console.log('Additional data from server:', response.data);
        })
        .catch((error) => {
          this.isSubmitting = false;
          if (error.response.data.errors) {
            this.errors = error.response.data.errors;
          } else {
            console.error('Error adding destinations:', error);
          }
        });
    }
  }
}
</script>

<style scoped>
/* Add your custom styles here */
</style>
