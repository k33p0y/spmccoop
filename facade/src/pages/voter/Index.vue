<template>
    <q-page padding>
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Home" icon="home" to="/" />
            <q-breadcrumbs-el label="Voters" icon="group" />
        </q-breadcrumbs>
        <p class="text-h4 q-my-md">Voters</p>
        <q-table id="main-table" :rows="table.rows" :columns="table.columns" :filter="table.filter" :pagination="table.paginationSettings" :loading="table.loading" row-key="id" :visibleColumns="fullColumns">
            <template v-slot:top>
                <!-- <div>
                    <q-btn class="q-mb-xs q-mr-xs" @click="onStore()" color="blue-grey-8" icon="add" size="md" unelevated>
                        <template v-slot:default>
                            <span class="q-mx-xs gt-xs">Register Member</span>
                        </template>
                    </q-btn>
                </div>
                <q-space /> -->
                <div>
                    <q-input class="q-mb-xs" borderless dense filled debounce="300" bg-color="blue-grey-1" color="blue-grey" placeholder="Search" v-model="table.filter">
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
                    <q-td class="table-number-field" key="number" :props="props">{{ props.rowIndex + 1 }}</q-td>
                    <q-td key="name" :props="props"><span @click="onUpdate(props.row)" class="table-primary-field">
                            {{ props.row.name }}
                        </span></q-td>
                    <q-td key="voted" :props="props">
                        <span :class="props.row.voted == 1 ? 'text-green' : 'text-amber'" >&#11044;</span>
                        {{ props.row.voted == 1 ? 'Yes' : 'No'}}</q-td>
                </q-tr>
            </template>
            <template v-if="table.loading" v-slot:no-data>
                <div class="full-width row flex-center q-gutter-sm">
                    <q-spinner-gears color="primary" size="2em" />
                    <span>Working on it.</span>
                </div>
            </template>
        </q-table>
        <StoreOrUpdateDialog :row="dialog.row" :showDialog="dialog.showStoreOrUpdate" />
    </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';

const StoreOrUpdateDialog = defineAsyncComponent(() => import('./components/StoreOrUpdateDialog.vue'));

export default {
    name: 'PositionIndex',
    data() {
        return {
            dialog: {
                showStoreOrUpdate: false,
                row: null,
            },
            fullColumns: ['number', 'name', 'voted'],
            table: {
                columns: [
                    { name: "number", class: "text-weight-bold", label: "#", align: "left" },
                    { name: "name", align: "left", label: "Name", field: "name", sortable: true },
                    { name: "voted", align: "left", label: "Voted", field: "voted", sortable: true }
                ],
                filter: null,
                loading: false,
                paginationSettings: { rowsPerPage: 20 },
                rows: [],
            },
        };
    },
    methods: {
        onStore() {
            this.dialog.row = null;
            this.dialog.showStoreOrUpdate = true;
        },

        onUpdate(row) {
            this.dialog.row = row;
            this.dialog.showStoreOrUpdate = true;
        },
        executeLoadTableRows() {
            this.table.loading = true;
            this.table.rows = [];
            this.$http.get('api/election/vote/voter-list')
                .then(response => {
                    this.table.rows = response.data;
                    this.table.loading = false;
                })
                .catch(error => {
                    this.$http.requestError(error);
                    this.table.loading = false;
                })
        }
    },
    created() {
        this.executeLoadTableRows();
    },
    components: {
        StoreOrUpdateDialog
    }
};
</script>