<template>
  <div class="wrapper">
      <app-header-vue/>
      <div class="main">
          <main class="content">
              
              <slot />
          </main>
      </div>
  </div>
</template>

<script>
import AppHeaderVue from './AppHeader.vue';
export default {
    components: {AppHeaderVue}

}
</script>