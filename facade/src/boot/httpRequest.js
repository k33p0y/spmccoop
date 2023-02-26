import { api } from 'boot/axios';

const http = {
    get(url, params) {
        return api.get(url, {
            params: params,
            headers: {
                'Authorization': storeHttp.state.oauth.token.token_type + ' ' + storeHttp.state.oauth.token.access_token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
    },

    post(url, params) {
        return api.post(
            url,
            params,
            {
                headers: {
                    'Authorization': storeHttp.state.oauth.token.token_type + ' ' + storeHttp.state.oauth.token.access_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }
        )
    },

    postTokenless(url, params) {
        return api.post(
            url,
            params
        )
    },

    getTokenless(url, params) {
        return api.get(
            url,
            params
        )
    },

    postFile(url, params) {
        return api.post(
            url,
            params,
            {
                headers: {
                    'Authorization': storeHttp.state.oauth.token.token_type + ' ' + storeHttp.state.oauth.token.access_token,
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            }
        )
    },

    patch(url, params) {
        return api.patch(
            url,
            params,
            {
                headers: {
                    'Authorization': storeHttp.state.oauth.token.token_type + ' ' + storeHttp.state.oauth.token.access_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }
        )
    },

    delete(url, params) {
        return api.delete(
            url,
            {
                headers: {
                    'Authorization': storeHttp.state.oauth.token.token_type + ' ' + storeHttp.state.oauth.token.access_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: params
            }
        )
    },

    requestError(error) {
        console.log(error);
        let inputErrors = [];
        if (error == 'Error: Network Error') {
            var errorResponse = [];
            errorResponse.status = 502;
            errorResponse.statusText = 'Bad Gateway';
            errorResponse.data = [];
            errorResponse.data.message = "Unable to connect to server.";
            errorResponse.config = [];
            errorResponse.config.url = null;
            // storeHttp.commit('global/showErrorDialog', {
            //     display: true,
            //     errorDetail: errorResponse
            // })
            console.log();
        } else {
            if (error.response.status === 422) {
                console.log('louie');
                var errors = [];
                for (var x in error.response.data.errors) {
                    var errorMessage = '';
                    for (var y in error.response.data.errors[x]) {
                        errorMessage += error.response.data.errors[x][y] + ' ';
                    }
                    errors[snakeCaseToCamelCase(x)] = true;
                    errors[snakeCaseToCamelCase(x) + "Message"] = errorMessage;
                }
                inputErrors = errors;
            }
            else {
                // storeHttp.commit('global/showErrorDialog', {
                //     display: true,
                //     errorDetail: error.response
                // })
                console.log(); 
            }
        }

        function snakeCaseToCamelCase(str) {
            var strArray = str.split('_');
            let camelCase = '';
            for (let x = 0; x < strArray.length; x++) {
                if (x == 0) {
                    camelCase += strArray[x];
                } else {
                    camelCase += strArray[x].charAt(0).toUpperCase() + strArray[x].slice(1);
                }
            }
            return camelCase;
        }

        return inputErrors;
    },

    requestInfo(message) {
        storeHttp.commit('global/showInfoDialog', {
            display: true,
            infoDetail: message
        })
    }
};

var storeHttp = null;

export default ({ app, store }) => {
    app.config.globalProperties.$http = http;
    storeHttp = store;
}

export { http }