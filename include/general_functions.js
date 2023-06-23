function uncheck_other_homepage_checkboxes(index){
	if ( $("#is_homepage_static_page_"+index).is(':checked') === true ){
		for (i=1;i<=Number($("#number_items").val());i++){
			if ( i != index){
				$("#is_homepage_static_page_"+i).prop('checked', false);
			}
		}
	}
}
function enable_disable_loader(action)
{
	if (action === 'enable'){
		 $("body").addClass("loading"); 
	}
	else if(action === 'disable'){
		 $("body").removeClass("loading"); 
	}
}
function enable_disable_loader_form_preview(action)
{
	if (action === 'enable'){
		 //$("body").addClass("loading"); 
	}
	else if(action === 'disable'){
		 //$("body").removeClass("loading"); 
	}
}
function remove_showing_confirmation_message_class()
{
	 $("body").removeClass("showing_confirmation_message");
}
function show_hide_csv_options(show_hide)
{
    if (show_hide == 1){
        $("#csv_options").css('display', 'block'); 
    }
    else{
        $("#csv_options").css('display', 'none'); 
    
    }
}

function show_admin_help_old(title, content)
{
    if (document.all) {
        topoffset=document.body.scrollTop;
        leftoffset=document.body.scrollLeft;
        WIDTH=document.body.clientWidth;
        HEIGHT=document.body.clientHeight;
    } else {
        topoffset=pageYOffset;
        leftoffset=pageXOffset;
        WIDTH=window.innerWidth;
        HEIGHT=window.innerHeight;
    }

    newtop=((HEIGHT-200)/2)+topoffset;
    
    if (document.all) {
        newleft=150;
    } else {
        newleft=((WIDTH-400)/2)+leftoffset;
    }

    document.getElementById('div_help').style.left=newleft;
    document.getElementById('div_help').style.top=newtop;

    document.getElementById('div_help_content_title').innerHTML = '<div align="right""><a href="javascript:hide_help();" style="text-decoration:none;color:#ff7700">[X]&nbsp;&nbsp;&nbsp;</a></div>'+title;
    document.getElementById('div_help_content_text').innerHTML = content;
    document.getElementById('div_help').style.display = 'block';
}


function show_admin_help(title, content)
{
	document.getElementById('dialog').innerHTML = content;
	
	
	$( "#dialog" ).dialog({
	  width: 500,
	  modal: true
	});
    
    $('#dialog').dialog('option', 'title', title);
}

function show_admin_help_resize(title, content, width)
{
	document.getElementById('dialog').innerHTML = content;
	
	
	$( "#dialog" ).dialog({
	  width: width,
	  modal: true,
	  close: function( event, ui ) {$('#dialog').children('iframe').attr('src', '');} // to stop youtube videos
	});
    
    $('#dialog').dialog('option', 'title', title);
}


function hide_help()
{
    document.getElementById('div_help').style.display = 'none';
    document.getElementById('div_help_content_title').innerHTML = "";
    document.getElementById('div_help_content_text').innerHTML = "";
    
    
}

function show_frontend_help(title, content)
{
    if (document.all) {
        topoffset=document.body.scrollTop;
        leftoffset=document.body.scrollLeft;
        WIDTH=document.body.clientWidth;
        HEIGHT=document.body.clientHeight;
    } else {
        topoffset=pageYOffset;
        leftoffset=pageXOffset;
        WIDTH=window.innerWidth;
        HEIGHT=window.innerHeight;
    }

    newtop=((HEIGHT-200)/2)+topoffset;
    
    if (document.all) {
        newleft=150;
    } else {
        newleft=((WIDTH-400)/2)+leftoffset;
    }

    document.getElementById('div_help_frontend').style.left=newleft;
    document.getElementById('div_help_frontend').style.top=newtop;

    document.getElementById('div_help_frontend_content_title').innerHTML = '<div align="right""><a href="javascript:hide_frontend_help();" style="text-decoration:none;color:#ff7700">[X]&nbsp;&nbsp;&nbsp;</a></div>'+title;
    document.getElementById('div_help_frontend_content_text').innerHTML = content;
    document.getElementById('div_help_frontend').style.display = 'block';
}

