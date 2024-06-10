
import Vue from 'vue';
import moment from 'moment';

Vue.filter('humanReadableTime', function (value) {
    return moment(value).fromNow();
});