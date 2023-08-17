<template>
	<q-page padding>
		<q-breadcrumbs>
			<q-breadcrumbs-el label="Home" icon="home" to="/" />
			<q-breadcrumbs-el label="Raffle" icon="account_circle" />
		</q-breadcrumbs>
		<p class="text-h4 q-my-md">Winners</p>
		<q-table :rows="table.rows" :columns="table.columns" :filter="table.filter"
			:pagination="table.paginationSettings" :loading="table.loading" row-key="id"
			:visibleColumns="fullColumns">
			<template v-slot:top>
				<div>
					<q-btn class="q-mb-xs q-mr-xs" to="/raffle" color="blue-grey-8" icon="sports_basketball" size="md"
						unelevated>
						<template v-slot:default>
							<span class="q-mx-xs gt-xs">Play Raffle</span>
						</template>
					</q-btn>
					<q-btn class="q-mb-xs q-mr-xs" @click="printWinner" color="blue-grey-8" icon="print" size="md"
						unelevated>
						<template v-slot:default>
							<span class="q-mx-xs gt-xs">Print</span>
						</template>
					</q-btn>
				</div>
				<q-space />
				<div>
					<q-input class="q-mb-xs" borderless dense filled debounce="300" bg-color="blue-grey-1"
						color="blue-grey" placeholder="Search" v-model="table.filter">
						<template v-slot:append>
							<q-icon name="search" />
						</template>
					</q-input>
				</div>
			</template>
			<template v-slot:header="props">
				<q-tr :props="props" class="text-blue-grey-10 text-uppercase">
					<q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-left">
						<span class="text-subtitle2 text-bold">{{ col.label }}</span>
					</q-th>
				</q-tr>
			</template>
			<template v-slot:body="props">
				<q-tr class="table-row-height" :props="props">
					<q-td class="table-number-field" key="number" :props="props">{{ props.rowIndex + 1 }} <span id="raffle-id" v-show="false">{{  props.row.id }}</span></q-td>
					<q-td key="name" :props="props">
                        {{ props.row.winner }}
                    </q-td>
					<q-td key="election" :props="props">
                        {{ props.row.election }}
                    </q-td>
					<q-td key="price" :props="props"><span @click="onUpdate(props.row)" class="table-primary-field">
                        {{ props.row.price }}</span>
                    </q-td>
				</q-tr>
			</template>
			<template v-if="table.loading" v-slot:no-data>
				<div class="full-width row flex-center q-gutter-sm">
					<q-spinner-gears color="primary" size="2em" />
					<span>Working on it.</span>
				</div>
			</template>
		</q-table>
		<StoreOrUpdateDialog :row="dialog.row" :showDialog="dialog.showStoreOrUpdate"/>
	</q-page>
</template>

<script>
	import { defineAsyncComponent } from 'vue';
	import { Transition } from 'vue';

	const StoreOrUpdateDialog = defineAsyncComponent(() => import('./components/StoreOrUpdateDialog.vue'));
	export default {
    name: "RouletteIndex",
    data() {
        return {
			dialog: {
				showStoreOrUpdate: false,
				row: null,
			},
			fullColumns: ['number', 'name', 'election', 'price'],
			table: {
				columns: [
					{ name: "number", class: "text-weight-bold", label: "#", align: "left" },
					{ name: "name", align: "left", label: "Name", field: "winner", sortable: true },
					{ name: "election", align: "left", label: "Election", field: "election", sortable: true },
					{ name: "price", align: "left", label: "Price", field: "price", sortable: true },
				],
				filter: null,
				loading: false,
				paginationSettings: { rowsPerPage: 20 },
				rows: [],
			},
        };
    },
    methods: {
        executeLoadTableRows() {
			this.table.loading = true;
			this.table.rows = [];
			this.$http.get('api/raffle')
				.then(response => {
					this.table.rows = response.data;
					this.table.loading = false;
				})
				.catch(error => {
					this.$http.requestError(error);
					this.table.loading = false;
				})
		},
		onUpdate(row) {
			this.dialog.row = row;
			this.dialog.showStoreOrUpdate = true;
		},
		printWinner() {
			let table = document.getElementsByClassName('q-table')[0]
			let ids = []
			for (var i = 0, len = table.rows.length; i < len; i++) {
				if (i == 0) continue; // <thead>
				ids.push(table.rows[i].cells[0].children[0].innerHTML)
			}
			let baseUrl = 'http://192.168.1.118:8080/api/raffle/print/winners';
			let paramName = 'ids[]=';
			let arrayAsString = '?' + paramName + ids.join('&' + paramName);
			let urlWithParams = baseUrl + arrayAsString;
			window.location.href = urlWithParams;
		},
    },
    created() {
        this.executeLoadTableRows();
    },
    components: {
		Transition, StoreOrUpdateDialog
	}
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
