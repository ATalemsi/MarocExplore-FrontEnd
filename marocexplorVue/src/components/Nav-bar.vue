<template>
  <nav class="bg-gray-800 p-4">
    <div class="container mx-auto">
      <div class="flex justify-between items-center">
        <router-link to="/itineraries" class="text-white text-xl font-bold">My App</router-link>
        <div>
          <router-link to="/itineraries" class="text-white">Itinerary</router-link>
          <button @click="logout" class="text-white ml-4">Logout</button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import axios from 'axios';

export default {
  methods: {
    async logout() {
      try {
        await axios.post('http://localhost:8000/api/logout');
        localStorage.removeItem('token');
        localStorage.removeItem('userId');
        // Redirect the user to the login page
        this.$router.push('/');
      } catch (error) {
        console.error('Error logging out:', error);
        // Optionally, display an error message to the user
        // alert('An error occurred while logging out. Please try again.');
      }
    }
  }
}
</script>

<style>
/* Add your custom styles here */
</style>
