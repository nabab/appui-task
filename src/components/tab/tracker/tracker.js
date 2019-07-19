/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:35
 */
(() => {
  return {
    props: ['source'],
    computed: {
      tasks(){
        return this.closest('appui-task-task').tasks;
      },
      trackerComp(){
        return bbn.vue.find(appui, 'appui-task-tracker');
      }
    },
    methods: {
      renderUser(row){
        return bbn.fn.get_field(appui.app.users, 'value', row.id_user, 'text');
      },
      renderLength(row){
        return this.trackerComp.secToTime(row.length);
      },
      renderStart(row){
        return moment(row.start).format('DD/MM/YYYY HH:mm:ss');
      },
      renderEnd(row){
        if ( !row.end ){
          return bbn._('In progress...');
        }
        return moment(row.end).format('DD/MM/YYYY HH:mm:ss');
      },
      edit(row, col, idx){
        return this.$refs.table.edit(row, {
          title: bbn._("Edit"),
          height: 500
        }, idx);
      },
      remove(row){
        if ( appui.app.user.isAdmin ){
          this.confirm(bbn._('Are you sure you want to delete this track?'), () => {
            bbn.fn.post(this.tasks.source.root + 'actions/tracker/remove', {id: row.id}, d => {
              if ( d.success ){
                this.getRef('table').updateData();
                appui.success(bbn._('Deleted'));
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      gridButtons(row){
        let ret = [];
        if (
          appui.app.user.isAdmin ||
          (
            (row.id_user === appui.app.user.id) &&
            (moment().diff(moment(row.end), 'hours') < 48)
          )
        ){
          ret.push({
            title: bbn._('Edit'),
            icon: 'nf nf-fa-edit',
            notext: true,
            command: this.edit
          });
        }
        if ( appui.app.user.isAdmin ){
          ret.push({
            title: bbn._('Remove'),
            icon: 'nf nf-fa-trash',
            notext: true,
            command: this.remove
          });
        }
        return ret;
      }
    },
    components: {
      editor: {
        props: ['source'],
        template: `
<bbn-form :source="source.row"
          :action="tracker.tasks.source.root + 'actions/tracker/edit'"
          class="bbn-overlay"
          :scrollable="false"
          :validation="validation"
          @success="success"
>
  <div class="bbn-grid-fields bbn-spadded">
    <label>${bbn._('Start')}</label>
    <bbn-datetimepicker v-model="source.row.start"
                        :max="maxStart"
    ></bbn-datetimepicker>
    <label>${bbn._('End')}</label>
    <bbn-datetimepicker v-model="source.row.end"
                        :min="source.row.start"
                        :max="maxEnd"
    ></bbn-datetimepicker>
    <label>${bbn._('Message')}</label>
    <div style="height: 300px">
      <bbn-textarea v-model="source.row.message"
                    class="bbn-h-100"
                    style="width: 100%"
      ></bbn-textarea>
    </div>
  </div>
</bbn-form>
        `,
        data(){
          return {
            tracker: bbn.vue.closest(this, 'bbn-container').getComponent(),
            maxEnd: moment().format('YYYY-MM-DD')
          }
        },
        computed: {
          maxStart(){
            let end = moment(this.source.row.end).unix(),
                now = moment().unix();
            return end > now ? moment().format('YYYY-MM-DD HH:mm:ss') : this.source.row.end;
          }
        },
        methods: {
          validation(){
            if ( moment(this.source.row.end).unix() < moment(this.source.row.start).unix() ){
              this.alert(bbn._('The end date must be more recent than the start date'));
              return false;
            }
            return true;
          },
          success(d){
            if ( d.success ){
              this.tracker.getRef('table').updateData();
              appui.success(bbn._('Edited'));
            }
            else {
              appui.error(bbn._('Error'));
            }
          }
        },
        watch: {
          'source.row.start'(newVal){
            if ( newVal && this.source.row.end ){
              this.$set(this.source.row, 'length', moment(this.source.row.end).unix() - moment(this.source.row.start).unix());
            }
            else {
              this.$set(this.source.row, 'length', 0);
            }
          },
          'source.row.end'(newVal){
            if ( newVal && this.source.row.start ){
              this.$set(this.source.row, 'length', moment(this.source.row.end).unix() - moment(this.source.row.start).unix());
            }
            else {
              this.$set(this.source.row, 'length', 0);
            }
          }
        }
      }
    }
  }
})();
