/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  Vue.component('appui-task-tab-logs', {
    template: '#bbn-tpl-component-appui-task-tab-logs',
    props: ['source'],
    data(){
      return {
        root: appui.tasks.source.root
      };
    },
    methods: {
      renderUser(row){
        return appui.tasks.userName(row.id_user);
      },
      renderDate(row){
        return bbn.fn.fdate(row.chrono);
      }
    },
    mounted(){
      this.$nextTick(() => {
        bbn.fn.analyzeContent(this.$el, true);
      });
    },
    components: {
      'appui-tasks-user-avatar': {
        template: '#appui-tasks-user-avatar',
        props: ['source'],
        methods: {
          userName: appui.tasks.userName,
        }
      }
    }
  });
})();
