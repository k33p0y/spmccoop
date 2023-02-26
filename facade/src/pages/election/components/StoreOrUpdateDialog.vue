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
                    <q-input v-model="form.name" autofocus color="teal-8" :error="errors.name" :error-message="errors.nameMessage" hide-bottom-space input-class="name" label="NAME" outlined>
                        <template v-slot:label>
                            NAME
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <div class="q-mt-sm">
                        <div><q-checkbox keep-color v-model="form.status" color="teal" label="ACTIVE" :true-value="1" :false-value="0" dense/></div>
                    </div>
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
    props: ['showDialog', 'row'],
    data() {
        return {
            errors: {},
            visible: false,
            form: {
                name: null,
                status: 0
            },
            loading: false
        }
    },
    methods: {
        closeDialog() {
            this.$parent.$parent.$parent.dialog.showStoreOrUpdate = false;
            this.$parent.$parent.$parent.dialog.row = null;
            this.errors = {};
            this.form.name = null;
            this.form.status = 0;
        },
        params() {
            var params = {
                name: this.form.name,
                status: this.form.status
            };
            if (this.row != null) {
                params.id = this.row.id;
            }
            return params;
        },
        executeStore() {
            this.errors = {};
            this.loading = true;
            this.$http.post('api/election/store', this.params())
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
            this.$http.patch('api/election/update', this.params())
                .then(() => {
                    this.$parent.$parent.$parent.executeLoadTableRows()
                    this.closeDialog();
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = this.$http.requestError(error);
                });
        }
    },
    watch: {
        showDialog() {
            this.visible = this.showDialog;
            if (this.row) {
                this.form.name = this.row.name;
                this.form.status = this.row.status;
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