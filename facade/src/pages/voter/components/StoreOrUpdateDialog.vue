<template>
    <q-dialog v-model="visible" persistent transition-show="flip-down" transition-hide="flip-up">
        <q-card class="member-store-or-update">
            <q-toolbar class="q-pt-md q-pl-md q-pr-md">
                <q-toolbar-title>
                    <span class="text-weight-bold">
                        Print Receipt
                    </span>
                </q-toolbar-title>
                <q-btn @click="closeDialog()" flat round dense icon="close" />
            </q-toolbar>
            <q-card-section>
                <!-- <q-form class="q-gutter-sm" autocomplete="off">
                    <q-input v-model="form.username" autofocus color="teal-8" :error="errors.username" :error-message="errors.usernameMessage" hide-bottom-space input-class="username" label="NAME" outlined>
                        <template v-slot:label>
                            USERNAME
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <q-input v-model="form.name" color="teal-8" :error="errors.name" :error-message="errors.nameMessage" hide-bottom-space input-class="name" label="NAME" outlined>
                        <template v-slot:label>
                            NAME
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <q-input v-model="form.employeeId" color="teal-8" :error="errors.employeeId" :error-message="errors.employeeIdMessage" hide-bottom-space input-class="employee-id" label="EMPLOYEE ID" outlined>
                        <template v-slot:label>
                            EMPLOYEE ID
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <q-input v-model="form.email" color="teal-8" :error="errors.email" :error-message="errors.emailMessage" hide-bottom-space input-class="email" label="EMAIL" outlined>
                        <template v-slot:label>
                            EMAIL
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <q-input v-if="row == null" v-model="form.password" color="teal-8" :error="errors.password" :error-message="errors.passwordMessage" hide-bottom-space input-class="password" label="PASSWORD" outlined>
                        <template v-slot:label>
                            PASSWORD
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <q-input v-if="row == null" v-model="form.passwordConfirmation" color="teal-8" :error="errors.passwordConfirmation" :error-message="errors.passwordConfirmationMessage" hide-bottom-space input-class="password-confirmation" label="CONFIRM PASSWORD" outlined>
                        <template v-slot:label>
                            CONFIRM PASSWORD
                            <span class="text-negative">*</span>
                        </template>
                    </q-input>
                    <div class="q-mt-sm">
                        <div><q-checkbox keep-color v-model="form.status" color="teal" label="ACTIVE" :true-value="1" :false-value="0" dense/></div>
                        <div><q-checkbox keep-color v-model="form.admin" color="teal" label="ADMIN" :true-value="1" :false-value="0" dense/></div>
                        <div><q-checkbox keep-color v-model="form.candidate" color="teal" label="CANDIDATE" :true-value="1" :false-value="0" dense/></div>
                    </div>
                </q-form> -->
            </q-card-section>
            <q-card-actions class="q-pl-md q-pr-md q-pb-md q-pt-none q-gutter-sm" align="right">
                <!-- <q-btn v-if="row == null" class="default-button" @click="executeStore()" color="primary" icon="save" unelevated :loading="loading">
                    <template v-slot:default>
                        <span class="q-mx-xs gt-xs">Save</span>
                    </template>
                </q-btn> -->
                <q-btn class="default-button" :href="printIndividualVotes(row.id)" color="primary" icon="print" unelevated :loading="loading">
                    <template v-slot:default>
                        <span class="q-mx-xs gt-xs">Print</span>
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
    name: "MemberStoreOrUpdate",
    props: ['showDialog', 'row'],
    data() {
        return {
            errors: {},
            visible: false,
            form: {
                username: null,
                name: null,
                email: null,
                employeeId: null,
                password: null,
                passwordConfirmation: null,
                status: 0,
                admin: 0,
                candidate: 0
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
            this.form.username
            this.form.email = null;
            this.form.employeeId = null;
            this.form.status = 0;
            this.form.admin = 0;
            this.form.candidat = 0;
            this.row.id = null;
            this.form.password = null;
            this.form.passwordConfirmation = null;
            this.$parent.$parent.$parent.executeLoadTableRows()
        },
        params() {
            var params = {
                name: this.form.name,
                username: this.form.username,
                email: this.form.email,
                employee_id: this.form.employeeId,
                status: this.form.status,
                admin: this.form.admin,
                candidate: this.form.candidate,
            };
            if(this.row != null) {
                params.id = this.row.id;
            } else {
                params.password = this.form.password;
                params.password_confirmation = this.form.passwordConfirmation;
            }
            return params;
        },
        // executeStore() {
        //     this.errors = {};
        //     this.loading = true;
        //     this.$http.post('api/member/store', this.params())
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
        // executeUpdate() {
        //     this.errors = {};
        //     this.loading = true;
        //     this.$http.patch('api/member/update', this.params())
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
        printIndividualVotes(id) {
            return `http://192.168.1.118:8080/api/election/vote/${id}/print/votes/`;
        }
    },
    watch: {
        showDialog() {
            this.visible = this.showDialog;
            if (this.row) {
                this.form.name = this.row.name;
                this.form.username = this.row.username;
                this.form.email = this.row.email;
                this.form.employeeId = this.row.employee_id;
                this.form.status = this.row.status;
                this.form.admin = this.row.admin;
                this.form.candidate = this.row.candidate;
            }
        }
    },
};
</script>
<style lang="scss" scoped>
.member-store-or-update {
    width: 400px
}
</style>
