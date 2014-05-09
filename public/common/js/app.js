/* Application JS Main File */

var Courses = (function(){


    var CONFIGS    =   {

          DataTables    : {
                  Members :  {

                      "aoColumns": [
                          { "sTitle": "Name" }
                      ],
                      bJQueryUI : true
                  },

                  Courses : {
                      "aoColumns": [
                          { "sTitle": "Date Start" },
                          { "sTitle": "Date End" },
                          { "sTitle": "Title"},
                          { "sTitle": "Trainer"}
                      ],
                      bJQueryUI : true
                  }
          },

          Menu : { /* To be implemented some day in the future */ },

          DatePickers : {
                  Members : {
                      dateFormat : "yy-mm-dd"
                  }
          }

    };

    var _initMainMenu   =   function() {
            $( "ul#menu" ).menu();

            return true;
    };

    var _initDataTables =  {

        Courses :   function() {
            $('#courses-list').dataTable(CONFIGS.DataTables.Courses);
        },

        Members :   function() {
            $('#members-list').dataTable(CONFIGS.DataTables.Members);
        },

        All : function() {
            this.Courses();
            this.Members();
        }

    };

    var _initDatePickers = {

        Courses : function() {

            if ($('#date_start').length > 0) {
                $('#date_start').datepicker(CONFIGS.DatePickers.Members);
            }

            if ($('#date_end').length > 0) {
               $('#date_end').datepicker(CONFIGS.DatePickers.Members);
            }
        }

    };

    var _getDataTables = {
        Courses : function() {
            return $('#courses-list').dataTable();
        },

        Members : function() {
            return $('#members-list').dataTable();
        }
    }

    return {

        Init : function() {

            _initMainMenu();
            _initDataTables.Courses();
            _initDataTables.Members();
            _initDatePickers.Courses();

            return true;
        },

        Abandon : function() {
            return true;
        },

        LoadMembersListFromDb : function(url, callBack) {

            var cbSend = Courses.HandleMembersListLoadSuccess

            if ('function' == typeof callBack) {
                cbSend = callBack;
            }

            $.ajax({
                dataType : "json",
                url      : url,
                success  : cbSend
            });

            delete callBack;
            delete cbSend;
        },

        HandleMembersListLoadSuccess : function(d) {
            if(d && d instanceof Array) {

                var tblMembers = _getDataTables.Members();
                    tblMembers.fnClearTable();
                    tblMembers.fnAddData(d);
            }
        },

        LoadCoursesListFromDb : function(url, callBack) {

            var cbSend = Courses.HandleCoursesListLoadSuccess

            if ('function' == typeof callBack) {
                cbSend = callBack;
            }

            $.ajax({
                dataType : "json",
                url      : url,
                success  : cbSend
            });

            delete callBack;
            delete cbSend;
        },

        HandleCoursesListLoadSuccess : function(d) {

            if(d && d instanceof Array) {

                var tblCourses = _getDataTables.Courses();
                    tblCourses.fnClearTable();
                    tblCourses.fnAddData(d);
            }

        }
    }

})();