// filter by text, example   $('#select').filterByText($('#textbox'), true);
jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
    return this.each(function() {
        var select = this;
        var options = [];
        $(select).find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
        $(select).data('options', options);
        $(textbox).bind('change keyup', function() {
            var options = $(select).empty().data('options');
            var search = $.trim($(this).val());
            var regex = new RegExp(search,"gi");
          
            $.each(options, function(i) {
                var option = options[i];
                if(option.text.match(regex) !== null || option.value.match(regex) != null || option.value == "") { /*modified - checks .value also*/
                    $(select).append(
                       $('<option>').text(option.text).val(option.value)
                    );
                }
            });
            if (selectSingleMatch === true && $(select).children().length === 1) {
                $(select).children().get(0).selected = true;
            }
            $(select).get(0).scrollTop = 0;
        });            
    });
};
// filter by text with a underscore at the end simulating region_id
jQuery.fn.filterFacilityByText = function(textbox, selectSingleMatch) {
    return this.each(function() {
        var select = this;
        var options = [];
        $(select).find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
        $(select).data('options', options);
        $(textbox).bind('change keyup', function() {
            var options = $(select).empty().data('options');
            var search = $.trim($(this).val()) + '_';                // changed from above func
            var regex = new RegExp('^'+search,"gi");             // changed from above func
          
            $.each(options, function(i) {
                var option = options[i];
                if(option.text.match(regex) !== null || option.value.match(regex) != null || option.value == "") { /*modified - checks .value also*/
                    $(select).append(
                       $('<option>').text(option.text).val(option.value)
                    );
                }
            });
            if (selectSingleMatch === true && $(select).children().length === 1) {
                $(select).children().get(0).selected = true;
            }
            $(select).get(0).scrollTop = 0;
        });            
    });
};