function hide_frontend_help()
{
    document.getElementById('div_help_frontend').style.display = 'none';
    document.getElementById('div_help_frontend_content_title').innerHTML = "";
    document.getElementById('div_help_frontend_content_text').innerHTML = "";
}

function show_template_instructions(title)
{
	$("#dialog").load('include/forms/template_help_content.html').dialog({ width: 500,modal:true}); 
    $('#dialog').dialog('option', 'title', title);
}

function show_hide_date_functions_select(){

	var type=$('option:selected', $('#group_by_field')).attr('field_type');
	
	
	if ( type == 'date' || type == 'date_time' || type == 'update_date_time' || type == 'insert_date_time'){
		$('#date_functions_container').css('display', 'inline-block');
	}
	else {
		$('#date_functions_container').css('display', 'none')
	};

}

//opens a js popup with customizable options. Popup will close and open
//again upon call from pwd-generator link
var mywindow;
function generic_js_popup(url,name,w,h){
	if (mywindow!=null && !mywindow.closed){
	mywindow.close();
	}
var options;
options = "resizable=yes,toolbar=0,status=1,menubar=0,scrollbars=yes, width=" + w + ",height=" + h + ",left="+(screen.width-w)/2+",top="+(screen.height-h)/6; 
mywindow = window.open(url,name,options);
mywindow.focus();
}

function enable_disable_input_box_insert_edit_form(null_checkbox_prefix, year_field_suffix, month_field_suffix, day_field_suffix, hours_field_suffix, minutes_field_suffix, seconds_field_suffix)
// goal: set the status (disabled|enabled) of each input element of the insert|edit form, depending on the status (checked|not checked) of the corresponding null value checkbox (if it exists)
// input: null_checkbox_prefix, year_field_suffix, month_field_suffix, day_field_suffix
{
	
	if (document.getElementById('dadabik_main_form') !== null){ // otherwise there is no form, e.g. in case of lost lock
	var count = document.getElementById('dadabik_main_form').length;
	var null_checkbox_prefix_length = null_checkbox_prefix.length;

	// for each element of the form
	for (i=0;i<count;i++)
	{
		// if the element is a null value checkbox element
		if (document.getElementById('dadabik_main_form').elements[i].name.substr(0,null_checkbox_prefix_length) == null_checkbox_prefix && document.getElementById('dadabik_main_form').elements[i].disabled === false){
		
			// check if the field is a datetime field type

			var hours_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + hours_field_suffix;
				
			var b = new Array;
			b = document.getElementsByName(hours_field_name);

			// check if the field is a date field type

			var year_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + year_field_suffix;
				
			var a = new Array;
			a = document.getElementsByName(year_field_name);
			
			var field_type_is_date = 0;
			var field_type_is_date_time = 0;
			

			if (b[0]){ // if the relative hours field exists
				field_type_is_date_time = 1;
			} // end if
			else if (a[0]){ // if the relative year field exists
				field_type_is_date = 1;
			} // end if
			

			if (field_type_is_date == 1 || field_type_is_date_time == 1){
				// get the name of the relative input controls

				var month_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + month_field_suffix;

				var day_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + day_field_suffix;
				
				if (field_type_is_date_time == 1){

					var minutes_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + minutes_field_suffix;

					var seconds_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + seconds_field_suffix;
				}

				// and set the relative input controls enabled/disabled depending on the null value checkbox status (checked|not checked)
				var a = new Array;
				a = document.getElementsByName(year_field_name);
				
				var b = new Array;
				b = document.getElementsByName(month_field_name);

				var c = new Array;
				c = document.getElementsByName(day_field_name);
				
				if (field_type_is_date_time == 1){
				
				var d = new Array;
				d = document.getElementsByName(hours_field_name);
				
				var e = new Array;
				e = document.getElementsByName(minutes_field_name);

				var f = new Array;
				f = document.getElementsByName(seconds_field_name);
				
				}
				if (document.getElementById('dadabik_main_form').elements[i].checked == true){
					a[0].disabled = true;
					b[0].disabled = true;
					c[0].disabled = true;
					
					if (field_type_is_date_time == 1){
					
						d[0].disabled = true;
						e[0].disabled = true;
						f[0].disabled = true;
					
					}
				} // end if
				else{
					a[0].disabled = false;
					b[0].disabled = false;
					c[0].disabled = false;
					
					if (field_type_is_date_time == 1){
						d[0].disabled = false;
						e[0].disabled = false;
						f[0].disabled = false;
					
					}

				} // end else
			} // end if
			else {
				// get the name of the relative input control
				var field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length);

				// and set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
				var a = new Array;
				a = document.getElementsByName(field_name);

				if (document.getElementById('dadabik_main_form').elements[i].checked == true){
					a[0].disabled = true;
				} // end if
				else{
					a[0].disabled = false;
				} // end else
			} // end else
		} // end if
	} // end for
	}
} // end function

