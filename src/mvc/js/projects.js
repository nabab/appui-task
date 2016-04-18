// Javascript Document
$(ele).closest(".k-content").css("padding", "0px");

$("#appui_task_tabnav").tabNav({
  baseTitle: 'Projects - ',
  scrollable: false,
  list: [{
    title: data.lng.opened_tasks,
    content: '<div class="appui-task-opened-task-tabstrip appui-full-height"> </div>',
    url: "opened",
    static: true,
    callback: function(cont, idx, dt, widget){
      $(".appui-task-opened-task-tabstrip", cont).tabNav({
        baseURL: "pm/projects/opened/"
      });
    }
  }, {
    title: data.lng.new_task + ' / ' + data.lng.search,
    content: ' ',
    url: "search",
    static: true,
    callonce: function(cont, idx, dt, widget){
      appui.fn.post(data.root + 'search', function(d){
        if ( d.html ){
          widget.setContent(d.html, idx);
          $(".appui-task-search-container input.appui-lg", cont).keyup(function(e){
            var v = $(this).val();
            if ( v.length > 1 ){
              appui.fn.post(data.root + "search", {search: v}, function(d){
                if ( d.data ){
                  for ( var i = 0; i < d.data.length; i++ ){
                    
                  }
                  kendo.bind($(".appui-task-search-container ul", cont), d.data);
                }
              });
            }
          });
        }
      });
    }
  }, {
    title: data.lng.demo_task,
    content: ' ',
    url: "new",
    static: true,
    callonce: function(cont, idx, dt, widget){
      appui.fn.post(data.root + 'new', function(d){
        if ( d.html ){
          widget.setContent(d.html, idx);
          var data = d.data;
          eval(d.script);
        }
      });
    }
  }, {
    title: data.lng.my_ongoing_tasks,
    content: ' ',
    url: "ongoing",
    static: true,
    callonce: function(cont, idx, dt, widget){
      appui.fn.post(data.root + 'gantt', function(d){
        if ( d.html ){
          widget.setContent(d.html, idx);
          widget.set("callback", function(cont, idx, dt, widget){
            $(".appui-task-gantt", cont).data("kendoTreeList").dataSource.read()
          });
          var data = d.data;
          eval(d.script);
        }
      });
    }
  }, {
    title: data.lng.timeline,
    content: '<p class="appui-c appui-lg">' + data.lng.soon + '</p>',
    url: "timeline",
    static: true,
    callback: function(ele){
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
  }]
});
