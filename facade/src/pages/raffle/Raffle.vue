<template>
	<q-page padding>
		<q-breadcrumbs>
			<q-breadcrumbs-el label="Home" icon="home" to="/" />
			<q-breadcrumbs-el label="Raffle" icon="casino" to="/raffle-winners"/>
			<q-breadcrumbs-el label="Play" icon="sports_basketball" />
		</q-breadcrumbs>
		<p class="text-h4 q-my-md ">{{ electionName }} Raffle Game</p>

		<div class="row justify-center q-mt-xl">
			<q-card class="my-card col-md-6 col-sm-10 card-raffle">
				<q-card-section class="q-ma-sm">
					<div class="row justify-center">
						<div class="q-pa-md" style="width: 100%" >
							<div class="q-gutter-md" style="text-align: center;">
								<q-form>
									<q-input class="input-price" v-model="form.price" autofocus color="teal-8" hide-bottom-space input-class="price" label="PRICE" outlined>
										<template v-slot:label>
											PRIZE
											<span class="text-negative">*</span>
										</template>
									</q-input>
								</q-form>
							</div>
						</div>
					</div><br>
					<div v-show="showImage" class="row justify-center">
						<p class="txt-winner text-h5 text-weight-bold">WINNER:</p>
					</div>
            <div v-show="showImage" class="row justify-center">
              <p class="txt-name text-h1 text-weight-bold"
              style="color: teal; text-align: center;">{{ currentWinner }}
              </p>
            </div>
            <div v-show="showSpinner" class="row justify-center q-mb-lg">
              <div class="loader"></div>
            </div>
					<Transition name="bounce">
						<div v-show="showImage" class="row justify-center q-pa-md q-gutter-sm">
							<q-img
								src="../../assets/winner.png"
								spinner-color="white"
								style="height: 40%; max-width: 45%"
							/>
						</div>
					</Transition>
					<div class="row justify-center">
						<q-btn @click="pickWinner" class="glossy q-ma-sm" color="teal" :label="participants.winners.length ? 'Pick another name' : 'Start'" :disabled="btnDisabled"/>
						<q-btn @click="resetRaffle" class="glossy q-ma-sm" color="teal" label="Clear Price" v-if="participants.winners.length" :disabled="btnDisabled"/>
						<q-btn @click="removeWinner" class="glossy q-ma-sm" color="red" label="Invalidate" v-show="showImage" :disabled="btnDisabled"/>
					</div>
				</q-card-section>
			</q-card>
		</div>
	</q-page>

</template>

<script>
import { Transition } from 'vue';


	export default {
    name: "RouletteIndex",
    data() {
      return {
        participants: {
          winners: [],
          list: []
        },
        form: {
          price: null
        },
        showImage: false,
        showSpinner: false,
        electionName: '',
        electionId: null,
        btnDisabled: false,
        currentWinner: '',
      };
    },
    methods: {
      getActiveElection() {
        this.$http.get('api/election')
          .then(response => {
            if (response.data) {
              this.electionName = response.data.filter(this.getElectionName)[0]['name'];
              this.electionId = response.data.filter(this.getElectionName)[0]['id'];
            }
          })
          .catch(error => {
            this.$http.requestError(error);
            console.log(error);
          })
      },
      getVoters() {
          this.$http.get("api/election/vote/voter-list")
            .then(response => {
              this.participants.list = response.data
          })
              .catch(error => {
              this.$http.requestError(error);
              console.log(error);
          });
      },
      getElectionName(obj) {
        return obj["status"] == 1;
      },
      pickWinner() {
        if (!this.form.price) alert('Please input price for the raffle.')
        else {
          this.showImage = false
          for (let i=0; i<this.participants.winners.length; i++) {
            const index = this.participants.list.findIndex(p => p.name == this.participants.winners[i]['winner']);
            if (index > -1) this.participants.list.splice(index, 1);
          }
          if (this.participants.list.length) {
            const random = Math.floor(Math.random() * this.participants.list.length);
            this.btnDisabled = true
            this.showSpinner = true
            this.executeStore(this.participants.list[random]["id"], this.form.price)
          } else {
            this.form.price = ''
            this.currentWinner = ''
            alert('No participants left');
          }
        }
      },
      resetRaffle() {
        this.showImage = false
        this.showSpinner = false
        this.form.price = ''
        this.getAllWinners();
        this.getVoters();
      },
      executeStore(user_id, price) {
        var params = {
          election_id: this.electionId,
          winner_user_id: user_id,
          price: price
        }
        this.$http.post('api/raffle/store', params)
          .then(response => {
              this.getAllWinners();
              this.getVoters();
              setTimeout(( () => {
                this.currentWinner = response.data['winner']
                this.showSpinner = false
                this.showImage = true
                this.btnDisabled = false
              }), 2000);
          })
          .catch( error => {
            this.loading = false;
            // this.errors = this.$http.requestError(error);
          });
      },
      getAllWinners() { // get all winners for the current active election
        this.$http.get('api/raffle/current/winners')
          .then(response => {
            this.participants.winners = response.data;
          })
          .catch(error => {
            this.$http.requestError(error);
          })
      },
      removeWinner() {
        let answer = confirm("Are you sure you want to invalidate current winner?");
        if (answer) {
          let index = this.participants.winners.findIndex(w => w.winner == this.currentWinner);
          var params = {
            raffle_id: this.participants.winners[index]['raffle_id']
          }
          this.$http.patch('api/raffle/invalidate/winner', params)
            .then(() => {
              this.getAllWinners();
              this.getVoters();
              this.currentWinner = ''
              this.showImage = false;
            })
            .catch( error => {
              this.loading = false;
              // this.errors = this.$http.requestError(error);
            });
        }
      }
    },
    created() {
      this.getAllWinners();
      this.getVoters();
      this.getActiveElection();
    },
    components: { Transition }
};
</script>

<style lang="scss" scoped>
	.bounce-enter-active {
		animation: bounce-in .2s;
	}
	.bounce-leave-active {
		animation: bounce-in 0s reverse;
	}
	@keyframes bounce-in {
		0% {
			transform: scale(0);
		}
		50% {
			transform: scale(1.25);
		}
		100% {
			transform: scale(1);
		}
	}

  .loader {
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
    animation: spin 2s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  /* Safari */
  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }
</style>
