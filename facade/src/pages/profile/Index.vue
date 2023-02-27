<template>
    <q-page padding>
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Home" icon="home" to="/" />
            <q-breadcrumbs-el label="Profile" icon="account_circle" />
        </q-breadcrumbs>
        <p class="text-h4 q-my-md">Profile</p>
        <fieldset>
            <legend>Change Password</legend>
            <q-input v-model="form.currentPassword" class="q-mb-md" color="teal" :error="errors.currentPassword" :error-message="errors.currentPasswordMessage" filled hide-bottom-space input-class="password" placeholder="NEW PASSWORD" :type="viewnCurrentPassword ? 'text' : 'password'">
                <template v-slot:append>
                    <q-icon :name="viewnCurrentPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer" @click="viewnCurrentPassword = !viewnCurrentPassword" />
                </template>
            </q-input>
            <q-input v-model="form.password" class="q-mb-md" color="teal" :error="errors.password" :error-message="errors.passwordMessage" filled hide-bottom-space input-class="password" placeholder="CURRENT PASSWORD" :type="viewPassword ? 'text' : 'password'">
                <template v-slot:append>
                    <q-icon :name="viewPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer" @click="viewPassword = !viewPassword" />
                </template>
            </q-input>
            <q-input v-model="form.passwordConfirmation" class="q-mb-md" color="teal" :error="errors.passwordConfirmation" :error-message="errors.passwordConfirmationMessage" filled hide-bottom-space input-class="password" placeholder="CONFIRM PASSWORD" :type="viewnPasswordConfirmation ? 'text' : 'password'">
                <template v-slot:append>
                    <q-icon :name="viewnPasswordConfirmation ? 'visibility' : 'visibility_off'" class="cursor-pointer" @click="viewnPasswordConfirmation = !viewnPasswordConfirmation" />
                </template>
            </q-input>
        </fieldset>
        <br />
        <q-btn label="CHANGE PASSWORD" unelevated color="teal" :loading="loading" @click="executeChangePassword()" />
        <div class="flex flex-center text-center vertical-middle drop-profile-zone q-mt-xl" style="width: 305px; height: 305px; padding: 2px;">
            <div v-if="profileBase64 == null">
                <q-icon name="cloud_upload" color="teal" size="4.4em" /><br />
                <div id="drop-profile-placeholder" class="text-h6 text-blue-grey">Click or drop profile file here to upload </div>
            </div>
            <div v-else>
                <img :src="profileBase64" alt="profile-ppicture" style="max-width: 300px; max-height: 300px;"/>
            </div>
            <input @change="loadProfile" id="drop-profile-input" name="profile" type="file" class="full-widht full-height" accept="image/jpg, image/jpeg, image/png">
        </div>
        <br />
        <q-btn label="Update Profile Picture" unelevated color="teal" :loading="profilePictureLoading" @click="executeUpdateProfilePicture()" />
    </q-page>
</template>

<script>

export default {
    name: 'PositionIndex',
    data() {
        return {
            form: {
                currentPassword: null,
                password: null,
                passwordConfirmation: null,
                profilePicture: null
            },
            profileBase64: null,
            loading: false,
            profilePictureLoading: false,
            viewnCurrentPassword: false,
            viewPassword: false,
            viewnPasswordConfirmation: false,
            errors: {}
        };
    },
    methods: {
        executeChangePassword() {
            this.loading = true;
            this.$http.post('api/profile/change-password', {
                current_password: this.form.currentPassword,
                password: this.form.password,
                password_confirmation: this.form.passwordConfirmation

            })
                .then(response => {
                    this.form.currentPassword = null;
                    this.form.password = null;
                    this.form.passwordConfirmation = null;
                    alert("Password changed successfully.")
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = this.$http.requestError(error);
                    this.loading = false;
                })
        },
        loadProfile(e) {
            let supportedFileTypes = ['image/jpg', 'image/jpeg', 'image/png'];

            this.form.profilePicture = e.target.files[0];
            let fileType = this.form.profilePicture.type;

            if(supportedFileTypes.includes(fileType)) {
                let fileReader = new FileReader();
                fileReader.onload = () => {
                    this.profileBase64 = fileReader.result;
                    // console.log(this.form.profileBase64);
                }
                fileReader.readAsDataURL(this.form.profilePicture);
            }
        },
        executeUpdateProfilePicture() {
            if(this.form.profilePicture != null) {
                this.profilePictureLoading = true;
                let data = new FormData();
                data.append('profile_picture', this.form.profilePicture == null ? '' : this.form.profilePicture);

                this.$http.postFile('api/profile/update-picture', data)
                .then((response) => {
                    this.profilePictureLoading = false;
                    alert("Profile picture saved successfully.");
                })
                .catch((error) => {
                    this.profilePictureLoading = false;
                    this.errors = this.$http.requestError(error);
                });
            }
        },
        loadProfileBase64() {
            this.$http.get('api/profile/profile-picture')
                .then((response) => {
                    // console.log(response);
                    this.profileBase64 = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });



            // profile_picture
            // profileBase64
        }
    },
    created() {
        this.loadProfileBase64();
    }
};
</script>
<style lang="scss" scoped>
.drop-profile-zone {
    height: 415px;
    border: 2px dashed grey;
    position: relative;
}

.drop-profile-zone-error {
    height: 415px;
    border: 3px dashed #C10015;
    position: relative;
}

#remove-profile {
    position: absolute;
    top: 5px;
    right: 5px;
}

#drop-profile-input {
    opacity: 0;
    width: 300px !important;
    height: 300px !important;
    cursor: pointer;
    position: absolute;
}

#drop-profile-placeholder {
    padding-left: 20px;
    padding-right: 20px;

}
</style>