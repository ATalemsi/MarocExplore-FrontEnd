<template>
  <div>
    <Navbar />

    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="p-8">
        <div class="flex justify-end mb-6">
          <button
            v-if="isAuthenticated"
            @click="handleAddItinerary"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded"
          >
            Add Itinerary
          </button>
        </div>
        <div
          v-for="itinerary in itineraries"
          :key="itinerary.id"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <ItineraryCard
            :itinerary="itinerary"
            :authenticatedUserId="authenticatedUserId"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from '@/components/Nav-bar.vue'
import ItineraryCard from '@/components/ItineraryCard.vue'
import axios from 'axios'

export default {
  components: {
    Navbar,
    ItineraryCard
  },
  data() {
    return {
      itineraries: [],
      loading: false,
    }
  },
  created() {
    this.fetchItineraries();
  },
  methods: {
    async fetchItineraries() {
      try {
        this.loading = true
        const response = await axios.get('http://localhost:8000/api/itineraries/all')
        this.itineraries = response.data
        console.log('Response from API:', this.itineraries)
      } catch (error) {
        console.error('Error fetching itineraries:', error)
      } finally {
        this.loading = false
      }
    },
    handleAddItinerary() {
      this.$router.push('/create-itinerary')
    },
    isAuthenticated() {
      const token = localStorage.getItem('token')
      return !!token
    }
  },
  computed: {
    authenticatedUserId() {
      return localStorage.getItem('userId');
    }
  }
}
</script>