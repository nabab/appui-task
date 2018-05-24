/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:35
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        form: {
          props: {
            imageDom: this.tasks.source.root + 'image/tmp/',
            fileSave: this.tasks.source.root + 'actions/file/upload/',
            fileRemove: this.tasks.source.root + 'actions/file/unupload/',
            linkPreview: this.tasks.source.root + 'link_preview'
          },
          data: {
            id_task: this.source.id
          }
        }
      }
    },
    methods: {
      insert(){
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          title: bbn._('New message'),
          width: 800,
          height: 600,
          component: 'appui-notes-forum-form',
          source: $.extend(true, {
            props: {
              formAction: this.tasks.source.root + 'actions/messages/insert',
              formSuccess: (d) => {
                if ( d.success ){
                  this.getRef('forum').updateData();
                  appui.success(bbn._('Inserted'));
                }
                else {
                  appui.error(bbn._('Error'));
                }
              }
            },
            row: {
              title: '',
              text: '',
              files: [],
              links: [],
              locked: 1
            }
          }, this.form)
        });
      },
      edit(n, v){
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          title: bbn._('Edit'),
          width: 800,
          height: 600,
          component: 'appui-notes-forum-form',
          source: $.extend(true, {
            props: {
              formAction: this.tasks.source.root + 'actions/messages/edit',
              formSuccess: (d) => {
                if ( d.success ){
                  if ( v.topic ){
                    v.topic.getRef('pager').updateData();
                  }
                  else {
                    this.getRef('forum').updateData();
                  }
                  appui.success(bbn._('Edited'));
                }
                else {
                  appui.error(bbn._('Error'));
                }
              }
            },
            data: {
              id: n.id
            },
            row: {
              title: v.topic ? undefined : n.title,
              text: n.content,
              files: n.files,
              links: n.links,
              locked: n.locked
            }
          }, this.form)
        });
      },
      reply(n, v){
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          title: bbn._('Reply to') + ' ' + appui.app.getUserName(n.creator),
          width: 800,
          height: 600,
          component: 'appui-notes-forum-form',
          source: $.extend(true, {
            props: {
              formAction: this.tasks.source.root + 'actions/messages/reply',
              formSuccess: (d) => {
                if ( d.success ){
                  if ( v.topic ){
                    v.topic.getRef('pager').updateData();
                  }
                  else {
                    if ( v.getRef('pager') ){
                      v.getRef('pager').updateData();
                    }
                    else {
                      n.num_replies++;
                    }
                  }
                  appui.success(bbn._('Inserted'));
                }
                else {
                  appui.error(bbn._('Error'));
                }
              }
            },
            data: {
              id_parent: n.id,
              id_alias: n.id_alias || n.id
            },
            row: {
              text: '',
              files: [],
              links: [],
              locked: 1
            }
          }, this.form)
        });
      },
      remove(n, v){
        this.confirm(bbn._('Are you sure you want to delete this message?'), () => {
          bbn.fn.post(this.tasks.source.root + 'actions/messages/delete', {
            id: n.id
          }, (d) => {
            if ( d.success ){
              if ( v.topic ){
                v.topic.getRef('pager').updateData();
              }
              else {
                this.getRef('forum').updateData();
              }
              appui.success(bbn._('Deleted'));
            }
            else {
              appui.error(bbn._('Error'));
            }
          });
        });
      }
    },
    created(){
      bbn.vue.setComponentRule(this.tasks.source.root_notes + 'components/', 'appui-notes');
      bbn.vue.addComponent('forum');
      bbn.vue.addComponent('forum/form');
      bbn.vue.unsetComponentRule();
    }
  }
})();