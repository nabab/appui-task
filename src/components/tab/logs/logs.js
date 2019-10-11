/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  return {
    props: ['source'],
    computed: {
      /**
       *  @author Mirko Argentino
       */
      tasks(){
        return this.closest('appui-task-task').tasks;
      }
    },
    methods: {
      renderUser(row){
        return this.tasks.userName(row.id_user);
      },
    },
    components: {
      'appui-tasks-user-avatar': {
        template: `
<div class="bbn-vmiddle">
  <bbn-initial v-if="source.id_user"
               :user-id="source.id_user"
               :title="userName"
               :height="20"
               :width="20"
  ></bbn-initial>
  <span v-text="userName" class="bbn-hsmargin"></span>
</div>
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
