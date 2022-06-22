/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    methods: {
      renderUser(row){
        return this.userName(row.id_user);
      },
    },
    components: {
      useravatar: {
        template: `
<div class="bbn-vmiddle">
  <bbn-initial v-if="source.id_user"
               :user-id="source.id_user"
               :title="userName"
               :height="25"
               :width="25"
               font-size="1em"/>
  <span v-text="userName" class="bbn-hsmargin"/>
</div>
        `,
        props: {
          source: {
            type: Object
          }
        },
        computed: {
          userName(){
            return appui.app.getUserName(this.source.id_user);
          }
        }
      }
    }
  };
})();
