/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:50
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        main: bbn.vue.closest(this, 'appui-task-tab-main')
      }
    },
    methods: {
      minDate(){
        return moment().format('YYYY-MM-DD 00:00:00');
      }
    }
  }
})();