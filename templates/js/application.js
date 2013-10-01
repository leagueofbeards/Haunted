$(document).ready(function() {
	$(".tagsinput").tagsInput();
		
	$('.search i').click(function() {
		$('#content_search').css('background', '#efefef');
	});
		
	$('body').on( 'click', '#contents li', function() {
		$('#contents li').each(function() {
			$(this).removeClass('selected');
		});
		
		$(this).addClass('selected');
		
		var url = $(this).data('url');
		$.post( url, function(vars) {
			if( vars.response_code == 200 ) {
				var args = $.parseJSON( vars.data.post );
				$('.post_content').hide().html( args.content ).fadeIn();
				$('.type').hide().html( vars.data.type ).fadeIn();
				$('.status').hide().html( vars.data.status ).fadeIn();
				$('.edit_link').attr( 'href', vars.data.edit_link );
			}
		});
		
		return false;
	});

	$('.icon-search').click(function() {
		$('#search').focus();
	});

	$('#content_type').on('change', function() {
		$('#filter_contents').submit();
	});

	$('#search').on('blur', function() {
		$('#filter_contents').submit();
	});

	$('#editor_area').on('scroll', function () {
    	$('.post').scrollTop( $(this).scrollTop() );
	});

	$('.post').on('scroll', function () {
    	$('#editor_area').scrollTop( $(this).scrollTop() );
	});
	
	$('#save').on('click', function() {
		var data = $('#edit_form').serialize();
		var url = $('#edit_form').attr('action');
		$('.results span').html( '<i id="progress" class="icon-spinner icon-spin icon-large"></i>' );
		
		$.post( url, data, function(r) {
			if( r.response_code = 200 ) {
				$('.results span').hide().html( r.message ).fadeIn();
			}
		});
		
		return false;
	});

	$('#draft').on('click', function() {
		var data = $('#edit_form').serialize();
		var url = $('#edit_form').attr('action');
		$('.results span').html( '<i id="progress" class="icon-spinner icon-spin icon-large"></i>' );
		
		$.post( url, data, function(r) {
			if( r.response_code = 200 ) {
				$('.results span').hide().html( r.message ).fadeIn();
			}
		});
		
		return false;
	});

	$('#publish').on('click', function() {
		var data = $('#edit_form').serialize();
		var url = $('#edit_form').attr('action') + '&state=publish';
		$('.results span').html( '<i id="progress" class="icon-spinner icon-spin icon-large"></i>' );
		
		$.post( url, data, function(r) {
			if( r.response_code = 200 ) {
				$('.results span').hide().html( r.message ).fadeIn();
			}
		});
		
		return false;
	});
});

// Function to serialize a form to JSON
(function( $ ){
	$.fn.serializeJSON=function() {
		var json = {};
		jQuery.map($(this).serializeArray(), function(n, i) {
			json[n['name']] = n['value'];
		});
		
		return json;
	};
})( jQuery );

var handleAjaxResponse = function() {	
}

// Function to return a function that will update a table using ajax-json data, based on filters supplied in a form
var getData = function(selector, updateURL, templateId, filterFormSelector, no_result_message, paginator_selector) {
	var container = $(selector);
	var t = tmpl(templateId);
	var filterForm = $(filterFormSelector);
	var paginator = $(paginator_selector);
	
	if(no_result_message == undefined) {
		no_result_message = 'There are no results.';
	}
	
	r = function() {
		$.get(
			updateURL,
			filterForm.serializeJSON(),
			function(data) {
				container.find('li').remove();
				if(data.message) {
					flash.msg(data.message);
				}
				
				if(data.data && data.data.rows) {
					var rows = data.data.rows;
					if(rows.length == 0) {
						$(container).append($('<li>' + no_result_message + '</li>'));
					}
					
					for(var z in rows) {
						try{
							$(container).append(
								t(rows[z])
							);
						}
						catch (e) {
							console.log(e);
						}						
					}
					
					$('#contents li:first').addClass('selected');
					$('#contents li:last').addClass('push');
				} else {
					$(container).append($('<li>There was an error obtaining your content. Please try again.</li>'));
				}
			}
		);
	};

	filterForm.submit(function(){
		r();
		return false;
	});
	
	return r;
};