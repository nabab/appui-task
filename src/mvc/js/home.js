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
    content: '<div class="appui-task-opened-tabstrip appui-full-height"></div>' +
    	'<div class="appui-task-opened-desc container-placeholder appui-lg">' +
    		'<i class="fa fa-question-circle"> </i> &nbsp;' +
    		'Opened projects come here' +
    	'</div>',
    callonce: function(ele){
      $(".appui-task-opened-tabstrip", ele).tabNav({
        activate: function(ele, idx, ui){
          ele.parent().next().hide()
        },
        afterClose: function(ele, idx, ui){
          appui.fn.log("SUR AFTER CLOSE", ele, ui, ui.getList());
          if ( !ui.getList().length ){
            ele.parent().next().show()
          }
        },
      });
    }
  }]
});
appui.tasks = {
  categories: data.categories,
  options: data.options,
  create: function(info){
    if ( info.id ){
      var tabstrip = $("#appui_task" + info.id);
      if ( tabstrip.length ){
        tabstrip.tabNav({
          list: [{
            url: "main",
            title: "Main view",
            content: '<div class="tab-main appui-full-height"></div>',
            static: true
          }, {
            url: "full",
            title: "Full view",
            content: '<div class="tab-full appui-full-height"></div>',
            static: true
          }, {
            url: "logs",
            title: "Logs",
            content: '<div class="tab-logs appui-full-height"></div>',
            static: true
          }]
        })
      }
    }
  }
};