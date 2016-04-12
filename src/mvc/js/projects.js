// Javascript Document
$(ele).closest(".k-content").css("padding", "0px");

$("#appui_task_tabnav").tabNav({
  baseURL: data.root + '/projects/',
  baseTitle: 'Projects - ',
  scrollable: false,
  list: [{
    title: data.title,
    content: data.content,
    url: "new",
    static: true,
    callonce: function(ele){
      eval(data.script);
    }
  }, {
    title: data.my_ongoing_tasks,
    content: ' ',
    url: "ongoing",
    static: true,
    callonce: function(cont, idx, dt, widget){
      appui.fn.post(data.root + '/ongoing', function(d){
        if ( d.html ){
          widget.setContent(d.html, idx);
        }
      })
    }
  }, {
    title: data.timeline,
    content: 'Hello 3',
    url: "timeline",
    static: true,
    callback: function(ele){
    }
  }, {
    title: data.search,
    content: 'Hello 4',
    url: "search",
    static: true,
    callback: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      //appui.fn.log(this.options.current);
    }
  }]
});