function enable_disable_input_box_search_form(field_name, select_type_select_suffix, year_field_suffix, month_field_suffix, day_field_suffix, hours_field_suffix, minutes_field_suffix, seconds_field_suffix)
// goal: set the status (disabled|enabled) of an input element of the search form, depending on the status of the corresponding select_type_select field
// input: field_name, select_type_select_suffix, year_field_suffix, month_field_suffix, day_field_suffix
{
	
	// check if the field is a date field type

	var year_field_name = field_name + year_field_suffix;
	var hours_field_name = field_name + hours_field_suffix;
	
	// check if the field is a datetime field type
		
	var b = new Array;
	b = document.getElementsByName(hours_field_name);

	// check if the field is a date field type
		
	var a = new Array;
	a = document.getElementsByName(year_field_name);
	
	var field_type_is_date = 0;
	var field_type_is_date_time = 0;
	

	if (b[0]){ // if the relative hours field exists
		field_type_is_date_time = 1;
	} // end if
	else if (a[0]){ // if the relative year field exists
		field_type_is_date = 1;
	} // end if
	

	if (field_type_is_date == 1 || field_type_is_date_time == 1){
		// get the name of the relative input controls

		var month_field_name = field_name + month_field_suffix;

		var day_field_name = field_name + day_field_suffix;
				
		if (field_type_is_date_time == 1){

			var minutes_field_name = field_name + minutes_field_suffix;

			var seconds_field_name = field_name + seconds_field_suffix;
		}

		// and set the relative input controls enabled/disabled depending on the null value checkbox status (checked|not checked)
		var a = new Array;
		a = document.getElementsByName(year_field_name);

		var b = new Array;
		b = document.getElementsByName(month_field_name);

		var c = new Array;
		c = document.getElementsByName(day_field_name);
		
		
				
		if (field_type_is_date_time == 1){
				
				var d = new Array;
				d = document.getElementsByName(hours_field_name);
				
				var e = new Array;
				e = document.getElementsByName(minutes_field_name);

				var f = new Array;
				f = document.getElementsByName(seconds_field_name);
				
		}

		var g = new Array;
		g = document.getElementsByName(field_name+select_type_select_suffix);

		if (g[0].value == 'is_null'){
			a[0].disabled = true;
			b[0].disabled = true;
			c[0].disabled = true;
					
					if (field_type_is_date_time == 1){
					
						d[0].disabled = true;
						e[0].disabled = true;
						f[0].disabled = true;
					
					}
		} // end if
		else{
			a[0].disabled = false;
			b[0].disabled = false;
			c[0].disabled = false;
					
					if (field_type_is_date_time == 1){
					
						d[0].disabled = false;
						e[0].disabled = false;
						f[0].disabled = false;
					
					}
		} // end else
	} // end if
	else{
		// set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
		var a = new Array;
		a = document.getElementsByName(field_name);

		var b = new Array;
		b = document.getElementsByName(field_name+select_type_select_suffix);

		if (b[0].value == 'is_null' || b[0].value == 'is_empty' || b[0].value == 'is_not_null' || b[0].value == 'is_not_empty'){
			a[0].disabled = true;
		} // end if
		else{
			a[0].disabled = false;
		} // end else
	} // end else

} // end function

function getRadioValue(theRadioGroup)
{
    var elements = document.getElementsByName(theRadioGroup);
    for (var i = 0, l = elements.length; i < l; i++)
    {
        if (elements[i].checked)
        {
            return elements[i].value;
        }
    }
}

