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
					<div v-show="showPicker" class="row justify-center">
						<p class="txt-name text-h1 text-weight-bold"
						style="color: teal; text-align: center;">
						</p>
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
						<p class="text-h5">Total participants: {{ totalParticipants }}</p>
					</div>
					<div class="row justify-center">
						<q-btn @click="displayNames" class="glossy q-ma-sm" color="teal" :label="winners.length ? 'Pick another name' : 'Start'" :disabled="btnDisabled"/>
						<q-btn @click="resetRaffle" class="glossy q-ma-sm" color="teal" label="Reset Raffle" v-if="winners.length" :disabled="btnDisabled"/>
					</div>

					<div class="q-mt-lg" v-show="winners.length > 1">
						<q-separator class="" inset />
						<div class="row justify-center q-mt-md">
							<p class="text-weight-bold">PREVIOUS WINNERS:</p>
						</div>
						<div v-for="(value, index) in winners.slice().reverse()">
							<div class="row justify-center q-mt-xs">
								<p v-if="index > 0">{{ value['name'] }}</p>
							</div>
						</div>
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
            voters: {
                rows: []
            },
			form: {
                price: null
            },
            totalParticipants: 0,
            winners: [],
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
                this.voters.rows = response.data.filter(this.getVoterName);
                this.totalParticipants = this.voters.rows.length;
            })
                .catch(error => {
                this.$http.requestError(error);
                console.log(error);
            });
        },
        displayNames() {
			if (!this.form.price) alert('Please input price for the raffle.')
			else {
				this.btnDisabled = true
				this.showImage = false;
				this.showPicker = true;
				let obj = this.voters.rows;
				var name = document.getElementsByClassName("txt-name")[0];
				for (var x = 0, ln = obj.length; x < ln; x++) {
					let self = this;
					setTimeout(function (y) {
						name.innerHTML = obj[y]["name"];
						if (y == ln - 1)
							self.getWinner();
					}, x * 30, x); // we're passing x
				}
			}
        },
        getVoterName(obj) {
            return obj["voted"] == 1;
        },
		getElectionName(obj) {
            return obj["status"] == 1;
        },
        getWinner() {
            var name = document.getElementsByClassName("txt-name")[0];
            const random = Math.floor(Math.random() * this.voters.rows.length);
            name.innerHTML = this.voters.rows[random]["name"];
			this.executeStore(this.voters.rows[random]["id"], this.form.price)
            const index = this.voters.rows.indexOf(this.voters.rows[random]);
			this.winners.push({id: this.voters.rows[random]['id'], name: this.voters.rows[random]['name']})
            if (index > -1) { // only splice array when item is found
                this.voters.rows.splice(index, 1); // 2nd parameter means remove one item only
                this.totalParticipants = this.voters.rows.length;
                this.showImage = true;
				this.btnDisabled = false;
            }
        },
		resetRaffle() {
			this.voters.rows = []
			this.totalParticipants = 0
			this.winners = []
			this.showPicker = false
			this.showImage = false
			this.form.price = ''
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
                .catch(error => {
                    this.loading = false;
                    this.errors = this.$http.requestError(error);
                });
        },
    },
    created() {
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