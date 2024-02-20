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
          height: 500
        }, idx);
      },
      canDelete(row) {
        if (appui.app.user.isAdmin
          || (
            (appui.app.user.id === row.id_user)
            && (dayjs().diff(dayjs(row.end), 'hours') < 48)
          )
        ) {
          return true;
        }

        return false;
      },
      remove(row){
        if (this.canDelete(row)){
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
        if (this.canDelete(row)) {
          ret.push({
            title: bbn._('Edit'),
            icon: 'nf nf-fa-edit',
            notext: true,
            action: this.edit
          });
        }

        if (appui.app.user.isAdmin) {
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
                        :max="maxStart"/>
    <label>` + bbn._('End') + `</label>
    <bbn-datetimepicker v-model="source.row.end"
                        :min="source.row.start"
                        :max="maxEnd"/>
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
            maxEnd: dayjs().format('YYYY-MM-DD')
          }
        },
        computed: {
          maxStart(){
            let end = dayjs(this.source.row.end).unix(),
                now = dayjs().unix();
            return end > now ? dayjs().format('YYYY-MM-DD HH:mm:ss') : this.source.row.end;
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
