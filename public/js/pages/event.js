var myTable;
$(document).ready(function(){
    data_table();

    $("#start_date").datepicker({
        'dateFormat' : 'yy-mm-dd',
        onSelect: function(selected) {
            $("#end_date").datepicker("option","minDate", selected)
        }
    });
    $("#end_date").datepicker({
        'dateFormat' : 'yy-mm-dd',
        onSelect: function(selected) {
           $("#end_date").datepicker("option","maxDate", selected)
        }
    });

    $('#frmEvent').validate({
        errorElement: 'span',
        rules: {
            title: {required: true},
            start_date: {required: true },
            end_date: {required:true },
            recurrence_type: {required:true },
        },
        messages: {
            title: {required: "Please enter event title"},
            start_date: {required: "Please enter event start date"},
            end_date: {required: "Please enter event end date"},
            recurrence_type: {required: "Please select event recurrence type"},
        }
    });
});
var myTable;
function data_table()
{
    $.fn.dataTable.ext.errMode = 'none';
    myTable = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "event/ajax"
        },
        "order": [],
        // 'displayLength' : 1,
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'dates', name: 'dates', orderable: false},
            {data: 'occurence', name: 'occurence', orderable: false},
            {data: 'action', name: 'action', orderable: false},
        ],
        oLanguage: {
            sSearch: "",
            sSearchPlaceholder: "Search",
            sEmptyTable: "No data found.",
            sProcessing: '<div class="loader"><span class="loader-image"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span></div>'
        }
    });
    $('.dataTables_filter input').addClass('form-control');
    $('.dataTables_length select').addClass('form-control');
    $('.dataTables_length select').css("display","inline");
    $('.dataTables_length select').css("width","auto");
}

$(document).on('click', '.btnDelete', function () {
    var id = $(this).data("id");
    var title = "Do you really want to delete?";
    var body = " ";
    var url = "event/delete/"+ id;

    var token = $('meta[name="csrf-token"]').attr("content");
    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus : 1,
        submit: function(e,v,m,f){
            if(v)
            {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': token
                    },
                    type: "DELETE",
                    url: url,
                    success: function(data){
                        if(data.success == 1)
                        {
                            myTable.ajax.reload();
                            toastr.success(data.message);
                        }
                        else
                        {
                            toastr.error("Something went wrong!");
                        }
                    }
                });
            }
            $.prompt.close();
        }
    });
});
