<template>
    <q-dialog v-model="visible" persistent transition-show="flip-down" transition-hide="flip-up">
        <q-card class="position-store-or-update">
            <q-toolbar class="q-pt-md q-pl-md q-pr-md">
                <q-toolbar-title>
                    <span class="text-weight-bold">
                        {{ row ? 'Update': 'Add' }} Election
                    </span>
                </q-toolbar-title>
                <q-btn @click="closeDialog()" flat round dense icon="close" />
            </q-toolbar>
            <q-card-section>
                <q-form class="q-gutter-sm" autocomplete="off">
                    <!-- <q-input v-model="form.name" autofocus color="teal-8" :error="errors.name" :error-message="errors.nameMessage" hide-bottom-space input-class="name" label="NAME" outlined>
                        <template v-slot:label>
                            NAME
                            <span class="text-negative">*</span>
                        </template>
                    </q-input> -->
                    <q-select v-model="form.candidate" color="blue-grey-8" emit-value :error="errors.candidate"
                        :error-message="errors.candidateMessage" @filter="filterCandidate" hide-bottom-space
                        input-class="candidate" label="CANDIDATE" map-options outlined :options="options.candidate" use-input>
                        <template v-slot:label>
                            CANDIDATE
                            <span class="required-mark">*</span>
                        </template>
                        <template v-slot:no-option>
                            <q-item>
                                <q-item-section class="text-grey">No results</q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                    <q-select v-model="form.position" color="blue-grey-8" emit-value :error="errors.position"
                        :error-message="errors.positionMessage" @filter="filterPosition" hide-bottom-space
                        input-class="position" label="position" map-options outlined :options="options.position" use-input>
                        <template v-slot:label>
                            POSITION
                            <span class="required-mark">*</span>
                        </template>
                        <template v-slot:no-option>
                            <q-item>
                                <q-item-section class="text-grey">No results</q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                </q-form>
            </q-card-section>
            <q-card-actions class="q-pl-md q-pr-md q-pb-md q-pt-none q-gutter-sm" align="right">
                <q-btn v-if="row == null" class="default-button" @click="executeStore()" color="primary" icon="save" unelevated :loading="loading">
                    <template v-slot:default>
                        <span class="q-mx-xs gt-xs">Save</span>
                    </template>
                </q-btn>
                <q-btn v-else class="default-button" @click="executeUpdate()" color="primary" icon="edit" unelevated :loading="loading">
                    <template v-slot:default>
                        <span class="q-mx-xs gt-xs">Update</span>
                    </template>
                </q-btn>
                <q-btn class="default-button" @click="closeDialog()" color="blue-grey-8" icon="close" text-color="white" unelevated>
                    <template v-slot:default>
                        <span class="q-mx-xs gt-xs">Close</span>
                    </template>
                </q-btn>
            </q-card-actions>

        </q-card>
    </q-dialog>
</template>
<script>
export default {
    name: "PositionStoreOrUpdate",
    props: ['showDialog', 'row', 'candidateCache', 'positionCache'],
    data() {
        return {
            errors: {},
            visible: false,
            form: {
                candidate: null,
                position: null
            },
            loading: false,
            options: {
                candidateCache: [],
                candidate: [],
                positionCache: [],
                position: []
            }
        }
    },
    methods: {
        closeDialog() {
            this.$parent.$parent.$parent.dialog.showStoreOrUpdate = false;
            this.$parent.$parent.$parent.dialog.row = null;
            this.errors = {};
            this.form.candidate = null;
            this.form.position = null;
        },
        params() {
            var params = {
                candidate_user_id: this.form.candidate,
                position_id: this.form.position
            };
            if (this.row != null) {
                params.id = this.row.id;
            }
            return params;
        },
        executeStore() {
            this.errors = {};
            this.loading = true;
            this.$http.post('api/election-detail/store', this.params())
                .then(() => {
                    this.$parent.$parent.$parent.executeLoadTableRows()
                    this.closeDialog();
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = this.$http.requestError(error);
                });
        },
        executeUpdate() {
            this.errors = {};
            this.loading = true;
            this.$http.patch('api/election-detail/update', this.params())
                .then(() => {
                    this.$parent.$parent.$parent.executeLoadTableRows()
                    this.closeDialog();
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = this.$http.requestError(error);
                });
        },
        filterCandidate(val, update) {
            update(() => { this.options.candidate = (this.options.candidateCache).filter(v => v.label.toLowerCase().indexOf(val.toLowerCase()) > -1) })
        },
        filterPosition(val, update) {
            update(() => { this.options.position = (this.options.positionCache).filter(v => v.label.toLowerCase().indexOf(val.toLowerCase()) > -1) })
        }
    },
    watch: {
        showDialog() {
            this.visible = this.showDialog;
            if(this.showDialog) {
                this.options.candidateCache = this.candidateCache;
                this.options.candidate = this.candidateCache;
                this.options.positionCache = this.positionCache;
                this.options.position = this.positionCache;
                if (this.row) {
                    this.form.candidate = this.row.candidate_user_id;
                    this.form.position = this.row.position_id;
                }
            }
        }
    },
};
</script>
<style lang="scss" scoped>
.position-store-or-update {
    width: 400px
}
</style>