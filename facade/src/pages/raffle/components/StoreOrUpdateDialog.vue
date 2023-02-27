<template>
    <q-dialog v-model="visible" persistent transition-show="flip-down" transition-hide="flip-up">
        <q-card class="position-store-or-update">
            <q-toolbar class="q-pt-md q-pl-md q-pr-md">
                <q-toolbar-title>
                    <span class="text-weight-bold">
                        {{ row ? 'Update': 'Add' }} Price
                    </span>
                </q-toolbar-title>
                <q-btn @click="closeDialog()" flat round dense icon="close" />
            </q-toolbar>
            <q-card-section>
                <q-form class="q-gutter-sm" autocomplete="off">
                    <q-input v-model="form.price" autofocus color="teal-8" :error="errors.price" :error-message="errors.priceMessage" hide-bottom-space input-class="price" label="PRICE" outlined>
                        <template v-slot:label>
                            PRICE
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
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
                price: null
            },
            loading: false
        }
    },
    methods: {
        closeDialog() {
            this.$parent.$parent.$parent.dialog.showStoreOrUpdate = false;
            this.$parent.$parent.$parent.dialog.row = null;
            this.errors = {};
            this.form.price = null;
        },
        params() {
            var params = {
                price: this.form.price
            };
            if (this.row != null) {
                params.id = this.row.id;
            }
            return params;
        },
        // executeStore() {
        //     this.errors = {};
        //     this.loading = true;
        //     this.$http.post('api/election/position/store', this.params())
        //         .then(() => {
        //             this.$parent.$parent.$parent.executeLoadTableRows()
        //             this.closeDialog();
        //             this.loading = false;
        //         })
        //         .catch(error => {
        //             this.loading = false;
        //             this.errors = this.$http.requestError(error);
        //         });
        // },
        executeUpdate() {
            this.errors = {};
            this.loading = true;
            this.$http.patch('api/raffle/update', this.params())
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
                this.form.price = this.row.price;
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