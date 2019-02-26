<template>
  <v-app>
    <v-content>
      <v-container fluid>
        <Home />
      </v-container>
    </v-content>
  </v-app>
</template>

<script lang="ts">
import Home from "@/views/Home.vue";

export default {
  name: "App",
  components: { Home },
  data() {
    return {};
  }
};
</script>
