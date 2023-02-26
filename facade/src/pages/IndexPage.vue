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
                    <img src="https://cdn.quasar.dev/img/mountains.jpg">
                    <q-card-section class="text-h6  bg-info">
                        {{ candidate['candidate'] }}
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
            result: {}
        }
    },
    created() {
        this.$http.get('api/election/vote/result', {})
            .then(response => {
                console.log('louie', response);
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
    max-width: 250px;
}
</style>