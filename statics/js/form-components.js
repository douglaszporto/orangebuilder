(function( $ ) {
    $.fn.inputSelectComponent = function() {
        this.each(function() {
            var select              = $(this);
            var wrapper             = $("<div/>");
            var filter              = $("<input/>");
            var combobox            = $("<div/>");
            var comboboxNano        = $("<div/>");
            var comboboxNanoContent = $("<div/>");
            var numOfOptions        = 0;

            var isUnfilterable = typeof select.attr('data-input-unfilterable') !== 'undefined';

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

                    if(isUnfilterable)
                        filter.removeAttr('readonly');
                    
                    filter.val($(this).text());
                    filter.focus().blur();

                    if(isUnfilterable)
                        filter.attr('readonly','readonly');

                });

                numOfOptions++;
                comboboxNanoContent.append(opt);
            });

            comboboxNano.addClass("nano");
            comboboxNanoContent.addClass("nano-content");

            comboboxNano.append(comboboxNanoContent);
            combobox.append(comboboxNano);

            var comboboxRecalculateHeight = function(numItems){
                var calcHeight = Math.min(numItems, 6) * 40; // each option has 40px height
                
                combobox.css("height", calcHeight);
                comboboxNano.css("height", calcHeight);

                select.parent().find(".nano").nanoScroller();

                return calcHeight;
            };

            var filterOptions = function(){
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

                comboboxRecalculateHeight(numItems);
            }

            filter.addClass("input-select-filter")
            filter.attr("type","text");
            filter.focus(function(e){
                numOfOptions = $(this).parent().find(".input-select-option:not(.hidden)").length;
                var selectHeight = comboboxRecalculateHeight(numOfOptions);

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
            if(isUnfilterable){
                filter.attr('readonly','readonly');
            } else {
                filter.keyup(function(){
                    filterOptions();
                });
            }

            var widthRaw = select.css('width');
            var width = widthRaw.indexOf('%') > 0 ? (select.parent().width() * select.width()/100) : select.width();
            wrapper.css('width', width);
            
            wrapper.append(filter);
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
}(jQuery));