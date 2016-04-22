// Javascript Document

$(".appui-task-opened-tabstrip", ele).tabNav();

$.extend(appui.tasks, {
  create: function(info, ele){
    ele.tabNav({
      list: [{
        title: "Main view",
        content: '<div class="tab-main"></div>'
      }, {
        title: "Full view",
        content: '<div class="tab-full"></div>'
      }, {
        title: "Logs",
        content: '<div class="tab-logs"></div>'
      }]
    })
  }
});