<template>
    <q-layout view="hHh Lpr lFf" class="bg-white text-blue-grey-10" id="spmc">
        <q-header v-if="$q.screen.height > 599" class="bg-grey-10 text-white">
            <q-toolbar>
                <q-btn flat dense round @click="leftDrawerOpen = !leftDrawerOpen" aria-label="Menu" icon="menu" />
                <q-toolbar-title @click="playNotificationSound()">SPMC</q-toolbar-title>

                <q-btn round class="q-mr-sm" icon="notifications">
                    <q-badge class="q-mt-sm q-py-xs" color="red-7" floating v-if="notifCount > 0">{{
                        notifCount
                    }}</q-badge>
                    <q-menu>
                        <q-list>
                            <q-item clickable v-close-popup>
                                <q-item-section>
                                    PPMP:
                                </q-item-section>
                                <q-item-section side>
                                    <q-badge color="warning">{{ ppmpCounter }}</q-badge>
                                </q-item-section>
                            </q-item>
                            <!-- <q-item
                              clickable
                              v-close-popup
                          >
                              <q-item-section>
                                  Purchase
                                  <br />
                                  Request:
                              </q-item-section>
                              <q-item-section side>
                                  <q-badge color="warning">0</q-badge>
                              </q-item-section>
                          </q-item> -->
                            <!-- <q-item
                              clickable
                              v-close-popup
                          >
                              <q-item-section>
                                  CAF:
                              </q-item-section>
                              <q-item-section side>
                                  <q-badge color="warning">0</q-badge>
                              </q-item-section>
                          </q-item> -->
                        </q-list>
                    </q-menu>
                </q-btn>
                <q-btn-dropdown class="self-stretch" flat :label="username">
                    <q-list bordered id="q-list_avatar">
                        <!-- <q-item clickable v-close-popup>
                            <q-item-section avatar>
                                <q-icon color="teal-9" name="settings"></q-icon>
                            </q-item-section>
                            <q-item-section>Settings</q-item-section>
                        </q-item> -->
                        <q-item clickable v-close-popup to="/profile">
                            <q-item-section avatar>
                                <q-icon color="teal-9" name="account_circle" />
                            </q-item-section>
                            <q-item-section>Profile</q-item-section>
                        </q-item>
                        <q-item clickable @click="signOut()">
                            <q-item-section avatar>
                                <q-icon color="teal-9" name="exit_to_app" />
                            </q-item-section>
                            <q-item-section>Sign-Out</q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
            </q-toolbar>
        </q-header>

        <q-drawer v-model="leftDrawerOpen" show-if-above class="bg-grey-10" id="side-menu" :width="350">
            <q-scroll-area :thumb-style="menuScrollbar" class="fit bg-grey-10">
                <q-list dark>
                    <SideMenu v-for="child in tree" :key="child.id" :node="child" />
                </q-list>
            </q-scroll-area>
        </q-drawer>

        <q-page-container>
            <q-btn-group rounded class="min-profile">
                <q-btn v-if="$q.screen.height < 599" @click="leftDrawerOpen = !leftDrawerOpen" class="min-hamburger"
                    color="blue-grey-10" dense icon="menu" rounded />
                <q-btn-dropdown v-if="$q.screen.height < 599" color="blue-grey-10" rounded icon="person">
                    <template v-slot:label>
                        <span style="margin-top: 3px; margin-left: 5px;">{{ username }}</span>
                    </template>
                    <q-list bordered id="q-list_avatar">
                        <!-- <q-item clickable v-close-popup>
                            <q-item-section avatar>
                                <q-icon color="teal-9" name="settings"></q-icon>
                            </q-item-section>
                            <q-item-section>Settings</q-item-section>
                        </q-item> -->
                        <q-item clickable v-close-popup to="/profile">
                            <q-item-section avatar>
                                <q-icon color="teal-9" name="account_circle" />
                            </q-item-section>
                            <q-item-section>Profile</q-item-section>
                        </q-item>
                        <q-item clickable @click="signOut()">
                            <q-item-section avatar>
                                <q-icon color="teal-9" name="exit_to_app" />
                            </q-item-section>
                            <q-item-section>Sign-Out</q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
            </q-btn-group>
            <router-view :data="data" class="text-blue-grey-10" />
        </q-page-container>

        <q-footer :class="$q.screen.height < 599 ? 'invisible' : 'bg-grey-10 text-white'">
            <q-toolbar>
                <q-toolbar-title>
                    <div class="text-right text-subtitle2">Hifex</div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>
        <!-- <ErrorDialog />
        <InfoDialog /> -->
    </q-layout>
</template>

<script>
import { defineAsyncComponent } from 'vue';

const SideMenu = defineAsyncComponent(() => import('./components/SideMenu.vue'));
// const ErrorDialog = defineAsyncComponent(() => import('../../components/global/ErrorDialog.vue'));
// const InfoDialog = defineAsyncComponent(() => import('../../components/global/InfoDialog.vue'));

