/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  return {
    props: ['source'],
    methods: {
      renderUser(row){
        return this.tasks.userName(row.id_user);
      },
      renderDate(row){
        return bbn.fn.fdate(row.chrono);
      }
    },
    components: {
      'appui-tasks-user-avatar': {
        template: '#appui-tasks-user-avatar',
        props: ['source'],
        methods: {
          userName(id){
            return bbn.fn.get_field(appui.app.users, "value", id, "text");
          }
        }
      }
    }
  };
})();
