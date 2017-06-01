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

        $("#form-button-add, #form-button-edit").click(function(){
            $("#form form").submit();
        });
    };



    var datagridEvents = function(){
        $("#btn-new").click(function(){
            window.location = $(this).attr('data-href');
        });
    }




    formFilterEvents();
    datagridEvents();
    checkMenuSelected();
});