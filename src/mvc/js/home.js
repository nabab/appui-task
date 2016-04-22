// Javascript Document
$(ele).closest(".k-content").css("padding", "0px");

$("#appui_task_tabnav").tabNav({
  baseTitle: 'Projects - ',
  scrollable: false,
  list: [{
    title: data.lng.new_task + ' / ' + data.lng.search,
    content: ' ',
    url: "search",
    static: true,
    load: true
  }, {
    title: data.lng.demo_task,
    content: ' ',
    url: "new",
    static: true,
    load: true
  }, {
    title: data.lng.my_ongoing_tasks,
    content: ' ',
    url: "ongoing",
    static: true,
    load: true
  }, /*{
    title: data.lng.timeline,
    content: '<p class="appui-c appui-lg">' + data.lng.soon + '</p>',
    url: "timeline",
    static: true,
    callback: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      //appui.fn.log(this.options.current);
    }
  }, {
    title: data.lng.all_tasks,
    content: '<p class="appui-c appui-lg">' + data.lng.soon + '</p>',
    url: "all",
    static: true,
    callback: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      //appui.fn.log(this.options.current);
    }
  }, */{
    title: data.lng.opened_tasks,
    url: "tasks",
    static: true,
    load: true
  }]
});
appui.tasks = {
  categories: data.categories,
  options: data.options
}