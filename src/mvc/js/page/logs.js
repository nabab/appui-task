(() => {
  return {
    data(){
      return {
        mainPage: null,
        users: appui.users
      }
    },
    methods: {
      renderTitle(row){
        return `<a href="${this.mainPage.root}page/task/${row.id_task}">${row.title}</a>`;
      },
      renderState(row){
        let code = bbn.fn.getField(this.mainPage.optionsStates, 'code', 'value', row.state);
        return `<div style="color: ${this.mainPage.getStatusBgColor(code)}">${bbn.fn.getField(this.mainPage.optionsStates, 'text', 'value', row.state)}</div>`;
      }
    },
    created(){
      this.$set(this, 'mainPage', appui.getRegistered('appui-task'));
    }
  }
})();