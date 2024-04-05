<template>
  <div
    v-for="item in itinerary"
    :key="item.id"
    class="border border-gray-300 h-fit overflow-hidden transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 bg-white shadow-md rounded-lg mb-4"
  >
    <!-- Image at the top -->
    <img
      :src="getImageUrl(item.image)"
      :alt="item.title"
      class="w-full h-auto object-cover rounded-t-lg"
    />

    <!-- Flex container for content at the bottom -->
    <div class="flex flex-col justify-between h-full p-4">
      <!-- Content -->
      <div class="itinerary-content">
        <h2 class="itinerary-title text-xl font-semibold mb-2">{{ item.title }}</h2>
        <p class="itinerary-category text-sm mb-2">
          <strong>Category:</strong> {{ item.category }}
        </p>
        <p class="itinerary-duration text-sm mb-2">
          <strong>Duration:</strong> {{ item.duration }} jours
        </p>
        <p class="itinerary-description text-gray-700 mb-4">{{ item.description }}</p>
        <div
          v-for="(destination, index) in item.destinations"
          :key="index"
          class="destination mb-4"
        >
          <h3 class="destination-title text-red-600 text-lg font-semibold mb-1">
            Destination {{ index + 1 }}
          </h3>
          <p v-if="destination.name" class="text-gray-600">
            <strong>Lieu:</strong> {{ destination.name }}
          </p>
          <p v-if="destination.accommodation" class="text-gray-600">
            <strong>Accommodation:</strong> {{ destination.accommodation }}
          </p>
          <div v-if="destination.places_to_visit && destination.places_to_visit.length">
            <p class="text-gray-600"><strong>Places to Visit:</strong></p>
            <ul class="list-disc list-inside">
              <li
                v-for="(place, placeIndex) in destination.places_to_visit.split(',')"
                :key="placeIndex"
                class="ml-4 text-gray-600"
              >
                {{ place.trim() }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Button at the bottom -->
      <router-link
        v-if=" !isAuthenticatedUser(item.user_id)"
        class="btn-add-destination bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
        :to="{ path: '/itineraries/'+item.id+'/destinations'}"
      >
        Add Destination
      </router-link>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    itinerary: {
      type: Array,
      required: true
    },
    showAddDestinationBtn: {
      type: Boolean,
      default: false
    },
    authenticatedUserId: {
      type: String, // Assuming the user ID is a string
      required: true
    }
  },
  emits: ['addDestination'], // Declare the emitted event
  methods: {
    getImageUrl(imageFileName) {
      return `http://localhost/storage/itinerary_images/${imageFileName}`
    },
    isAuthenticatedUser(creatorId) {
      const authenticatedUserId = localStorage.getItem('userId');
      console.log('Creator ID:', creatorId);
      console.log('Authenticated User ID:', authenticatedUserId);
      return authenticatedUserId === creatorId;
    }
  }
}
</script>
