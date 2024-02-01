/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:35
 */
(() => {
  return {
    props: {
      source: {
        type: Object,
        required: true
      }
    },
    methods: {
      renderUser(row){
        return bbn.fn.getField(appui.app.users, 'text', 'value', row.id_user);
      },
      renderLength(row){
        return this.secToTime(row.length);
      },
      renderStart(row){
        return dayjs(row.start).format('DD/MM/YYYY HH:mm:ss');
      },
      renderEnd(row){
        if ( !row.end ){
          return bbn._('In progress') + '...';
        }
        return dayjs(row.end).format('DD/MM/YYYY HH:mm:ss');
      },
      edit(row, col, idx){
        return this.$refs.table.edit(row, {
          title: bbn._("Edit"),
          width: 500
        }, idx);
      },
      remove(row){
        if ( appui.app.user.isAdmin ){
          this.confirm(bbn._('Are you sure you want to delete this track?'), () => {
            this.post(this.root + 'actions/tracker/remove', {id: row.id}, d => {
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
            (dayjs().diff(dayjs(row.end), 'hours') < 48)
          )
        ){
          ret.push({
            title: bbn._('Edit'),
            icon: 'nf nf-fa-edit',
            notext: true,
            action: this.edit
          });
        }
        if ( appui.app.user.isAdmin ){
          ret.push({
            title: bbn._('Remove'),
            icon: 'nf nf-fa-trash',
            notext: true,
            action: this.remove
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
          :action="tracker.root + 'actions/tracker/edit'"
          :scrollable="false"
          :validation="validation"
          @success="success">
  <div class="bbn-grid-fields bbn-padded">
    <label>` + bbn._('Start') + `</label>
    <bbn-datetimepicker v-model="source.row.start"
                        :max="maxStart"
                        :show-second="true"/>
    <label>` + bbn._('End') + `</label>
    <bbn-datetimepicker v-model="source.row.end"
                        :min="source.row.start"
                        :max="maxEnd"
                        :show-second="true"/>
    <label>`+ bbn._('Message') + `</label>
    <div style="height: 300px">
      <bbn-textarea v-model="source.row.message"
                    class="bbn-h-100"
                    style="width: 100%"/>
    </div>
  </div>
</bbn-form>
        `,
        data(){
          return {
            tracker: this.closest('bbn-container').find('appui-task-task-widget-tracker-detail'),
            maxEnd: dayjs().format('YYYY-MM-DD HH:mm:ss')
          }
        },
        computed: {
          maxStart(){
            if (!this.source.row.end) {
              return dayjs().format('YYYY-MM-DD HH:mm:ss');
            }

            let end = dayjs(this.source.row.end).unix(),
                now = dayjs().unix();
            return end < now ? this.source.row.end : dayjs().format('YYYY-MM-DD HH:mm:ss');
          }
        },
        methods: {
          validation(){
            if ( dayjs(this.source.row.end).unix() < dayjs(this.source.row.start).unix() ){
              this.alert(bbn._('The end date must be more recent than the start date'));
              return false;
            }
            return true;
          },
          success(d){
            if ( d.success ){
              this.tracker.getRef('table').updateData();
              appui.success(bbn._('Edited'));
              let notesWidget = this.closest('bbn-container').find('appui-task-task-widgets-notes');
              if (notesWidget) {
                let taskNotes = notesWidget.find('appui-task-notes');
                if (taskNotes && !!taskNotes.getRef('forum')) {
                  taskNotes.getRef('forum').updateData();
                }
              }
            }
            else {
              appui.error(bbn._('Error'));
            }
          }
        },
        watch: {
          'source.row.start'(newVal){
            if ( newVal && this.source.row.end ){
              this.$set(this.source.row, 'length', dayjs(this.source.row.end).unix() - dayjs(this.source.row.start).unix());
            }
            else {
              this.$set(this.source.row, 'length', 0);
            }
          },
          'source.row.end'(newVal){
            if ( newVal && this.source.row.start ){
              this.$set(this.source.row, 'length', dayjs(this.source.row.end).unix() - dayjs(this.source.row.start).unix());
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
