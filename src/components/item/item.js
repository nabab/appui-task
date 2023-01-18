(() => {
  let mixins = [{
    data(){
      return {
        mainPage: {},
        homePage: {},
        columnsComp: {}
      };
    },
    computed:{
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
      this.$set(this, 'homePage', this.closest('appui-task-home'));
      this.$set(this, 'columnsComp', this.closest('appui-task-columns'));
    }
  }];
  bbn.vue.addPrefix('appui-task-item-', (tag, resolve, reject) => {
    return bbn.vue.queueComponent(
      tag,
      appui.plugins['appui-task'] + '/components/item/' + bbn.fn.replaceAll('-', '/', tag).substr('appui-task-item-'.length),
      mixins,
      resolve,
      reject
    );
  });

  return {
    mixins: mixins,
    props: {
      source: {
        type: Object
      },
      index:{
        type: Number
      },
      inverted: {
        type: Boolean,
        default: false
      },
      showParent: {
        type: Boolean,
        default: false
      }
    },
    data(){
      return {
        showOpenContent: false,
        showSubtasks: false,
        collapsed: false
      }
    },
    computed: {
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      },
      author(){
        return !!this.mainPage ? this.mainPage.userName(this.source.id_user) : '';
      },
      colObj(){
        return !!this.columnsComp ? bbn.fn.getRow(this.closest('column').filteredData, 'index', this.index) : {};
      },
      statusBgColor(){
        return !!this.mainPage ? this.mainPage.getStatusBgColor(this.mainPage.getStatusCode(this.source.state)) : '';
      },
      statusColor(){
        return !!this.mainPage ? this.mainPage.getStatusColor(this.mainPage.getStatusCode(this.source.state)) : '';
      },
      statusText(){
        return !!this.mainPage ? bbn.fn.getField(this.mainPage.optionsStates, 'text', 'value', this.source.state) : '';
      },
      closedChildren(){
        return !!this.mainPage ? bbn.fn.filter(this.source.children, c => c.state === this.mainPage.states.closed) : [];
      },
      managersTitle(){
        if (this.mainPage) {
          let s = bbn.fn.getField(this.mainPage.optionsRoles, 'text', 'code', 'managers');
          if (!!this.source.roles.managers) {
            s += "\n";
            bbn.fn.each(this.source.roles.managers, u => {
              s += "\n" + appui.app.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      workersTitle(){
        if (this.mainPage) {
          let s = bbn.fn.getField(this.mainPage.optionsRoles, 'text', 'code', 'workers');
          if (!!this.source.roles.workers) {
            s += "\n";
            bbn.fn.each(this.source.roles.workers, u => {
              s += "\n" + appui.app.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      role(){
        if (this.mainPage && !!this.source.role) {
          return bbn.fn.getRow(this.mainPage.optionsRoles, 'value', this.source.role);
        }
        return false;
      },
      isCollapsed(){
        if (this.colObj && bbn.fn.numProperties(this.colObj)) {
          return !!this.colObj.collapsed;
        }
        return this.collapsed;
      }
    },
    methods: {
      getMenuSource(){
        let menu = [{
          text: bbn._('Open in new window'),
          icon: 'nf nf-mdi-open_in_new',
          action: this.seeTask
        }, {
          text: this.isCollapsed ? bbn._('Expand') : bbn._('Collapse'),
          icon: this.isCollapsed ? 'nf nf-mdi-arrow_expand' : 'nf nf-mdi-arrow_collapse',
          action: () => {
            this.toggleCollapsed();
          }
        }];
        return menu;
      },
      seeTask(){
        bbn.fn.link(this.mainPage.root + 'page/task/' + this.source.id);
      },
      seeParentTask(){
        if (!!this.source.parent && !!this.source.parent.id) {
          bbn.fn.link(this.mainPage.root + 'page/task/' + this.source.parent.id);
        }
      },
      openDescription(){
        this.getPopup({
          title: false,
          closable: false,
          width: bbn.fn.isMobile() ? '95%' : '90%',
          height: bbn.fn.isMobile() ? '95%' : '90%',
          component: 'appui-task-item-description',
          source: this.source
        });
      },
      openNotes(){
        if (this.source.num_notes) {
          this.getPopup({
            title: false,
            closable: false,
            width: bbn.fn.isMobile() ? '95%' : '90%',
            height: bbn.fn.isMobile() ? '95%' : '90%',
            component: 'appui-task-item-notes',
            source: this.source
          });
        }
      },
      toggleSubtasks(){
        this.showSubtasks = !!this.source.num_children && !this.showSubtasks;
      },
      toggleCollapsed(){
        if (this.colObj && bbn.fn.numProperties(this.colObj)) {
          this.$set(this.colObj, 'collapsed', !!this.colObj.collapsed ? false : true);
          this.collapsed = this.colObj.collapsed;
        }
        else {
          this.collapsed = !this.collapsed;
        }
      }
    },
    mounted(){
      this.$nextTick(() => {
        if (this.getRef('description').clientHeight >= 600) {
          this.showOpenContent = true;
        }
      });
    }
  }
})();