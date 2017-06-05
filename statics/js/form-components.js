(function( $ ) {


    var comboboxRecalculateHeight = function(numItems, combobox, comboboxNano, select){
        var calcHeight = Math.min(numItems, 6) * 40; // each option has 40px height
        
        combobox.css("height", calcHeight);
        comboboxNano.css("height", calcHeight);

        select.parent().find(".nano").nanoScroller();

        return calcHeight;
    };

    var filterOptions = function(filter, combobox, comboboxNano, select){
        var value = filter.val();

        numItems = 0;
        filter.parent().find(".input-select-option").each(function(){
            var optVal = $(this).text();

            if(optVal.indexOf(value) < 0) {
                $(this).addClass("hidden");
            } else {
                $(this).removeClass("hidden");
                numItems++;
            }
        });

        comboboxRecalculateHeight(numItems, combobox, comboboxNano, select);
    }




    $.fn.inputSelectComponent = function() {
        this.each(function() {
            var select              = $(this);
            var wrapper             = $("<div/>");
            var filter              = $("<input/>");
            var combobox            = $("<div/>");
            var comboboxNano        = $("<div/>");
            var comboboxNanoContent = $("<div/>");
            var numOfOptions        = 0;

            select.addClass("hidden");
            wrapper.addClass("input-select-wrapper");
            combobox.addClass("input-select-combobox");

            $(this).find("option").each(function(){
                var opt  = $("<div/>");
                var self = $(this);

                opt.html($(this).html());
                opt.attr("data-value", $(this).attr("val"));
                opt.addClass("input-select-option");

                if(self.attr('selected') == 'selected')
                    filter.val($(this).html());

                opt.click(function(e){
                    select.find("option[selected]").removeAttr("selected");
                    self.attr("selected","selected");

                    filter.val($(this).text());
                    filter.focus().blur();
                });

                numOfOptions++;
                comboboxNanoContent.append(opt);
            });

            comboboxNano.addClass("nano");
            comboboxNanoContent.addClass("nano-content");

            comboboxNano.append(comboboxNanoContent);
            combobox.append(comboboxNano);

            filter.addClass("input-select-filter");
            filter.attr("type","text");
            filter.focus(function(e){
                numOfOptions = $(this).parent().find(".input-select-option:not(.hidden)").length;
                var selectHeight = comboboxRecalculateHeight(numOfOptions, combobox, comboboxNano, select);

                selectHeight += 50; // Input filter height;

                combobox.addClass("active");

                var availableHeight = $("#content").height() - selectHeight;

                if(filter.offset().top >= availableHeight)
                    combobox.addClass("from-bottom");
            });
            filter.blur(function(){
                combobox.css("height",0);
                combobox.removeClass("active");
            });
            filter.keyup(function(){
                filterOptions(filter, combobox, comboboxNano, select);
            });

            var widthRaw = select.css('width');
            var width = widthRaw.indexOf('%') > 0 ? (select.parent().width() * select.width()/100) : select.width();
            wrapper.css('width', width);
            
            wrapper.append(filter);
            wrapper.append(combobox);

            select.after(wrapper);
        });

        return this;
    };



    $.fn.inputSelectComponentNoFilter = function() {
        this.each(function() {
            var select              = $(this);
            var wrapper             = $("<div/>");
            var value               = $("<div/>");
            var combobox            = $("<div/>");
            var comboboxNano        = $("<div/>");
            var comboboxNanoContent = $("<div/>");
            var numOfOptions        = 0;

            select.addClass("hidden");
            wrapper.addClass("input-select-wrapper");

            combobox.addClass("input-select-combobox");
            $(this).find("option").each(function(){
                var opt  = $("<div/>");
                var self = $(this);

                opt.html($(this).html());
                opt.attr("data-value", $(this).attr("val"));
                opt.addClass("input-select-option");

                if(self.attr('selected') == 'selected'){
                    value.html($(this).html());
                }

                opt.click(function(e){                    
                    select.find("option[selected]").removeAttr("selected");
                    self.attr("selected","selected");

                    value.html($(this).text());

                    select.trigger('change');
                });

                numOfOptions++;
                comboboxNanoContent.append(opt);
            });

            comboboxNano.addClass("nano");
            comboboxNanoContent.addClass("nano-content");

            comboboxNano.append(comboboxNanoContent);
            combobox.append(comboboxNano);

            value.addClass("input-select-value");
            value.click(function(e){
                e.stopPropagation();

                numOfOptions = $(this).parent().find(".input-select-option:not(.hidden)").length;
                var selectHeight = comboboxRecalculateHeight(numOfOptions, combobox, comboboxNano, select);

                selectHeight += 50;

                combobox.addClass("active");

                var availableHeight = $("#content").height() - selectHeight;

                if(value.offset().top >= availableHeight)
                    combobox.addClass("from-bottom");
            });

            $('body').click(function(){
                combobox.css("height",0);
                combobox.removeClass("active");
            });

            var widthRaw = select.css('width');
            var width = widthRaw.indexOf('%') > 0 ? (select.parent().width() * select.width()/100) : select.width();
            wrapper.css('width', width);
            
            wrapper.append(value);
            wrapper.append(combobox);

            select.after(wrapper);
        });

        return this;
    };





    $.fn.inputCheckboxComponent = function() {
        this.each(function() {
            var checkbox  = $(this);
            var wrapper   = $("<div/>");
            var indicator = $("<div/>");

            wrapper.addClass("input-checkbox-wrapper");
            indicator.addClass("input-checkbox-indicator");
            checkbox.addClass("input-checkbox-checkbox");

            indicator.click(function(){
                var checkbox = $(this).parent().find("input");
                checkbox.prop('checked', !checkbox.prop('checked'));
                checkbox.trigger('change');
            });

            wrapper.append(checkbox.clone());
            wrapper.append(indicator);

            checkbox.after(wrapper);
            checkbox.remove();

        });

        return this;
    };


    $.fn.inputDateComponent = function(){
        var $wrapper = $("<div />");;
        var $icon    = $("<div />");
        var $input   = $(this).clone(true)

        $wrapper.addClass("data-input-datepicker-wrapper");
        $icon.addClass("data-input-datepicker-icon");
        $input.addClass("data-input-datepicker-input");

        var widthRaw = $input.css('width');
        var width = widthRaw.indexOf('%') > 0 ? ($input.parent().width() * $input.width()/100) : $input.width();
        
        $wrapper.css('width', width);

        $input.datepicker({
            'dateFormat'      : 'dd/mm/yy',
            'dayNames'        : ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
            'dayNamesMin'     : ["D", "S", "T", "Q", "Q", "S", "S"],
            'dayNamesShort'   : ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            'monthNames'      : ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            'monthNamesShort' : ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"]
        });

        $input.attr('placeholder', 'dd/mm/yyyy');
        $input.on('change', function(){
            var dt = $(this).val().replace(/[^\d]/gi,'');

            if(dt.length < 8){
                $(this).val('');
                return;
            }

            var day   = parseInt(dt.substr(0,2),10);
            var month = parseInt(dt.substr(2,2),10) - 1;
            var year  = parseInt(dt.substr(4,4),10);

            var date = new Date(year, month, day, 0, 0, 0, 0);

            console.log(date);

            if(date.getDate() != day || date.getMonth() != month || date.getFullYear() != year){
                $(this).val('');
                return;
            }

            var formatedDay   = ('00' + day).substr(-2);
            var formatedMonth = ('00' + (month+1)).substr(-2);

            $(this).val(formatedDay + '/' + formatedMonth + '/' + year);
        });

        $wrapper.append($icon);
        $wrapper.append($input);

        $(this).after($wrapper);
        $(this).remove();
    };





    $.fn.dataInputPagination = function(){
        $(this).on('click', function(e){
            e.stopPropagation();
            e.preventDefault();

            if($(this).hasClass('disabled'))
                return;

            $('#input-page').val($(this).attr('data-input-pagination'));
            $('#filter-form').submit();
        });
    };
    $.fn.dataInputPaginationByPage = function(){
        $(this).on('change', function(e){
            e.stopPropagation();
            e.preventDefault();
            
            $('#input-bypage').val($(this).val());
            $('#filter-form').submit();
        });
    };
    $.fn.dataInputColumnSort = function(){
        $(this).on('click', function(e){
            e.stopPropagation();
            e.preventDefault();

            console.log($('#input-orderdir').val());
            var currentOrderDir = $('#input-orderdir').val();
            var orderDir = currentOrderDir == 'desc' ? 'asc' : 'desc';

            console.log($(this).attr('data-input-columnsort') + ' = ' + orderDir);

            $('#input-orderby').val($(this).attr('data-input-columnsort'));
            $('#input-orderdir').val(orderDir);
            $('#filter-form').submit();
        });
    };
}(jQuery));