import { Cookies } from 'quasar';

export default ({ router, store, Vue }) => {
    router.beforeEach((to, from, next) => {
        if(Cookies.has('user')) {
            let user = Cookies.get('user');
            store.commit('oauth/user', user);
            if(to.path == '/oauth/signin') {
              store.commit('oauth/user', user);
              router.replace('/');
            } else {
                next();
            }
        } else {
            if(to.path == '/oauth/signin' || to.path == '/voter') {
                next();
            } else {
                router.replace('/voter');
            }
        }
    })
}