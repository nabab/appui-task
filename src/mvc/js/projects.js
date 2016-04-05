// Javascript Document
$(ele).css("overflow", "hidden");

$("#bbn_tasks_tabnav").tabNav({
  baseURL: data.root + '/projects/',
  baseTitle: 'Projects - ',
  scrollable: false,
  list: [{
    title: data.title,
    content: data.content,
    url: "new",
    static: true,
    callonce: function(ele){
      appui.fn.log(data.script);
      eval(data.script);
    }
  }, {
    title: "My ongoing tasks",
    content: ' ',
    url: "ongoing",
    static: true,
    callonce: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      appui.fn.post(data.root + '/ongoing', function(d){
        if ( d.html ){
          widget.setContent(d.html, idx);
        }
      })
    }
  }, {
    title: "Timeline",
    content: 'Hello 3',
    url: "timeline",
    static: true,
    callback: function(ele){
    }
  }, {
    title: "Search",
    content: 'Hello 4',
    url: "search",
    static: true,
    callback: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      //appui.fn.log(this.options.current);
    }
  }]
});
