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
      shorten: bbn.fn.shorten,
      html2text: bbn.fn.html2text,
      fdate: bbn.fn.fdate
    }
  }
})();