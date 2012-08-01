Ext.namespace("er2");

er2.application = {
  init:function(){
    storeUserProcess = function (n, r, i) {

    var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Load users..."});
      myMask.show();
      Ext.Ajax.request({
        url: "/sys"+CONFIG.ws+"/en/classic/cases/casesStartPage_Ajax.php",
        method: "POST",
        params: {"action": "startCase", "processId": "40825258848d67d3d5d1d52083561715", "taskId":"67172619648d67d3d5db8f8066114860"},

        success:function (result, request) {
                  storeUser.loadData(Ext.util.JSON.decode(result.responseText));
                  myMask.hide();
                  var data = Ext.decode(result.responseText);
                  //parent.location.href = "/sys"+CONFIG.ws+"/en/"+CONFIG.skin+"/cases/cases?APP_UID="+data.APPLICATION+"&DEL_INDEX="+data.INDEX+"&action=todo";
                  location.href = "/sys"+CONFIG.ws+"/en/"+CONFIG.skin+"/cases/open?APP_UID="+data.APPLICATION+"&DEL_INDEX="+data.INDEX+"&action=todo";
                },
        failure:function (result, request) {
                  myMask.hide();
                  Ext.MessageBox.alert("Alert", "Failure users load");
                }
      });
    }

    //Variables declared in html file
    var pageSize = parseInt(CONFIG.pageSize);
    var message = CONFIG.message;

    //stores
    var storeUser = new Ext.data.Store({
      proxy:new Ext.data.HttpProxy({
        url: "/sys"+CONFIG.ws+"/en/classic/cases/casesStartPage_Ajax.php",
        method: "POST"
      }),

      //baseParams: {"option": "LST", "pageSize": pageSize},

      reader:new Ext.data.JsonReader({
        //root: "data",
        //totalProperty: "resultTotal",
        APPLICATION: "APPLICATION",
        INDEX: "INDEX",
        PROCESS: "PROCESS",
        CASE_NUMBER: "CASE_NUMBER",
        fields: [{name: "APPLICATION"},
                 {name: "INDEX"},
                 {name: "PROCESS"},
                 {name: "CASE_NUMBER"}
                ]

      }),

      //autoLoad: true, //First call

      listeners:{
        beforeload:function (store) {
          this.baseParams = {"option": "LST", "pageSize": pageSize};
        }
      }
    });

    //Initialize events
    storeUserProcess(pageSize, pageSize, 0);

/*
  var searchForm = new Ext.FormPanel({
    renderTo : document.body,
    //tbar : TbarActions,
    buttonAlign : 'left',
    frame : true,
    bodyStyle : 'padding:5px 5px 0',
    layout : 'form',
*/
/*
    items : [
      {
      layout : 'column',      
      items : [{
        columnWidth : .2,
        layout : 'form',
        items : [{
          xtype : 'textfield',
          id : 'NUMBER',
          width : 150,
          value : '',
          fieldLabel : 'Reimbursement Number',
          allowBlank : true,
          disabled : false
    }],
*/
/*
    buttons : [{
      text : ' New Expense Report ',
      handler : function() {
          Ext.Ajax.request({
            url: "/sys"+CONFIG.ws+"/en/classic/cases/casesStartPage_Ajax.php",
            method: "POST",
            params: {"action": "startCase", "processId": "40825258848d67d3d5d1d52083561715", "taskId":"67172619648d67d3d5db8f8066114860"},
            success:function (result, request) {
                      storeUser.loadData(Ext.util.JSON.decode(result.responseText));
                      //myMask.hide();
                      var data = Ext.decode(result.responseText);
                      //parent.location.href = "/sys"+CONFIG.ws+"/en/"+CONFIG.skin+"/cases/cases_Open?APP_UID="+data.APPLICATION+"&DEL_INDEX="+data.INDEX+"&action=draft";
                      location.href = "/sys"+CONFIG.ws+"/en/"+CONFIG.skin+"/cases/cases_Open?APP_UID="+data.APPLICATION+"&DEL_INDEX="+data.INDEX+"&action=todo";

                    },
            failure:function (result, request) {
                      //myMask.hide();
                      Ext.MessageBox.alert("Alert", "Failure users load");
                    }
          });
      }
    }]
  });

  var viewport = new Ext.Viewport({
    layout : 'fit',
    items : [ grdpnlUser ]
  });
*/
  }
}

Ext.onReady(er2.application.init, er2.application);
