
import Vue from 'vue';
import moment from 'moment';

Vue.filter('humanReadableTime', function (value) {
    return moment(value).fromNow();
});

Vue.directive('select', {
  twoWay: true,
  bind: function (el, binding, vnode) {
    $(el).select2().on("select2:select", (e) => {
      el.dispatchEvent(new Event('change', { target: e.target }));
    });
  },
  componentUpdated: function(el, me) {
    $(el).trigger("change");
  }
});