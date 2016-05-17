// Javascript Document
var table = $("#taches_grid"),
    dte = new Date(),
    fileName = "Taches de developpement - " + dte.getSQL();

table.kendoGrid({
  toolbar: kendo.template($("#tpl-tasks_list_bar").html()),
  excel: {
    allPages: true,
    fileName: fileName + ".xlsx"
  },
  pdf: {
    fileName: fileName + ".pdf"
  },
  editable: data.editable,
  sortable: true,
  selectable: "multiple",
  filterable: {
    extra: false,
    mode: "row"
  },
  pageable: {
    refresh: true
  },
  columns: [{
    field: "id",
    hidden: true,
  }, {
    title: data.lng.auter,
    field: "id_user",
    width: 150,
    values: appui.app.apst.users,
    template: function(e){
      var idx = appui.fn.search(appui.app.apst.users, "value", e.id_user);
      if ( idx > -1 ){
        return appui.app.apst.users[idx].text;
      }
      else{
        return '<em>'+ data.lng.inconnu + '</em>';
      }
    }
  }, {
    title: data.lng.cmt,
    width: 40,
    field: "comments"
  }, {
    title: data.lng.active,
    field: "last_activity",
    format: "{0:yyyy-MM-dd}",
    parseFormats: "{0:yyyy-MM-dd}",
    width: 100,
    template: function(e){
      return appui.fn.fdate(e.last_activity, 'Inconnue');
    },
    filterable: {
      ui: function(element){
        element.kendoDatePicker({
          format: "yyyy-MM-dd",
          max: new Date()
        });
      }
    }
  }, {
    title: data.lng.duree,
    field: "duration",
    filterable: false,
    width: 50,
    template: function(e){
      if ( !e.duration ){
        return 'Inconnue';
      }
      if ( e.duration < 3600 ){
        return Math.round(e.duration/60) + ' mn';
      }
      if ( e.duration < (24*3600) ){
        return Math.round(e.duration/3600) + ' h';
      }
      return Math.round(e.duration/(24*3600)) + ' j';
    }
  }, {
    title: data.lng.titre,
    field: "title",
    filterable: {
      cell: {
        showOperators: false,
        operator: "contains"
      }
    }
  }, {
    title: data.lng.priority,
    width: 50,
    field: "priority",
    template: function(e){
      var col;
    	switch ( e.priority ){
        case 1:
          col = 'red';
          break;
        case 2:
          col = 'orange';
          break;
        case 3:
          col = 'blue';
          break;
        case 4:
          col = 'green';
          break;
        case 5:
          col = 'grey';
          break;
      }
      return '<span style="color: ' + col + '">' + e.priority + '</span>';
    },
    filterable: {
      cell: {
        template: function(cont){
          cont.element.kendoNumericTextBox({
            max: 9,
            min: 1,
            format: "n0"
          });
        }
      }
    },
    editor: function(cont, opt){
      $("<input/>").attr("name", opt.field).appendTo(cont).kendoNumericTextBox({
        max: 9,
        min: 1,
        format: "n0"
      });
    }
  }, {
    title: data.lng.objectif,
    field: "deadline",
    format: "yyyy-MM-dd",
    width: 100,
    template: function(e){
      return appui.fn.fdate(e.deadline, '-');
    }
  }, {
    title: data.lng.statut,
    field: "status",
    template: function(e){
      var color;
      switch ( e.status ){
        case "ouvert":
          color = "red";
          break;
        case "en cours":
          color = "blue";
          break;
        case "résolu":
          color = "green";
          break;
        case "en attente":
          color = "DarkGray";
          break;
      }
      return '<span style="color: ' + color + '">' + e.status + '</span>';
    },
    values: [
      {text: data.lng.ouvert, value: "ouvert"},
      {text: data.lng.en_cours, value:"en cours"},
      {text: data.lng.resolu, value: "résolu"},
      {text: data.lng.en_attente, value: "en attente"}
    ],
    width: 100
  },{
    title: data.lng.actions,
    field: "id",
    width: 110,
    filterable: false,
    sortable: false,
    template: function(e){
      var cls = e.subscribed > 0 ? 'fa-unlink' : 'fa-link',
          title = e.subscribed > 0 ? data.lng.desabonner : data.lng.abboner,
          st = '<button class="k-button apst-subscribe" title="' + title + '"><i class="fa ' + cls + '"> </i></button>';
      //'<button class="k-button apst-info" title="Infos"><i class="fa fa-info-circle"> </i></button>' +
      if ( data.is_dev ){
        st += '<button class="k-button apst-edit" title="' + data.lng.modifier + '" href="javascript:;"><i class="fa fa-edit"> </i></a>' +
          '<button class="k-button apst-save" title="' + data.lng.sauvegarder + '" style="display: none"><i class="fa fa-check-circle-o"> </i></a>' +
          '<button class="k-button apst-cancel" title="' + data.lng.annuler + '" style="display: none"><i class="fa fa-times-circle-o"> </i></a>' +
          '<button class="k-button apst-delete" title="' + data.lng.supprimer + '" href="javascript:;"><i class="fa fa-trash"> </i></a>';
      }
      else{
        st += '<button class="k-button apst-up" title="' + data.lng.priority_re + '" href="javascript:;"><i class="fa fa-hand-o-up"> </i></a>' +
      		'<button class="k-button apst-down" title="' + data.lng.priority_ra + '"><i class="fa fa-hand-o-down"> </i></a>';
      }
      return st;
    },
  }],
  dataSource: {
    serverSorting: true,
    serverFiltering: true,
    serverPaging: true,
    pageSize: 50,
    sort:{
      field: "last_activity",
      dir: "desc"
    },
    filter: {
      field: "status",
      operator: "neq",
      value: "résolu"
    },
    transport: {
      read: function(options) {
        appui.fn.post(data.root + "/list", options.data, function(d){
          options.success(d);
        });
      },
      update: function(options) {
        appui.fn.post(data.root + "/list", appui.fn.gridParse(options.data), function(d){
          options.success(d);
        });
      },
      destroy: function(options) {
        appui.fn.post(data.root + "/list", $.extend({}, appui.fn.gridParse(options.data), {action: "delete"}), function(d){
          options.success(d);
        });
      }
    },
    schema: {
      data: "data",
      total: "total",
      model: {
        id: "id",
        fields: [{
          field: "id",
          editable: false
        }, {
          field: "id_user",
          defaultValue: appui.app.apst.userid,
          editable: false
        }, {
          field: "title",
          type: "string"
        }, {
          field: "priority",
          type: "number",
        }, {
          field: "comments",
          type: "number",
          editable: false,
        }, {
          field: "creation_date",
          type: "date",
          editable: false
        }, {
          field: "deadline",
          type: "date",
          validation: {
            min: new Date()
          }
        }, {
          field: "last_activity",
          type: "date",
          editable: false
        }, {
          field: "duration",
          type: "number",
          editable: false
        }, {
          field: "statut",
          validation: {
            required: true
          }
        }]
      }
    },
  },
  change: function(e){
		appui.fn.log(e.sender.select());
  },
  detailInit: function(e){
    var stable = $("<div/>");
    stable.appendTo(e.detailCell).kendoGrid({
      toolbar: [
        {name: "create", text: "Ajouter un commentaire"}
      ],
      dataSource: {
        sort:{
          field: "creation_date",
          dir: "desc"
        },
        transport: {
          create: function(options) {
            appui.fn.post(data.root + "/list", $.extend({}, options.data, {id_task: e.data.id, action: "new_comment"}), function(d){
              e.sender.dataSource.read();
            });
          },
          read: function(options) {
            appui.fn.post(data.root + "/list", $.extend({}, options.data, {id_task: e.data.id, action: "comments"}), function(d){
              options.success(d);
            });
          },
        },
        schema: {
          data: "data",
          total: "total",
          model: {
            id: "id",
            fields: [{
              field: "id",
              editable: false
            }, {
              field: "id_user",
              editable: false,
              defaultValue: appui.app.apst.userid,
            }, {
              field: "comment",
              type: "string"
            }, {
              field: "creation_date",
              type: "date",
              defaultValue: new Date(),
              editable: false
            }]
          }
        },
        pageSize:10,
      },
      sortable: true,
      scrollable: false,
      editable: "inline",
      pageable: {
        refresh: true
      },
      pageSize: 5,
      columns: [{
        field: "id",
        hidden: true,
      }, {
        title: data.lng.auter,
        field: "id_user",
        width: 150,
        values: appui.app.apst.users,
        template: function(e){
          var idx = appui.fn.search(appui.app.apst.users, "value", e.id_user);
          if ( idx > -1 ){
            return appui.app.apst.users[idx].text;
          }
          else{
            return '<em>' + data.lng.inconnu + '</em>';
          }
        }
      }, {
        title: data.lng.date,
        field: "creation_date",
        width: 100,
        template: function(e){
          return appui.fn.fdate(e.creation_date, 'Inconnue');
        }
      }, {
        title: data.lng.texte,
        field: "comment",
        encoded: false,
        editor: function(c, o){
          $(c).append('<table style="width:100%"><tr><td><textarea data-bind="value:' + o.field + '" name="' + o.field + '" class="k-textbox" rows="4" cols="30"style="width: 100%"></textarea></td><td style="width: 100px; vertical-align: middle"><a href="javascript:;" class="k-button k-button-icontext k-primary k-grid-update"><span class="k-icon k-update"></span>' + data.lng.sauver + ' </a><br><br><a href="#" class="k-button k-button-icontext k-grid-cancel"><span class="k-icon k-cancel"></span> ' + data.lng.annuler + '</a></td></tr></table>');
        }
      }],
    });
  }
});
var grid = table.data("kendoGrid");
$("button.apst-fusion", table).kendoButton({
  enable: false,
  click: function(e){
    var r = [];
    table.find(":checkbox:checked").each(function(){
      r.push($(this).val());
    });
    appui.fn.post(data.root + "/list", {id_tasks: r}, function(d){
      if ( d.tiers ){
      }
    });
  }
});
// Bouton rapport de bug
$("button.apst-new", table).kendoButton({
  click: function(e){
    appui.fn.alert(
      kendo.template($("#tpl-tasks_list_form").html())({
        tab_selected: appui.app.tabstrip.ele.tabNav("option", "selected")
      }),
      data.lng.bug,
      600,
      400,
      function(ele){
        ele.find("form").data("script", function($form){
          appui.fn.closeAlert();
          grid.dataSource.read();
        })
    });
  }
});
// Bouton export PDF
$("button.apst-pdf", table).kendoButton({
  click: function(e){
    table.data("kendoGrid").saveAsPDF();
  }
});
$("button.apst-excel", table).kendoButton({
  click: function(e){
    table.data("kendoGrid").saveAsExcel();
  }
});
table.on("click", "button,button i", function(ev){
	var button = $(ev.target),
			$tr = button.closest("tr"),
      dataItem = grid.dataItem($tr),
			icon;
  ev.stopImmediatePropagation();
  if ( button[0].tagName === 'I' ){
    icon = button;
    button = icon.parent();
  }
  else{
    icon = button.find("i.fa");
  }
	if ( button.hasClass("apst-subscribe") ){
		appui.fn.post(data.root + "/list", {id: dataItem.id, action: "subscribe"}, function(d){
			if ( d.success ){
				if ( d.subscribed ){
					appui.fn.alert(data.lng.inscrit, data.lng.succes);
					icon.removeClass("fa-link").addClass("fa-unlink");
				}
				else{
					appui.fn.alert(data.lng.desinscrit, data.lng.succes);
					icon.removeClass("fa-unlink").addClass("fa-link");
				}
			}
			else{
				appui.fn.alert(data.lng.problem);
			}
		});
	}
	else if ( button.hasClass("apst-up") ){
    if ( dataItem.priority <= 1 ){
      appui.fn.alert(data.lng.priority_max);
    }
    else{
      appui.fn.post(data.root + "/list", {id: dataItem.id, action: "up"}, function(d){
        if ( d.id ){
          grid.dataSource.read();
          appui.fn.alert(data.lng.priority_r, data.lng.succes);
        }
      });
    }
	}
	else if ( button.hasClass("apst-down") ){
    if ( dataItem.priority >= 5 ){
      appui.fn.alert(data.lng.priority_min);
    }
    else{
      appui.fn.post(data.root + "/list", {id: dataItem.id, action: "down"}, function(d){
        if ( d.id ){
          grid.dataSource.read();
          appui.fn.alert(data.lng.priority_abb, data.lng.succes);
        }
      });
    }
	}
	else if ( button.hasClass("apst-edit") ){
		grid.editRow($tr);
		button.hide().siblings().hide();
		button.next().show().next().show();
	}
	else if ( button.hasClass("apst-save") ){
    // For Chrome otherwise model not updated
    button.focus();
		grid.saveChanges();
		button.hide().siblings().show();
		button.next().hide();
	}
	else if ( button.hasClass("apst-cancel") ){
		grid.cancelRow();
		button.hide().siblings().show();
		button.prev().hide();
	}
	else if ( button.hasClass("apst-delete") ){
		grid.removeRow($tr);
	}
});
