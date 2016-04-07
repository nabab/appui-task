// Javascript Document
var textarea = $("textarea", ele),
	  cont_textarea = textarea.parent(),
    old_type = 'text';

kendo.bind(ele, {
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

      case "gfm":
        textarea.codemirror({
          theme: "default",
          height: 200,
          mode: "gfm"
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
  users: [],
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
      appui.fn.window("usergroup/picker", {picker: "#bbn_pm_form_container input[name=id_user]"}, 350, 700);
    }
  },
  add_user: function(){
    var dataItem = this,
        id_user = $("input[name=id_user]", ele).val(),
        rolePicker = $("input[name=role]", ele).data("kendoDropDownList"),
        role = rolePicker.value(),
        frole = appui.fn.get_field(dataItem.roles, "value", role, "text");
    appui.fn.log(frole);
    if ( frole && ($.inArray(dataItem.users, id_user) === -1) ){
      appui.fn.post("usergroup/get_user", {id_user: id_user}, function(d){
        if ( d.res ){
          dataItem.users.push(id_user);
          rolePicker.value("");
          $("div.appui-task-roles-container").append(
            $('<div class="appui-nl"/>').append(
              /** @todo Change this! */
              '<span>' + apst.fnom(d.res)  + ' (' + frole + ')</span> &nbsp; ',
              $('<i class="fa fa-times appui-p"/>').click(function(e){
                if ( (id_user !== appui.env.userId) || confirm(data.confirm_delete) ){
                var idx = $.inArray(dataItem.users, id_user);
                if ( idx > -1 ){
                  dataItem.users.splice(idx, 1);
                }
                $(this).closest("div.appui-nl").remove();
                }
              })
            )
          );
        }
      });
    }
  },
  insert: function(){
    appui.fn.log(appui.fn.formdata(ele));
  }
});