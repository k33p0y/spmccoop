import { api } from 'boot/axios';
import { Cookies } from 'quasar';
import { http } from 'boot/httpRequest';

export function signIn(context, data) {
    return api.post('/api/oauth/signin', {
        'username': data.username,
        'password': data.password
    })
    .then(token => {
        return api.get('/api/user', {
            headers: { Authorization: token.data.token_type + " " + token.data.access_token }
        })
        .then(user => {
            return context.dispatch('credentials', {
                user: user.data,
                token: token.data,
            })
        });
    });
}

export function credentials(context, data) {
    context.commit('user', data.user)
    Cookies.set('user', data.user, { path: '/', sameSite: 'Lax' });
    context.commit('token', data.token)
    Cookies.set('token', data.token, { path: '/', sameSite: 'Lax' });
}

export function signOut({ commit }) {
    Cookies.remove('user', { sameSite: 'Lax' });
    Cookies.remove('token', { sameSite: 'Lax' });
    commit('token', null);
    commit('user', null);
    this.$router.push({ path: '/voter'});
}

export function recoverCredentials(context) {
    if (
        Cookies.has('user') &&
        Cookies.has('token')
    ) {

        var process = new Promise(function (resolve, reject) {
            const user = Cookies.get('user');
            const token = Cookies.get('token');
            resolve({
                "user": user,
                "token": token
            });
        })

        process.then(response => {
            context.commit('user', response['user']);
            context.commit('token', response['token']);
        });
    } else {
        context.dispatch('removeCredentials');
    }
}

export function removeCredentials(context) {
    Cookies.remove('user', { path: '/', sameSite: 'Lax'});
    context.commit('user', null);
    Cookies.remove('token', { path: '/', sameSite: 'Lax'});
    context.commit('token', null);
}