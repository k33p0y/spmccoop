<template>
  <q-page padding>
      <q-breadcrumbs>
          <q-breadcrumbs-el label="Home" icon="home" to="/" />
      </q-breadcrumbs>
      <br />
      <div v-for="(candidates, key) in result" :key="key" class="q-pb-xl">
          <div class="text-h5">{{ key }}</div>
          <div class="q-pa-md row items-start q-gutter-md">
              <q-card v-for="(candidate, id) in candidates" class="my-card">
                  {{ candidatePicture(candidate['candidate_id']) }}
                  <img :src="images[candidate['candidate_id']]" style="width: 300px; height: 300px;">
                  <q-card-section class="text-h6  bg-info">
                      {{ key }} Candidate {{ makeid(5) }}
                  </q-card-section>
                  <q-card-section class="text-h5">
                      {{ candidate['count'] }} Votes
                  </q-card-section>
              </q-card>
          </div>
      </div>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'IndexPage',
  data() {
      return {
          result: {},
          images: []
      }
  },
  methods: {
      candidatePicture(user_id) {
          this.$http.getTokenless('api/profile/candidate-picture/5000')
              .then(response => {
                  this.images[user_id] = response.data;
              })
              .catch(() => {
                  return 'https://cdn.quasar.dev/img/mountains.jpg';
              });
      },
      makeid(length) {
          let result = '';
          // const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
          const characters = '0123456789';
          const charactersLength = characters.length;
          let counter = 0;
          while (counter < length) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
          counter += 1;
          }
          return result;
      }
  },
  created() {
      this.$http.getTokenless('api/election/vote/result-blind', {})
          .then(response => {
              // this.showVoteList = true;
              this.result = response.data;
          })
          .catch(error => {
              this.loading = false;
              this.errors = this.$http.requestError(error);

          });
  }

})
</script>

<style lang="scss" scoped>
.my-card {
  width: 100%;
  max-width: 300px;
}
</style>
