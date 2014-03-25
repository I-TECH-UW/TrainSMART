/**
 * jQuery eComboBox - An editable Combo Box Plugin - http://www.reckdesign.de/eComboBox
 * 
 * @author  Recep Karadas
 * @version 1.0
 *
 * jQuery eComboBox generates editable Combo Boxes.
 *
 * Licensed under The MIT License
 *
 * @version			1.0
 * @since			22.05.2011
 * @author			Recep Karadas
 * @documentation	http://www.reckdesign.de/eComboBox
 * @license			http://opensource.org/licenses/mit-license.php
 *
 * Usage with default values:
 * ---------------------------------------------------------------------------------
 * $('#eComboBox').eComboBox();
 *
 * <select id="eComboBox">
 * 	<option>Value 1</option>
 * 	<option>Value 2</option>
 * 	<option>Value 3</option>
 * 	<option>Value 4</option>
 * </select>
 *
 */

(function( $ ){
  
  var methods = {
     init : function( options ) {
		
		var settings = {
			'allowNewElements' : true,
			'editableElements' : true,
			'textBoxFocusColor': '#F1F1F1'
		};
		     
		
		return this.each(function() { 
			
			var wrapperElement = null;
			var selectElement = null;  
			selectElement = $(this);
			var selected = "";
			// If options exist, lets merge them
			// with our default settings
			if ( options ) { 
				$.extend( settings, options );
			}
		  
			$(this).data("settings", settings);
			
			if (settings.allowNewElements){
				selectElement.prepend('<option>[Add new option]</option>');
			}
		  
			// Create Wrapper Element 
			var wrapperEl = document.createElement('div');
			wrapperElement = jQuery(wrapperEl);
			wrapperElement.css({
				"float" : "left",
				"width" : "250px"
			});
		  
			// Create Input Element
			var inputEl = document.createElement('input');
			inputEl.type = "text";
			var inputElement = jQuery(inputEl);
		  
			// put input and select element in wrapper element
			selectElement.before(wrapperElement);
			wrapperElement.append(inputElement).append(selectElement);
		  
			
			inputElement.css({
				"position" : "absolute",
				"display" : "none"
			});
		  
		  
			resizeElements();
		  
			selectElement.keydown( function(e){
				//alert(e.keyCode);
				if(e.keyCode >= 37 && e.keyCode <=40  || e.keyCode == 13) // arrow buttons or enter button
					return ;
					
				selected = $(this).val();
				
				if(e.keyCode == "46"){ // del-button
					if(selected != "[Add new option]"){
						$(this).children("option:selected").remove();
					}
					return;
				}
				
				if( selected=="[Add new option]" || settings['editableElements'] ) {
					inputElement.css({"display":"inline"});
					if(selected=="[Add new option]"){
						inputElement.val( "" ).focus();
					}else if(settings['editableElements']){
						inputElement.val( $(this).val() ).focus();
					}
				}
			});
				
			selectElement.change( function(e){
				if($(this).val()=="[Add new option]"){
					selected = $(this).val();
					inputElement.css({"display":"inline"});
					inputElement.val( "" ).focus();
				}
			});
			
			
/*
			inputElement.keyup(function(e){
				if(e.keyCode == 13){ //enter
					if(selected=="[Add new option]" ){
						if ($(this).val() != ""){
							selectElement.append('<option>'+$.trim($(this).val())+'</option>');
							selectElement.val($(this).val());
						}
					}else{
						if( $(this).val() == "" ) {
							selectElement.children('option:selected').remove();
						}else{
							selectElement.children('option:selected').text($(this).val());
						}
					}
					$(this).hide();
					selectElement.focus();
				}
				resizeElements();
			});
*/

			inputElement.blur(function(e ) {
			//if(e.keyCode == 13){ //enter 
				if ((!isNumber($(this).val())) && ($(this).val()!='0') && ($(this).val()!=0)){
					if(selected=="[Add new option]" ){
						if ($(this).val() != ""){
							
							selectElement.append('<option value="'+$.trim($(this).val())+'">'+$.trim($(this).val())+'</option>');
							selectElement.val($.trim($(this).val()));
						}
					} else {
						if( $(this).val() == "" ) {
							selectElement.children( 'option:selected ').remove();
						} else {
							selectElement.children( 'option:selected ').text($(this).val());
						}
					}
					$(this).hide();
					selectElement.focus();
			//}
					resizeElements( );
				}
			});
		  
			function resizeElements(){
				wrapperElement.css({
					"width" : selectElement.outerWidth()
				});
				inputElement.css({
					"width" : selectElement.outerWidth()
				});
			 }
		  
		  
		}); // END RETURN 
	 },
     destroy : function( ) {
		$(this).parent().remove();
	 },
	 disableAddingNewElements: function(){
		var fChild = $(this).children().first();
		if(fChild.text() == "[Add new option]"){
			fChild.remove();
		}
	 },
	 enableAddingNewElements: function(){
		var fChild = $(this).children().first();
		if(fChild.text() != "[Add new option]"){
			$(this).prepend('<option>[Add new option]</option>');
		}
	 },
	 disableEditingElements: function(){
		$(this).data('settings').editableElements = false;
	 },
	 enableEditingElements: function(){
		$(this).data('settings').editableElements = true;
	 }
  };

  
  $.fn.eComboBox = function( method ) {
    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.eComboBox' );
    }    
  
  };

})( jQuery );

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}