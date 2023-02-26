<template>
    <q-layout view="lHh Lpr lFf">
        <q-header elevated>
            <q-toolbar>
                <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    SPMC Cooperative
                </q-toolbar-title>

                <div>Quasar v{{ $q.version }}</div>
                <q-btn-dropdown class="self-stretch" flat :label="username">
                    <q-list bordered id="q-list_avatar">
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

        <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
            <q-list>
                <q-item-label header>
                    Menu
                </q-item-label>

                <EssentialLink v-for="link in essentialLinks" :key="link.title" v-bind="link" />
            </q-list>
        </q-drawer>

        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import EssentialLink from 'components/EssentialLink.vue'

const linksList = [
    {
        title: 'Home',
        caption: 'quasar.dev',
        icon: 'school',
        link: 'https://quasar.dev'
    },
    {
        title: 'Election',
        caption: 'github.com/quasarframework',
        icon: 'code',
        link: 'https://github.com/quasarframework'
    },
    {
        title: 'Position',
        caption: 'chat.quasar.dev',
        icon: 'chat',
        link: 'https://chat.quasar.dev'
    },
    {
        title: 'Users',
        caption: 'forum.quasar.dev',
        icon: 'record_voice_over',
        link: 'https://forum.quasar.dev'
    }
]

export default defineComponent({
    name: 'MainLayout',

    components: {
        EssentialLink
    },
    data() {
        return {
            leftDrawerOpen: false,
            essentialLinks: linksList,
            notifCount: 0,
            ppmpCounter: 0,
            data: null,
            tree: []

        };
    },
    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        }
    }
})
</script>
