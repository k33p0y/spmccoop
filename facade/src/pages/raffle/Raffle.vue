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
											PRICE
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
          <Transition name="bounce">
            <div v-show="showImage" class="row justify-center">
              <p class="txt-name text-h1 text-weight-bold"
              style="color: teal; text-align: center;">
              </p>
            </div>
          </Transition>
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
        showPicker: false,
        showImage: false,
        electionName: '',
        electionId: null,
        btnDisabled: false,
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
              this.participants.list = response.data.filter( obj => obj['voted'] == 1)
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
          this.showPicker = false
          var name = document.getElementsByClassName("txt-name")[0];
          for (let i=0; i<this.participants.winners.length; i++) {
            const index = this.participants.list.findIndex(p => p.name == this.participants.winners[i]['winner']);
            // console.log(index)
            // console.log(this.participants.list[index])
            if (index > -1) this.participants.list.splice(index, 1);
          }
          if (this.participants.list.length) {
            // console.log(this.participants.list, 'list')
            // console.log(this.participants.winners, 'winners')
            const random = Math.floor(Math.random() * this.participants.list.length);
            // console.log(random)
            // console.log(this.participants.list[random]["name"])
            name.innerHTML = this.participants.list[random]["name"];
            this.showImage = true
            this.executeStore(this.participants.list[random]["id"], this.form.price)
            this.getAllWinners();
            this.getVoters();
          } else alert('No participants left');
        }
      },
      resetRaffle() {
        this.totalParticipants = 0
        this.showPicker = false
        this.showImage = false
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
          .then(() => {
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
            // console.log(response.data, 'winners')
          })
          .catch(error => {
            this.$http.requestError(error);
          })
      },
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
		animation: bounce-in 0.5s;
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
</style>
