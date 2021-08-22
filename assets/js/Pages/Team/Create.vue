<template>
  <div>
    <form
      id="form-team"
      @submit.prevent="onSubmit"
      @keydown="form.errors.clear()"
      autocomplete="off"
    >
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="input-name" data-required="true">{{
              $t("team.name")
            }}</label>
            <input
              :class="[
                'form-control',
                { 'is-invalid': form.errors.has('name') },
              ]"
              id="input-name"
              type="text"
              maxlength="255"
              v-model="form.name"
            />
            <div
              class="invalid-feedback"
              v-if="form.errors.has('name')"
              v-text="form.errors.get('name')"
            ></div>
          </div>
        </div>
        <div class="col-md-6">
          <p v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
          </ul>
          </p>
        </div>
      </div>
<div class="table-responsive">
      <table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ $t('name') }}</th>
      <th scope="col">{{ $t('base_experience') }}</th>
      <th scope="col">{{ $t('image') }}</th>
      <th scope="col">{{ $t('abilities') }}</th>
      <th scope="col">{{ $t('types') }}</th>
    </tr>
  </thead>
  <tbody>
    <tr :key="item.name" v-for="item in pokemons">
      <td scope="row">
        <button class="btn btn-outline-secondary" type="button" @click="deleteItem" :data-name="item.name">X</button>
      </td>
      <td>{{ item.name }}</td>
      <td>{{ item.info.base_experience }}</td>
      <td><img :src="item.info.sprites.front_default" ></td>
      <td>
        <ul>
          <li :key="ability.ability.name" v-for="ability in item.info.abilities">{{ ability.ability.name }}</li>
        </ul>
      </td>
      <td>
        <ul>
            <li :key="type.type.name" v-for="type in item.info.types">{{ type.type.name }}</li>
          </ul>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <th scope="row" colspan="2" class="text-end">{{ $t('base_experience_total') }}</th>
        <td>{{ base_experience_total }}</td>
      </tr>
  </tfoot>
</table>
</div>
      <div class="row">
        <div class="col-md-1">
          <button class="btn btn-primary" type="submit" form="form-team" :disabled="form.errors.any()">{{ $t("save") }}</button>
        </div>
        <div class="col-2">
        <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#gonnaModal"
        @click="takePokemon"
        :disabled="catching"
      >
        {{ $t("team.takepokemon") }}
      </button>
      </div>
      </div>
    </form>

    <div class="modal" tabindex="-1" id="gonnaModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $t("team.takepokemon") }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="display: none;"></button>
      </div>
      <div class="modal-body">
        <p><img class="d-block mx-auto" :src="image" alt="" v-if="catching"/></p>
      </div>
    </div>
  </div>
</div>

  </div>
</template>

<script>
import Form from "../../utilities/Form";
import image from "../../../images/gotta_catch.gif";

export default {
  props: {
    data: Object,
    team: Object,
  },
  data() {
    return {
      random: false,
      pokemon: "",
      form: new Form({
        id: 0,
        name: "",
        teamitems: "",
      }),
      url: "/team/save",
      numPokemons: 0,
      pokemons: [],
      availablePokemons: [],
      errors: [],
      catching: false,
      image: image,
      base_experience_total: 0
    };
  },
  mounted() {
    if (this.team) {
      this.form.id = this.team.id;
      this.form.name = this.team.name;
      this.pokemons = this.team.items;
    }
    this.numPokemons = this.data.count;
    this.availablePokemons = this.data.results;
    this.base_experience_total = this.calcBaseExperienceTotal();
  },
  methods: {
    teamList() {
      location.href = "/team/list";
    },
    takePokemon() {
      this.catching = true;
      //$('#gonnaModal').toggle();
      var numRandom = Math.floor(Math.random() * this.numPokemons);
      axios
        .get("/team/api/pokemon/" + this.availablePokemons[numRandom].name)
        .then((response) => {
          this.availablePokemons[numRandom].info = response.data;
          this.pokemons.push(this.availablePokemons[numRandom]);
          this.availablePokemons.splice(numRandom, 1);
          this.numPokemons--;
          this.catching = false;
          $("#gonnaModal .btn-close").click();
          this.base_experience_total = this.calcBaseExperienceTotal();
        });
    },
    deleteItem(event) {
      var pokemon = event.target.getAttribute("data-name");
      var indexElement = this.pokemons.findIndex(
        (element) => element.name == pokemon
      );
      this.pokemons.splice(indexElement, 1);
      this.base_experience_total = this.calcBaseExperienceTotal();
    },
    calcBaseExperienceTotal()
    {
      var total = 0;
      this.pokemons.forEach(element => {
        total += element.info.base_experience;
      });
      return total;
    },

    /* Submit the form */
    onSubmit() {
      var id = this.form.id;
      var thisObj = this;
      this.form.teamitems = this.pokemons;
      this.form.submit(id == 0 ? "post" : "put", this.url).then((response) => {
        if (response.code == 200) {
          location.href = "/team/list";
        } else {
          this.form.id = id;
          var violations = [];
          violations = response.errors.violations;
          violations.forEach(function (item) {
            thisObj.errors.push(item.title);
          });
        }
      });
    },
  },
};
</script>