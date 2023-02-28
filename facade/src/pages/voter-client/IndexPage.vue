<template>
    <q-page padding>
        <q-form autocomplete="off">
            <!-- <hr /> -->
            <div v-if="!showVoteList" style="width: 500px; margin: 0 auto;">

                <p class="text-h6 blue-grey-8" style="padding-left: 10px;">Enter your SPMC ID No.</p>
                <q-input v-model="form.id" autofocus outlined :error="errors.id" :error-message="errors.idMessage" input-class="id">
                </q-input>
                <q-btn @click="checkUser()" color="primary" label="Check" class="float-right q-mt-sm" />
            </div>
            <div v-else style="width: 1920px; max-width: 90vw; margin: 0 auto;">
                <p class="text-h5 q-mb-lg">{{ list[0]['name'] }}</p>
                <div v-for="(position, key) in list[1]" :key="key" class="q-pb-lg">
                    <span class="text-h6">{{ key }}</span>
                    <hr />
                    <div class="row items-start q-gutter-md">
                        <div v-for="(candidate, index) in position['candidates']">
                            <q-card v-if="position['count'] > 1" class="my-card">
                                {{ candidatePicture(index) }}
                                <img :src="this.images[index]" style="width: 300px; height: 250px;">
                                <q-card-section>
                                    <span>
                                        <q-checkbox :disable="position['count'] <= (form.vote[key]).length && !(form.vote[key]).includes(index)" v-model="form.vote[key]" :val="index" :label="candidate" color="teal" />
                                    </span>
                                </q-card-section>
                            </q-card>

                            <q-card v-else class="my-card">
                                {{ candidatePicture(index) }}
                                <img :src="this.images[index]" style="max-width: 300px; height: 250px;">
                                <q-card-section>
                                    <span>
                                        <q-radio v-model="form.vote[key]" checked-icon="task_alt" color="teal" size="lg" unchecked-icon="panorama_fish_eye" :val="index">
                                            <template v-slot:default>
                                                <span>{{ candidate }}</span>
                                            </template>
                                        </q-radio>
                                    </span>
                                </q-card-section>
                            </q-card>
                        </div>
                    </div>
                </div>
                <q-btn @click="inception = true" color="primary" label="Vote" class="q-mt-sm" />
               
            </div>
        </q-form>
        <q-dialog v-model="inception">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Confirm</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    Are you sure you want to submit you vote?
                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                    <q-btn flat label="Clear" @click="form.vote = form.clearVote" v-close-popup />
                    <q-btn flat label="Go Back" v-close-popup />
                    <q-btn flat label="Vote" @click="executeVote()" />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="inception2">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Info</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    Thank you very much.
                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                    <q-btn flat label="Done" @click="done()" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'IndexPage',
    data() {
        return {
            errors: {},
            showVoteList: false,
            form: {
                id: null,
                vote: {},
                clearVote: {}   // temporary storage for formated clear votes
            },
            images: [],
            loading: false,
            list: {},
            inception: false,
            inception2: false
        }
    },
    methods: {
        done() {
            this.showVoteList = false;
            this.form.id = null;
            this.form.vote = {};
            this.list = {};
            this.inception = false;
            this.inception2 = false;
        },
        checkUser() {
            this.loading = true;
            this.errors = {};
            this.$http.postTokenless('api/check-user', {
                id: this.form.id
            })
                .then(response => {
                    this.$http.getTokenless('api/election/vote/list', {})
                        .then(response => {
                            this.showVoteList = true;
                            this.list = response.data;

                            for(var x in this.list[1]) {
      
                                if(this.list[1][x]['count'] > 1) {
                                    (this.form.vote)[x]= [];
                                    console.log('vote', this.form.vote);
                                }
                            }





                            this.form.clearVote = JSON.parse(JSON.stringify(this.form.vote));

                        })
                        .catch(error => {
                            this.loading = false;
                            this.errors = this.$http.requestError(error);

                        });


                })
                .catch(error => {
                    this.loading = false;
                    this.errors = this.$http.requestError(error);

                });
        },

        executeVote() {
            this.loading = true;
            this.errors = {};
            this.$http.postTokenless('api/check-user', {
                id: this.form.id
            })
                .then(response => {
                    this.$http.postTokenless('api/election/vote', {
                        id: this.form.id,
                        vote: this.form.vote
                    })
                        .then(response => {
                            this.inception2 = true;
                        })
                        .catch(error => {
                            this.loading = false;
                            this.errors = this.$http.requestError(error);

                        });
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = this.$http.requestError(error);
                });
        },

        candidatePicture(user_id) {
            this.$http.getTokenless('api/profile/candidate-picture/' + user_id)
                .then(response => {
                    this.images[user_id] = response.data;
                })
                .catch(() => {
                    return 'https://cdn.quasar.dev/img/mountains.jpg';
                });
        }
    },
})
</script>
<style lang="scss" scoped>
.my-card {
    width: 100%;
    max-width: 250px;
}
</style>