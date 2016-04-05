// Javascript Document
var textarea = $("textarea", ele),
	  cont_textarea = textarea.parent(),
    old_type = 'text';

kendo.bind(ele, {
  add_role: function(e){
    e.preventDefault();
    appui.fn.log(e, this);
  },
  add_attachment: function(e){
    e.preventDefault();
    appui.fn.log(e, this);
  },
  add_link: function(e){
    e.preventDefault();
    appui.fn.log(e, this);
  },
  change_comment_type: function(){
    var type = $("select.comment_type", ele).data("kendoDropDownList").value(),
        textarea_st = '<textarea class="k-textbox" name="comment"></textarea>',
        text = textarea.val(),
        val;

    if ( textarea.is(".ui-codemirror") ){
      val = textarea.val();
    }
    else if ( textarea.data("kendoEditor") ){
      val = textarea.data("kendoEditor").value();
    }
    else{
      val = textarea.val();
    }

    cont_textarea.html(textarea_st);
    textarea = $("textarea", ele);
    if ( val ){
      textarea.val(val);
    }

    switch ( type ){
      case "text":
        break;

      case "html":
        textarea.kendoEditor();
        break;

      case "md":
        textarea.codemirror({
          height: 200,
          mode: "md"
        });
        break;

      case "php":
        textarea.codemirror({
          height: 200,
          mode: "php"
        });
        break;

      case "js":
        textarea.codemirror({
          height: 200,
          mode: "js"
        });
        break;

      case "css":
        textarea.codemirror({
          height: 200,
          mode: "less"
        });
        break;
    }
  },
  roles: [{
    text: "Worker",
    value: "doer"
  }, {
    text: "Manager",
    value: "manager"
  }, {
    text: "Spectator",
    value: "viewer"
  }],
  change_role: function(){
    var role = $("input[name=role]", ele).data("kendoDropDownList").value();
    if ( role ){
      appui.fn.log(role);
      appui.fn.window("users/user_picker");
    }
  }
});