function show_hide_text_other(field_type)
// goal: show/hide the textbox close to select_single fields according to the "other" field selected or not
{
	
	var other_textbox_div_suffix =  '_other____';
	if (document.getElementById('dadabik_main_form') !== null){  // otherwise there is no form, e.g. in case of lost lock
	
	var count = document.getElementById('dadabik_main_form').length;
	var other_textbox_div_suffix_length = other_textbox_div_suffix.length;

	// for each element of the form
	for (i=0;i<count;i++)
	{
		var field_name_length = document.getElementById('dadabik_main_form').elements[i].name.length;
		
		// if the field is a other text field
		if (document.getElementById('dadabik_main_form').elements[i].name.substr(field_name_length-other_textbox_div_suffix_length) == other_textbox_div_suffix){
			// get the name of the relative input control
			var field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(0, field_name_length-other_textbox_div_suffix_length);
			
			// and set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
			var a = new Array;
			a = document.getElementsByName(field_name);
						
			var div_name = 'other_textbox_'+field_name;
			
			if (field_type == 'select_single'){
			    // when the form is in the duplication page, there is an additional field having same name, hidden, which is [0] 
			    // code to improve
			    if (a[0].type === 'hidden'){
				    var value_to_check = a[1].value;
				}
			    else{
				    var value_to_check = a[0].value;
				}
				    
			
			}
			else if(field_type === 'select_single_radio'){
				var value_to_check = getRadioValue(field_name);
			}
			
			if (value_to_check == '......'){
			
				 //document.getElementById(div_name).style.visibility = 'visible';
				 document.getElementById(div_name).style.display = 'block';
			} // end if
			else{
			
				 //document.getElementById(div_name).style.visibility = 'hidden';
				 document.getElementById(div_name).style.display = 'none';
			}
			
		} // end if
		
	} // end for
	}
} // end function

function show_hide_text_between()
// goal: show/hide an additional textbox according to the between field
{
	var other_textbox_div_suffix =  '_between____';
	
	execute_show_hide = 0;
	if ( document.getElementById('dadabik_main_form')){ // from search form
	    form_name = 'dadabik_main_form';
	    execute_show_hide = 1;
	}
	else if( document.getElementById('dadabik_quick_search_form')) { // from quick search form
	    form_name = 'dadabik_quick_search_form';
	    execute_show_hide = 1
	}
	
	if (execute_show_hide == 1) {
        var count = document.getElementById(form_name).length;
    
        var other_textbox_div_suffix_length = other_textbox_div_suffix.length;

        // for each element of the form
        for (i=0;i<count;i++)
        {
        
        
            var field_name_length = document.getElementById(form_name).elements[i].name.length;
            
            // if the field is a other text field
            if (document.getElementById(form_name).elements[i].name.substr(field_name_length-other_textbox_div_suffix_length) == other_textbox_div_suffix){
        
            
                // get the name of the main field
                var field_name = document.getElementById(form_name).elements[i].name.substr(0, field_name_length-other_textbox_div_suffix_length);
        
        
                // and set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
                var a = new Array;
                a = document.getElementsByName(field_name+js_select_type_select_suffix);
                        
                var div_name = 'between_textbox_'+field_name;
                var value_to_check = a[0].value;
            
                if (value_to_check == 'between'){
            
                     //document.getElementById(div_name).style.visibility = 'visible';
                     document.getElementById(div_name).style.display = 'block';
                } // end if
                else{
            
                     //document.getElementById(div_name).style.visibility = 'hidden';
                     document.getElementById(div_name).style.display = 'none';
                }
            
            } // end if
        
        } // end for
    }
} // end function

function execute_custom_function(url)
{
    enable_disable_loader('enable');
    
    $.ajax({
        url: url,

        data: ({}),
    
        type: "POST",
        dataType: "json",
        success: function(data){
    
            enable_disable_loader('disable');

            if (data.status === 'ok'){
                if (data.message !== ''){
                
                    $('#top_messages').html('<div class="'+data.class_message+'"><p>'+ data.message +'</p></div>');
                }
                else{
                 $("body").addClass("showing_confirmation_message");
                 window.setTimeout( remove_showing_confirmation_message_class, 1000); 
                 }
            }
            else{
                alert('unexpected error custom function ajax status not OK');
            }
        },
        error: function(data, status, e){
            enable_disable_loader('disable');
            alert('unexpected error custom function ajax call: '+JSON.stringify(data));
        }
    });
}