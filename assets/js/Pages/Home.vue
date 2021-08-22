<template>
  <div>
     <div class="px-4 py-5 my-5 text-center">
      <img class="d-block mx-auto mb-4" :src="image" alt="" />
      <h1 class="display-5 fw-bold">Pokémon Tournament</h1>
      <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">
          {{ $t("home.message") }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import image from "../../images/Pokemon_logo.png";
export default {
  props: {},
  components: {
    
  },
  data() {
    return {
      image: image,
    };
  },
  mounted() {},
  methods: {
  },
};
</script>