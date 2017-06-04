$(document).ready(function(){

    $("body").removeClass("preload");

    var checkMenuSelected = function(){
        var url = window.location.href.split('?')[0];

        $("nav a").each(function(){
            if($(this).attr('href') === url){
                $(this).parent().addClass("selected");
                return false;
            }
        });
    };


    var formFilterEvents = function(){
        $("[data-input-select]").inputSelectComponent();
        $("[data-input-checkbox]").inputCheckboxComponent();

        $("#form-button-add, #form-button-edit").click(function(){
            $("#form form").submit();
        });
    };



    var datagridEvents = function(){
        $("button[data-href]").click(function(){
            var form = $(this).attr("data-form");
            
            if(typeof form !== 'undefined'){
                $("#"+form).attr('action', $(this).attr('data-href'));
                $("#"+form).submit();
            } else {
                window.location = $(this).attr('data-href');
            }
        });

        $("#btn-filter").click(function(e){
            e.preventDefault();
            e.stopPropagation();

            $(this).parents("form").submit();
        });

        $("#btn-extra").click(function(e){
            e.stopPropagation();
            e.preventDefault();
            $("#actions-extra-container").addClass('active');
        });

        $("#datagrid-select-all").on('change', function(){
            var isSelected = $(this).prop("checked");

            $(".datagrid-selected-items").each(function(){
                $(this).prop("checked", isSelected);
            });
        });

        $(".datagrid-selected-items, #datagrid-select-all").on('change', function(){
            var checkedCount = 0;
            $(".datagrid-selected-items").each(function(){
                if($(this).prop("checked") === true)
                    checkedCount++;
            });

            $("[data-operation-visibility]").each(function(){
                var visibility = parseInt($(this).attr('data-operation-visibility'), 10);

                $(this).attr('disabled','disabled');
                if(checkedCount == 0 && (visibility & 4))
                    $(this).removeAttr('disabled');

                if(checkedCount == 1 && (visibility & 2))
                    $(this).removeAttr('disabled');

                if(checkedCount > 1 && (visibility & 1))
                    $(this).removeAttr('disabled');
            });
        })
    };


    var bodyResetEvents = function(){
        $("body").click(function(){
            $("#actions-extra-container").removeClass('active');
        });
    };




    formFilterEvents();
    datagridEvents();
    checkMenuSelected();
    bodyResetEvents();
});