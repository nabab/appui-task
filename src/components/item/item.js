(() => {
  return {
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
      }
    },
    data(){
      return {
        mainPage: {},
        homePage: {},
        columnsComp: {},
        showOpenContent: false,
        showSubtasks: false
      }
    },
    computed: {
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      },
      author(){
        return this.mainPage.userName(this.source.id_user);
      },
      colObj(){
        return !!this.columnsComp ? bbn.fn.getRow(this.closest('column').filteredData, 'index', this.index) : [];
      },
      statusBgColor(){
        return this.mainPage.getStatusBgColor(this.mainPage.getStatusCode(this.source.state));
      },
      statusColor(){
        return this.mainPage.getStatusColor(this.mainPage.getStatusCode(this.source.state));
      },
      statusText(){
        return bbn.fn.getField(this.mainPage.optionsStates, 'text', 'value', this.source.state);
      },
      closedChildren(){
        return bbn.fn.filter(this.source.children, c => c.state === this.mainPage.states.closed);
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
      }
    },
    methods: {
      getMenuSource(){
        let menu = [];
        menu.push({
          text: bbn._('Open in new window'),
          icon: 'nf nf-mdi-open_in_new',
          action: this.seeTask
        });
        if (this.source.collapsed) {
          menu.push({
            text: bbn._('Expand'),
            icon: 'nf nf-mdi-arrow_expand',
            action: () => {
              this.$set(this.source, 'collapsed', false);
            }
          });
        }
        else {
          menu.push({
            text: bbn._('Collapse'),
            icon: 'nf nf-mdi-arrow_collapse',
            action: () => {
              this.$set(this.source, 'collapsed', true);
            }
          });
        }
        return menu;
      },
      seeTask(){
        bbn.fn.link(this.mainPage.root + 'page/task/' + this.source.id);
      },
      openDescription(){
        this.getPopup({
          title: false,
          closable: false,
          width: bbn.fn.isMobile() ? '95%' : '90%',
          height: bbn.fn.isMobile() ? '95%' : '90%',
          component: 'appui-task-columns-task-description',
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
            component: 'appui-task-columns-task-notes',
            source: this.source
          });
        }
      },
      toggleSubtasks(){
        this.showSubtasks = !!this.source.num_children && !this.showSubtasks;
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
      this.$set(this, 'homePage', this.closest('appui-task-home'));
      this.$set(this, 'columnsComp', this.closest('appui-task-columns'));
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