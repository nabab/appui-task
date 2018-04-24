// Javascript Document
(() => {
  return {
    created(){
      let tasks = this,
          mixins = [{
            props: {
              tasks: {
                type: Object,
                default(){
                  return tasks;
                }
              }
            }
          }];
      bbn.vue.setComponentRule(this.source.root + 'components/', 'appui');
      bbn.vue.addComponent('task', mixins);
      bbn.vue.addComponent('task/tab/main', mixins);
      bbn.vue.addComponent('task/tab/people', mixins);
      bbn.vue.addComponent('task/tab/logs', mixins);
      bbn.vue.unsetComponentRule();
    },
    data(){
      return {
        priority_colors: [
          '#F00',
          '#F40',
          '#F90',
          '#FC0',
          '#9B3',
          '#7A4',
          '#5A5',
          '#396',
          '#284',
          '#063'
        ]
      };
    },
    computed: {
      fullCategories(){
        let res = [];
        const getItem = (cat, group) => {
          let items = cat.items || false;
          delete cat.items;
          cat.value = cat.id;
          cat.group = group || (items ? cat.text : '');
          res.push(cat);
          if ( items ){
            $.each(items, (i, c) => {
              getItem(c, cat.text);
            });
          }
        };
        if ( this.source.categories ){
          $.each(Object.assign({}, this.source.categories), (i, cat) => {
            getItem(cat);
          });
        }
        return res;
      },
      groups(){
        let res = [];
        $.each(this.source.groups, (i, v) => {
          v.expanded = false;
          v.items = $.map(
            $.grep(appui.app.users, (user) => {
              return user.active && (user.id_group === v.id);
            }),
            (user) => {
              user.id = user.value;
              user.icon = 'fa fa-user';
              return user;
            }
          );
          if ( v.is_parent ){
            v.icon = 'fa fa-users';
          }
          res.push(v);
        });
        return res;
      }
    },
    methods: {
      userName(id){
        return bbn.fn.get_field(appui.app.users, "value", id, "text");
      },
      userGroup(id){
        return bbn.fn.get_field(appui.app.users, "value", id, "id_group");
      },
      userAvatar(id){
        const av = bbn.fn.get_field(appui.app.users, "value", id, "avatar");
        return av ? av : bbn.var.defaultAvatar;
      },
      userAvatarImg(id){
        const av = this.userAvatar(id),
              name = this.userName(id);
        return '<span class="appui-avatar"><img src="' + av + '" alt="' + name + '" title="' + name + '"></span>';
      },
      userFull(id){
        const user = bbn.fn.get_row(appui.app.users, "value", id);
        return '<span class="appui-avatar"><img src="' + user.avatar + '" alt="' + user.text + '"> ' + user.text + '</span>';
      },











      // Function on the media links in the comments of the task main view
      download_media(id){
        if ( id ){
          bbn.fn.post_out(this.root + 'download/media/' + id);
        }
      },

      // The special field for the type of task (tree inside a dropdown)
      typeField(container, info){
        return $("input[name=type]", container).kendoDropDownTreeView({
          treeview: {
            select: function(e){
              var dt = e.sender.dataItem(e.node);
              if ( dt ){
                ddTree.element.val(dt.id).change();
              }
            },
            dataTextField: "text",
            dataValueField: "id",
            dataSource: new kendo.data.HierarchicalDataSource({
              data: this.categories,
              schema: {
                model: {
                  id: "id",
                  hasChildren: "is_parent",
                  children: "items",
                  fields: {
                    text: {type: "string"},
                    is_parent: {type: "bool"}
                  }
                }
              }
            })
          }
        }).data("kendoDropDownTreeView");
      }
    }
  };
})();