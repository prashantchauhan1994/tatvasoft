$(document).ready(function () {

    'use strict';


    // ------------------------------------------------------- //
    // Sidebar Functionality
    // ------------------------------------------------------ //
    $('#toggle-btn').on('click', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');

        $('.side-navbar').toggleClass('shrinked');
        $('.content-inner').toggleClass('active');
        $(document).trigger('sidebarChanged');

        if ($(window).outerWidth() > 1183) {
            if ($('#toggle-btn').hasClass('active')) {
                $('.navbar-header .brand-small').hide();
                $('.navbar-header .brand-big').show();
            } else {
                $('.navbar-header .brand-small').show();
                $('.navbar-header .brand-big').hide();
            }
        }

        if ($(window).outerWidth() < 1183) {
            $('.navbar-header .brand-small').show();
        }
    });

    // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function() { return $(this).val() !== ""; }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        $(this).siblings('.label-material').addClass('active');
    });

    // remove/keep label on blur
    materialInputs.on('blur', function () {
        $(this).siblings('.label-material').removeClass('active');

        if ($(this).val() !== '') {
            $(this).siblings('.label-material').addClass('active');
        } else {
            $(this).siblings('.label-material').removeClass('active');
        }
    });

    $.each($('input.input-material'),function(){
        var $this = $(this);
        if ($this.val()) {
            $(this).siblings('.label-material').addClass('active');
        }
        else
            $(this).siblings('.label-material').removeClass('active');
    });

    // ------------------------------------------------------- //
    // Footer
    // ------------------------------------------------------ //

    var contentInner = $('.content-inner');

    $(document).on('sidebarChanged', function () {
        adjustFooter();
    });

    $(window).on('resize', function () {
        adjustFooter();
    })

    function adjustFooter() {
        var footerBlockHeight = $('.main-footer').outerHeight();
        contentInner.css('padding-bottom', footerBlockHeight + 'px');
    }

    if(emsg && emsg!=""){
        if(ecls == "error"){
            toastr.error(emsg);
        }else{
            toastr.success(emsg);
        }
    }
});


function deleteData(title,body,url,table)
{
    var token = $('input[name="_token"]').val();
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
                            table.ajax.reload();
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
}

function updateStatus(title,body,url,id)
{
    var token = $('input[name="_token"]').val();
    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus : 1,
        submit: function(e,v,m,f){
            if(v)
            {
                e.preventDefault();
                $('.loader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': token
                    },
                    type: "POST",
                    url: url,
                    data: {id:id},
                    success: function(data){
                        $('.loader').hide();
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
}
