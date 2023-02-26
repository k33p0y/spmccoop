<template>
    <q-page class="flex flex-center background" v-on:keyup="triggerOnSubmit">
        <div v-if="verticalSignIn()" class="signin-horizontal q-px-lg q-pt-md q-pb-sm row">
            <div class="row" style="width: 600px;">
                <div class="col-12 text-white text-h6 q-mb-md" style="border-bottom: 2px solid grey;">Southern
                    Philippines Medical Center Cooperative</div>
            </div>
            <div class="flex flex-center q-mb-sm col-5">
                <!-- <img class alt="spmc_logo" id="spmc_logo" src="~assets/logos/spmc_logo_wt.svg" height="230" /> -->
            </div>
            <div class="text-subtitle1 text-white col-7">
                <div class="text-center">Sign In</div>
                <q-separator class="q-mt-xs q-mb-md" dark />
                <q-form>
                    <q-input v-model="form.username" autofocus class="q-mb-md" color="white" dark dense
                        :error="errors.username" :error-message="errors.usernameMessage" filled hide-bottom-space
                        input-class="username" placeholder="USERNAME" />
                    <q-input v-model="form.password" class="q-mb-md" color="white" dark dense :error="errors.password"
                        :error-message="errors.passwordMessage" filled hide-bottom-space input-class="password"
                        placeholder="PASSWORD" :type="viewPassword ? 'text' : 'password'">
                        <template v-slot:append>
                            <q-icon :name="
                                viewPassword ? 'visibility' : 'visibility_off'
                            " class="cursor-pointer" @click="viewPassword = !viewPassword" />
                        </template>
                    </q-input>
                    <div>
                        <q-btn @click="onSubmit()" color="teal" label="SIGN-IN" :loading="loading" no-caps />
                    </div>
                    <div class="flex flex-center q-mt-md">
                        <q-btn color="white" :disable="loading" flat label="FORGET PASSWORD" size="sm" no-caps />
                    </div>
                </q-form>
            </div>
        </div>
        <div v-else class="signin q-px-lg q-pt-md q-pb-sm">
            <div class="flex flex-center q-mb-sm">
                <!-- <img class alt="spmc_logo" id="spmc_logo" src="~assets/logos/spmc_logo_wt.svg" height="250" /> -->
            </div>
            <div class="text-h6 text-white text-center">Southern Philippines Medical Center Cooperative</div>
            <q-separator class="q-my-md" dark />
            <q-form>
                <q-input v-model="form.username" autofocus class="q-mb-md" color="white" dark dense
                    :error="errors.username" :error-message="errors.usernameMessage" filled hide-bottom-space
                    input-class="username" placeholder="USERNAME" />
                <q-input v-model="form.password" class="q-mb-md" color="white" dark dense :error="errors.password"
                    :error-message="errors.passwordMessage" filled hide-bottom-space input-class="password"
                    placeholder="PASSWORD" :type="viewPassword ? 'text' : 'password'">
                    <template v-slot:append>
                        <q-icon :name="
                            viewPassword ? 'visibility' : 'visibility_off'
                        " class="cursor-pointer" @click="viewPassword = !viewPassword" />
                    </template>
                </q-input>
                <div>
                    <q-btn @click="onSubmit()" color="teal" label="SIGN-IN" :loading="loading" no-caps />
                </div>
                <div class="flex flex-center q-mt-md">
                    <q-btn color="white" :disable="loading" flat label="FORGET PASSWORD" size="sm" no-caps />
                </div>
            </q-form>
        </div>
    </q-page>
</template>

<script>
import throwColorMixin from '../../mixins/throwColor';

export default {
    name: "SignIn",
    mixins: [throwColorMixin],
    data() {
        return {
            form: {
                username: null,
                password: null,
            },
            viewPassword: false,
            loading: false,
            errors: {},
            dialog: {
                showAuthUserChangePassword: false,
                username: null,
                id: null
            }
        };
    },
    methods: {
        params() {
            return {
                username: this.form.username,
                password: this.form.password,
            };
        },

        onSubmit() {
            this.errors = {};
            this.loading = true;
            this.$store.dispatch('oauth/signIn', this.params())
                .then(() => {
                    this.$router.replace({ path: "/" });
                })
                .catch((error) => {
                    this.throwColor("input", "input.username", "text-amber");
                    this.throwColor("input", "input.password", "text-amber");
                    this.errors = this.signInErrors(error);
                    this.loading = false;
                });
        },

        triggerOnSubmit(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                this.onSubmit();
            }
        },

        verticalSignIn() {
            var ratio = this.$q.screen.height / this.$q.screen.width;
            return (this.$q.screen.height < 600 && ratio < 0.7) ? true : false;
        },

        signInErrors(error) {
            console.log(error.response);
            console.log(error.response.data.message);
            let signInErrors = [];
            if (error == 'Error: Network Error') {
                signInErrors.username = true;
                signInErrors.usernameMessage = "Unable to connect to server.";
            } else {
                if (error.response.status === 401 || error.response.status === 422) {
                    var errors = [];
                    for (var x in error.response.data.errors) {
                        var errorMessage = '';
                        for (var y in error.response.data.errors[x]) {
                            errorMessage += error.response.data.errors[x][y] + ' ';
                        }
                        errors[x] = true;
                        errors[x + "Message"] = errorMessage;
                    }
                    signInErrors = errors;
                }
                else {
                    signInErrors.username = true;
                    signInErrors.usernameMessage = (error.response.data.message == undefined ? ('Status Code: ' + error.response.status) : error.response.data.message);
                }
            }
            return signInErrors;
        },
    },
};
</script>
<style lang="scss" scoped>
.signin {
    min-width: 320px;
    max-width: 400px;
    width: 50vw;
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 5px;
}

.background {
    // background-image: url("~assets/images/rainbow-spectrum-polygon.jpg");
    background-image: linear-gradient(to top, #09203f 0%, #004a84 100%);
    background-repeat: no-repeat;
    background-color: white;
    background-position: center;
    background-size: cover;
}

.signin-horizontal {
    width: 650px;
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 5px;
}
</style>