<template>
  <div>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/"
          ><img class="d-block mx-auto me-4" :src="image" alt="" width="100"
        /></a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarCollapse"
          aria-controls="navbarCollapse"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item">
              <a :class="(pageLink == '/')?'nav-link active':'nav-link'" aria-current="page" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a :class="(pageLink == '/team/create')?'nav-link active':'nav-link'" href="/team/create">{{
                $t("home.createteam")
              }}</a>
            </li>
            <li class="nav-item">
              <a :class="(pageLink == '/team/list')?'nav-link active':'nav-link'" href="/team/list">{{
                $t("home.teamlist")
              }}</a>
            </li>
          </ul>

        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import image from "../../images/Pokemon_logo.png";
export default {
  components: {},
  data() {
    return {
      image: image,
      pageLink: ""
    };
  },
  mounted() {
      this.pageLink = this.$page.url;
  },
};
</script>

