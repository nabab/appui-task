// Javascript Document

(() => {
  return {
    data() {
      return {
        root: appui.plugins['appui-task'] + '/',
        users: appui.users
      }
    },
    methods: {
      renderTitle(row) {
        return '<a href="' + this.root + 'page/task/' + row.id + '">' + row.title + '</a>';
      },
    }
  }
})();
