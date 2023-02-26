<template>
    <div>
        <q-expansion-item v-if="node.endpoint == 0 && nodePermissions" v-model="expanded"
            :dense-toggle="node.level > 0 ? true : false" :group="node.group" :header-inset-level="(node.level * 0.5)"
            :icon="node.icon" :key="node.id" :label="node.label" class="side-navigation-menu-recursive"
            :header-style="expansionStyles(node.navigation_path, node.level)">
            <SideMenu v-for="child in node.children" :key="child.label" :node="child" />
        </q-expansion-item>
        <q-item v-if="node.endpoint == 1 && nodePermissions" v-ripple:white active-class="my-menu-link" :exact="false"
            clickable class="side-navigation-menu-base" :inset-level="(node.level * 0.5)" :key="node.id"
            :to="node.navigation_path" :style="itemStyles(node.navigation_path, node.level)">
            <q-item-section avatar>
                <q-icon :name="node.icon" />
            </q-item-section>
            <q-item-section>{{ node.label }}</q-item-section>
        </q-item>
    </div>
</template>

<script>
export default {
    name: "SideMenu",
    props: {
        node: Object
    },
    data() {
        return {
            sideMenu: [],
            expanded: this.expandItem(this.node.navigation_path, this.node.level),
            nodePermissions: true
        }
    },
    methods: {
        expansionStyles(navigation_path, level) {
            if (((this.$route.path).includes(navigation_path)) && (level == 0)) {
                return {
                    backgroundColor: '#cc0000',
                    marginBottom: '4px'
                }
            } else if (((this.$route.path).includes(navigation_path)) && (level > 0)) {
                return {
                    backgroundColor: '#3d3d4d',
                    marginBottom: '4px',
                }
            } else {
                return { marginBottom: '4px' }
            }
        },
        itemStyles(navigation_path, level) {
            if (this.$route.path == navigation_path && (level == 0)) {
                return "background-color: #cc0000; color: white; margin-bottom: 4px; ";
            } else if (((this.$route.path).includes(navigation_path)) && (level > 0)) {
                return "background-color: #535353; margin-bottom: 4px; color: white; font-weight: italic;";
            } else {
                return "margin-bottom: 4px;";
            }
        },
        expandItem(navigation_path, level) {
            if (((this.$route.path).includes(navigation_path)) && (level == 0)) {
                return true;
            } else if (((this.$route.path).includes(navigation_path)) && (level > 0)) {
                return true;
            } else {
                return false;
            }
        }
    },
    watch: {
        $route(to, from) {
            if (to == '/') {
                this.expanded = false
            } else {
                this.expanded = this.expandItem(this.node.navigation_path, this.node.level);
            }
        },
        // '$store.state.sanctum.permission'() {
        //     this.nodePermissions = this.$permission(this.node.permissions);
        // }
    }
};
</script>