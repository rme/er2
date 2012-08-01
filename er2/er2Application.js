Ext.namespace("er2");

er2.application = {
  init:function(){
    storeUserProcess = function (n, r, i) {
      var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Load users..."});
      myMask.show();

      Ext.Ajax.request({
        url: "/sys"+CONFIG.ws+"/en/classic/cases/proxyCasesList?sort=APP_CACHE_VIEW.APP_NUMBER&dir=DESC&action="+CONFIG.action+"&start=0&limit=20",
        method: "POST",
        params: {"option": "LST", "pageSize": n, "limit": r, "start": i, "process": "40825258848d67d3d5d1d52083561715"},

        success:function (result, request) {
                  storeUser.loadData(Ext.util.JSON.decode(result.responseText));
                  myMask.hide();
                },
        failure:function (result, request) {
                  myMask.hide();
                  Ext.MessageBox.alert("Alert", "Failure users load");
                }
      });
    };

    onMnuContext = function(grid, rowIndex,e) {
      e.stopEvent();
      var coords = e.getXY();
      mnuContext.showAt([coords[0], coords[1]]);
    };

    //Variables declared in html file
    var pageSize = parseInt(CONFIG.pageSize);
    var message = CONFIG.message;

    //stores
    var storeUser = new Ext.data.Store({
      proxy:new Ext.data.HttpProxy({
        url: "/sys"+CONFIG.ws+"/en/classic/cases/proxyCasesList?sort=APP_CACHE_VIEW.APP_NUMBER&dir=DESC&action="+CONFIG.action+"&start=0&limit=20",
        method: "POST"
      }),

      //baseParams: {"option": "LST", "pageSize": pageSize},

      reader:new Ext.data.JsonReader({
        root: "data",
        totalProperty: "resultTotal",
        fields: [{name: "APP_UID"},
                 {name: "APP_NUMBER"},
                 {name: "PRO_UID"},
                 {name: "TAS_UID"},
                 {name: "APP_DEL_PREVIOUS_USER"},
                 {name: "DEL_INIT_DATE"},
                 {name: "APP_TITLE"},
                 {name: "APP_PRO_TITLE"},
                 {name: "APP_TAS_TITLE"},
                 {name: "DEL_TASK_DUE_DATE"},
                 {name: "APP_UPDATE_DATE"},
                 {name: "DEL_PRIORITY"},
                 {name: "DEL_INDEX"}
                ]
      }),

      //autoLoad: true, //First call

      listeners:{
        beforeload:function (store) {
          this.baseParams = {"option": "LST", "pageSize": pageSize};
        }
      }
    });

    var storePageSize = new Ext.data.SimpleStore({
      fields: ["size"],
      data: [["15"], ["25"], ["35"], ["50"], ["100"]],
      autoLoad: true
    });

    //
    var btnNew = new Ext.Action({
      id: "btnNew",

      text: "New",
      iconCls: "button_menu_ext ss_sprite ss_add",

      handler: function() {
        Ext.MessageBox.alert("Alert", message);
      }
    });

    var btnEdit = new Ext.Action({
      id: "btnEdit",

      text: "Edit",
      iconCls: "button_menu_ext ss_sprite ss_pencil",
      disabled: true,

      handler: function() {
        Ext.MessageBox.alert("Alert", message);
      }
    });

    var btnDelete = new Ext.Action({
      id: "btnDelete",

      text: "Delete",
      iconCls: "button_menu_ext ss_sprite ss_delete",
      disabled: true,

      handler: function() {
        Ext.MessageBox.alert("Alert", message);
      }
    });

    var btnSearch = new Ext.Action({
      id: "btnSearch",

      text: "Search",

      handler: function() {
        //Ext.MessageBox.alert("Alert", message);
      }
    });

    var mnuContext = new Ext.menu.Menu({
      id: "mnuContext",

      items: [btnEdit, btnDelete]
    });

    var txtSearch = new Ext.form.TextField({
      id: "txtSearch",

      emptyText: "Enter search term",
      width: 150,
      allowBlank: true,

      listeners:{
        specialkey: function (f, e) {
          if (e.getKey() == e.ENTER) {
            //Ext.MessageBox.alert("Alert", message);
          }
        }
      }
    });

    var btnTextClear = new Ext.Action({
      id: "btnTextClear",

      text: "X",
      ctCls: "pm_search_x_button",
      handler: function() {
        //txtSearch.reset();
      }
    });

    var cboPageSize = new Ext.form.ComboBox({
      id: "cboPageSize",

      mode: "local",
      triggerAction: "all",
      store: storePageSize,
      valueField: "size",
      displayField: "size",
      width: 50,
      editable: false,

      listeners:{
        select: function (combo, record, index) {
          pageSize = parseInt(record.data["size"]);

          pagingUser.pageSize = pageSize;
          pagingUser.moveFirst();
        }
      }
    });

    var pagingUser = new Ext.PagingToolbar({
      id: "pagingUser",

      pageSize: pageSize,
      store: storeUser,
      displayInfo: true,
      displayMsg: "Displaying users " + "{" + "0" + "}" + " - " + "{" + "1" + "}" + " of " + "{" + "2" + "}",
      emptyMsg: "No roles to display",
      items: ["-", "Page size:", cboPageSize]
    });

    var cmodel = new Ext.grid.ColumnModel({
      defaults: {
        width:50,
        sortable:true
      },
      columns:[{id: "APP_UID", dataIndex: "APP_UID", hidden: true},
               {header: "#", dataIndex: "APP_NUMBER", width: 7, align: "left"},
               {header: "Case", dataIndex: "APP_TITLE", width: 7, align: "left"},
               {header: "Process", dataIndex: "PRO_UID", align: "left", hidden: true},
               {header: "Task", dataIndex: "TAS_UID", width: 25, align: "center", hidden: true},
               {header: "Initial Date", dataIndex: "DEL_INIT_DATE", width: 25, align: "left", hidden: true},
               {header: "Process", dataIndex: "APP_PRO_TITLE", width: 25, align: "left"},
               {header: "Task", dataIndex: "APP_TAS_TITLE", width: 25, align: "left"},
               {header: "Due Date", dataIndex: "DEL_TASK_DUE_DATE", width: 25, align: "left"},
               {header: "Last Modify", dataIndex: "APP_UPDATE_DATE", width: 25, align: "left"},
               {header: "Priority", dataIndex: "DEL_PRIORITY", width: 25, align: "left"},
               {header: "Index", dataIndex: "DEL_INDEX", width: 25, align: "left", hidden: true}
              ]
    });

    var smodel = new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {

        rowselect: function (sm, rowIndex) {
          var record = grdpnlUser.getStore().getAt(rowIndex);
          location.href = "/sys"+CONFIG.ws+"/en/hsu/cases/open?APP_UID="+record.get('APP_UID')+"&DEL_INDEX="+record.get('DEL_INDEX')+"&action=todo";
          //btnEdit.enable();
          //btnDelete.enable();
        },
        rowdblclick: function(grid, rowIndex, event){
          alert("double ok!");
        },
        rowdeselect: function (sm) {
          //btnEdit.disable();
          //btnDelete.disable();
        }
      }
    });

    var grdpnlUser = new Ext.grid.GridPanel({
      id: "grdpnlUser",

      store: storeUser,
      colModel: cmodel,
      selModel: smodel,

      columnLines: true,
      viewConfig: {forceFit: true},
      enableColumnResize: true,
      enableHdMenu: true, //Menu of the column

      //tbar: [btnNew, "-", btnEdit, btnDelete, "-", "->", txtSearch, btnTextClear, btnSearch],
      tbar: ["->", txtSearch, btnTextClear, btnSearch],
      bbar: pagingUser,

      style: "margin: 0 auto 0 auto;",
      //width: "100%",
      //height: 450,
      layout: "fit",
      //title: "Users",

      renderTo: "divMain",

      listeners:{
      }
    });

    //Initialize events
    storeUserProcess(pageSize, pageSize, 0);

    grdpnlUser.on("rowcontextmenu", 
      function (grid, rowIndex, evt) {
        var sm = grid.getSelectionModel();
        sm.selectRow(rowIndex, sm.isSelected(rowIndex));
      },
      this
    );

    grdpnlUser.addListener("rowcontextmenu", onMnuContext, this);
    //grdpnlUser.addListener("rowdblclick", onMnuContext, this);

    cboPageSize.setValue(pageSize);

    var viewport = new Ext.Viewport({
      layout : 'fit',
      items : [ grdpnlUser ]
    });

  }
}

Ext.onReady(er2.application.init, er2.application);

