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
        template: `
<bbn-initial v-if="source.id_user"
             :user-id="source.id_user"
             :title="userName"
             :height="20"
             :width="20"
></bbn-initial>
        `,
        props: ['source'],
        computed: {
          userName(){
            return appui.app.getUserName(this.source.id_user);
          }
        }
      }
    }
  };
})();
