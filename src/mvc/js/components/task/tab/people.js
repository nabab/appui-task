/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(function(){
  Vue.component('appui-task-tab-people', {
    template: '#bbn-tpl-component-appui-task-tab-people',
    props: ['source'],
    data: function(){
      return $.extend({}, this.source);
    },
    methods: {
      isMaster: function(){
        if ( this.isManager() ){
          return true;
        }
        return bbn.env.userId === this.id_user;
      },
      isClosed: function(){
        return this.state === this.appui_tasks.states.closed;
      },
      isManager: function(){
        var managers = this.roles.managers;
        return managers && ($.inArray(bbn.env.userId, managers) > -1);
      },
      canChange: function(){
        return !this.isClosed() && this.isMaster();
      },

    }
  });
})();