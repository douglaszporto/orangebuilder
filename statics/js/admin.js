$(document).ready(function(){

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
        $(".datagrid-item-open").click(function(){
            $("#form").addClass("active");
            $("#background").addClass("active");

            $(".form-new-item").addClass("hidden");
            $(".form-edit-item").removeClass("hidden");
        });

        $("#btn-new").click(function(){
            $("#form").addClass("active");
            $("#background").addClass("active");

            $(".form-new-item").removeClass("hidden");
            $(".form-edit-item").addClass("hidden");
        });

        $("#form-close").click(function(e){
            e.stopPropagation();
            e.preventDefault();

            $("#form").removeClass("active");
            $("#background").removeClass("active");
        })
    }




    formFilterEvents();
    datagridEvents();
    checkMenuSelected();
});