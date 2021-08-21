<template>
  <div>
     <div class="px-4 py-5 my-5 text-center">
      <img class="d-block mx-auto mb-4" :src="image" alt="" />
      <h1 class="display-5 fw-bold">Pokémon Tournament</h1>
      <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">
          {{ $t("home.message") }}
        </p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <button
            type="button"
            class="btn btn-primary btn-lg px-4 gap-3"
            @click="createTeam"
          >
            {{ $t("home.createteam") }}
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary btn-lg px-4"
            @click="teamList"
          >
            {{ $t("home.teamlist") }}
          </button>
        </div>
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
    createTeam() {
      location.href = "/team/create";
    },
    teamList() {
      location.href = "/team/list";
    },
    changeLocale(event)
    {
      this.$root.switchLocale(event, 'en');
      //console.log(this.$root.switchLocale());
    }
  },
};
</script>