export default {
    name: "MasterLayout",
    data() {
        return {
            leftDrawerOpen: false,
            username: this.$store.state.oauth.user.username,
            notifCount: 0,
            ppmpCounter: 0,
            data: null,
            tree: [
                {
                    "id": 1,
                    "label": "HOME",
                    "parent_id": 0,
                    "order": 1,
                    "navigation_path": '/',
                    "masked_navigation_path": '/',
                    "icon": 'home',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 2,
                    "label": "ELECTIONS",
                    "parent_id": 0,
                    "order": 2,
                    "navigation_path": '/election',
                    "masked_navigation_path": '/election',
                    "icon": 'how_to_vote',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 3,
                    "label": "ELECTIONS DETAILS",
                    "parent_id": 0,
                    "order": 3,
                    "navigation_path": '/election-detail',
                    "masked_navigation_path": '/election-detail',
                    "icon": 'ballot',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 4,
                    "label": "POSITIONS",
                    "parent_id": 0,
                    "order": 4,
                    "navigation_path": '/position',
                    "masked_navigation_path": '/position',
                    "icon": 'account_circle',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 5,
                    "label": "MEMBERS",
                    "parent_id": 0,
                    "order": 5,
                    "navigation_path": '/member',
                    "masked_navigation_path": '/member',
                    "icon": 'person',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 6,
                    "label": "VOTERS",
                    "parent_id": 0,
                    "order": 6,
                    "navigation_path": '/voters',
                    "masked_navigation_path": '/voters',
                    "icon": 'group',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 7,
                    "label": "RAFFLE",
                    "parent_id": 0,
                    "order": 7,
                    "navigation_path": '/raffle-winners',
                    "masked_navigation_path": '/raffle-winners',
                    "icon": 'casino',
                    "level": 0,
                    "permissions": 1,
                    "endpoint": 1
                },
                {
                    "id": 8,
                    "label": "PLAY",
                    "parent_id": 7,
                    "order": 8,
                    "navigation_path": '/raffle',
                    "masked_navigation_path": '/raffle',
                    "icon": 'casino',
                    "level": 1,
                    "permissions": 1,
                    "endpoint": 1
                }
            ]

        };
    },
    mounted() {
        // this.$echo.private('mainstream')
        //     .listen("Websocket\\Core\\Administration\\Security\\Role\\ChangeRolePermission", (e) => {
        //         if (this.$store.state.oauth.role.includes(e.id)) {
        //             this.$store.dispatch('oauth/reValidateCredentials', { id: this.$store.state.oauth.user.id });
        //         }
        //     })
        // this.$echo.private('mainstream')
        //     .listen("Websocket\\SVCC\\Supply\\Procurement\\Ppmp\\ForApproval", (e) => {
        //         this.getNotification();
        //     })
        // this.getNotification();
    },
    methods: {
        // getNotifPPMP() {
        //     this.ppmpCounter = 0;
        //     console.log(this.data, 'data')
        //     if (this.data.length > 0) {
        //         console.log(this.data.length, 'data.length')
        //         this.data.forEach(v => {
        //             console.log(v, 'v');
        //             if (v.table == "PPMPS") {
        //                 this.ppmpCounter++;
        //             }
        //         });
        //     }
        //     else {
        //         this.ppmpCounter = 0;
        //     }
        // },
        signOut() { this.$store.dispatch('oauth/signOut'); },
        // getNotification() {
        //     this.$http.get('api/domain/svcc/supply/procurement/project-procurement-management-plans/notification', { user_id: this.$store.state.oauth.user.id })
        //         .then(response => {
        //             this.data = response.data.data;
        //             this.notifCount = response.data.data.length;
        //             this.getNotifPPMP(this.data);
        //         })
        //         .catch(error => {
        //             this.$http.requestError(error);
        //         });
        // },
        // playNotificationSound() {
        //     const audio = new Audio("audio/notification.mp3");
        //     audio.play();
        // }
    },
    computed: {
        menuScrollbar() {
            return {
                right: "2px",
                borderRadius: "5px",
                backgroundColor: "#fff",
                width: "5px",
                opacity: 0.75,
            };
        }
    },
    created() {
        // this.$http.get('/api/domain/core/developer/side-navigation-menus/tree')
        //     .then(response => {
        //         this.tree = response.data.tree;

        //     })
        //     .catch(error => {
        //         this.$http.requestError(error);
        //     });
    },
    components: {
        SideMenu,
        // ErrorDialog,
        // InfoDialog,
        // InfoDialog
    },
    watch: {
        // notifCount: function (newVal, oldVal) {
        //     if (oldVal < newVal) { this.playNotificationSound(); }
        // },
        // $route(to, from) {
        //     if (to.path == '/') {
        //         this.getNotification()
        //     }
        // }
    },
    beforeUnmount() {
        // this.$echo.leave('mainstream');
    },
};
</script>
<style lang="scss">
.min-hamburger {
    width: 60px;
}

.min-profile {
    top: 5px;
    right: 5px;
    position: fixed;
    z-index: 500;
}
</style>