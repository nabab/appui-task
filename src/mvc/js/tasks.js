// Javascript Document

$(".appui-task-opened-tabstrip", ele).tabNav();

$.extend(appui.tasks, {
  create: function(info){
    if ( info.id ){
      var tabstrip = $("#appui_task" + info.id);
      if ( tabstrip.length ){
        tabstrip.tabNav({
          list: [{
            url: "main",
            title: "Main view",
            content: '<div class="tab-main"></div>'
          }, {
            url: "full",
            title: "Full view",
            content: '<div class="tab-full"></div>'
          }, {
            url: "logs",
            title: "Logs",
            content: '<div class="tab-logs"></div>'
          }]
        })
      }
    }
  }
});