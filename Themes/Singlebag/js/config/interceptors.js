import axios from 'axios';
import _ from 'lodash';
import { notify, trans } from '../functions';

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        switch (error.response.status) {
            // case 500:
            //     let errors = _.values(error.response.data.errors);
            //     break;

            // case 422:
            //     let errors = _.values(error.response.data.errors);
            //     Vue.prototype.$awn.warning(_.first(errors)[0]);
            //     break;

            // case 401: {
            // }

            // // forbidden (permission related issues)
            // case 403: {
            // }

            // // bad request
            // case 400: {
            // }

            // // not found
            // case 404: {
            // }

            // // conflict
            // case 409: {
            // }

            // // unprocessable
            // case 422: {
            // }

            // generic api error (server related) unexpected
            default: {
                // return Promise.reject(error.response.data);
                // return Promise.reject(error);
            }
        }

        if(error.response.data.message !== undefined){
            notify(error.response.data.message, 'error');
        }

        if(error.response.data.errors !== undefined && error.response.data.errors.error !== undefined){
            notify(error.response.data.errors.error, 'error' );
        }

        return Promise.reject(error);
    }
);
