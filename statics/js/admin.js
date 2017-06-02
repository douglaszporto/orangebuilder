$(document).ready(function(){

    $("body").removeClass("preload");

    var checkMenuSelected = function(){
        var url = window.location.href;

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
        $("#btn-new").click(function(){
            window.location = $(this).attr('data-href');
        });

        $("#datagrid-select-all").on('change', function(){
            var isSelected = $(this).prop("checked");

            $(".datagrid-selected-items").each(function(){
                $(this).prop("checked", isSelected);
            });
        })
    }




    formFilterEvents();
    datagridEvents();
    checkMenuSelected();
});