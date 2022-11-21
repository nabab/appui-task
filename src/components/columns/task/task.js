(() => {
  return {
    props: {
      source: {
        type: Object
      },
      index:{
        type: Number
      }
    },
    data(){
      return {
        showOpenContent: false
      }
    },
    computed: {
      author(){
        return this.mainPage.userName(this.source.id_user);
      },
      colObj(){
        return bbn.fn.getRow(this.closest('column').filteredData, 'index', this.index);
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
          width: '90%',
          height: '90%',
          component: 'appui-task-columns-task-description',
          source: this.source
        });
      },
      openNotes(){
        this.getPopup({
          title: false,
          closable: false,
          width: '90%',
          height: '90%',
          component: 'appui-task-columns-task-description',
          source: this.source
        });
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