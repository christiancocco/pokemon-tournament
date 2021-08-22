<template>
  <div>
    <h1>{{ $t("team.teamlist") }}</h1>
    
    <select v-bind="typeSelected" @change="filterTeams">
      <option value="" selected>-</option>
      <option v-for="pokemonType in pokemonTypes" :key="pokemonType.name">{{ pokemonType.name }}</option>
    </select>
    
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col" width="5%">#</th>
            <th scope="col" width="15%">{{ $t("name") }}</th>
            <th scope="col" width="45%">{{ $t("items") }}</th>
            <th scope="col" width="10%">{{ $t("base_experiences") }}</th>
            <th scope="col" width="10%">{{ $t("types") }}</th>
            <th scope="col" width="15%">{{ $t("created") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr :key="item.name" v-for="item in this.data">
            <td scope="row">
              <button class="btn btn-outline-secondary" type="button" @click="deleteTeam" :data-id="item.id">X</button>
            </td>
            <td><a :href="'/team/' + item.id + '/edit'">{{ item.name }}</a></td>
            <td>
              <img :key="pokemon.name"  v-for="pokemon in item.pokemons" :src="pokemon.image" :alt="pokemon.name" :title="pokemon.name">
            </td>
            <td>
              {{ item.total_base_experience}}
            </td>
            <td>
              <ul>
                <li v-for="type in item.types" :key="type">{{ type }}</li>
              </ul>
            </td>
            <td>
              {{ item.created|moment("d/M/YYYY HH:mm")}}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    pokemonTeams: Array,
    pokemonTypes: Array,
  },
  components: {},
  data() {
    return {
      obj: "teams",
      data: this.pokemonTeams,
      typeSelected: 0
    };
  },
  computed: {
    
  },
  mounted() {
    //$('#test').html('ciao');
  },
  methods: {
    editTeam(event)
    {
      location.href = "/team/" + event.target.getAttribute("data-id") + "/edit";
    },
    deleteTeam(event)
    {
      location.href = "/team/" + event.target.getAttribute("data-id") + "/delete";
    },
    createTeam() {
      location.href = "/team/create";
    },
    filterTeams(event)
    {
      if (event.target.value)
      {
        this.data = this.pokemonTeams.filter(element => element.types.findIndex(item => item == event.target.value) > -1);
      }
      else
        this.data = this.pokemonTeams;
    }
  },
};
</script>