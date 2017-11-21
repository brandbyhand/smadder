(function($){

	/*
	*
	*
	* MANGLER !!!
	* Render field function skal tilføjes
	* layout number skal tilføjes
	* columns function skal køres efter tilføjet.
	*/

	acf.add_action('load', function( $el ){
		var overlayCheck = false;

		if(overlayCheck == false){
			$('body').append('<div class="bbh-popup-overlay">');
			var overlayCheck = true;
		}

		


		$('.acf-field-flexible-content .acf-flexible-content > .acf-actions a.acf-button').each(function(){

			var key = $(this).closest('.acf-field-flexible-content').attr('data-key');

			var html = $(this).closest('.acf-actions').siblings('.tmpl-popup').html();

			/*=========================================
			=            Build popup boxes            =
			=========================================*/			
			
			$('body').append('<div class="bbh-popup ' + key + '"></div>');
			var popup = $('.bbh-popup.' + key);
			$(popup).attr('data-key', key);
			$(popup).append(html);
			popup.wrapInner('<div class="popup-wrap">');
			var popup = popup.find('.popup-wrap');
			$(popup).find('.acf-fc-popup').removeClass('acf-fc-popup').addClass('bbh-layout-menu');
			$(popup).find('.bbh-layout-menu a.focus').remove();
			$('.bbh-popup a').off('click');



			popup.append(layout_material.markup);
			popup.find('.bbh-layout-menu').prepend('<div class="layout-menu-heading"><h3>Vælg en sektion</h3></div>');
			popup.find('.layout-info').prepend('<div class="window-controls"></div>');
			
			popup.find('.layout-info .window-controls').prepend('<span class="modal-info layout-picker-info"><span class="dashicons-info active" data-layout="welcome-message"></span></span>');

			
			popup.find('.layout-info .window-controls').append('<span class="media-modal-close layout-picker-close"><span class="media-modal-icon"></span></span>');

			var themepathImages = layout_material.themepath+'/include/flexible-content/images/';
			var themepathDescriptions = layout_material.themepath+'/include/flexible-content/descriptions/';
			var themepathWelcome = layout_material.themepath+'/include/flexible-content/welcome/';


			/*==============================================
			=            Build layouts in popup            =
			==============================================*/
			
			$(popup).find('.bbh-layout-menu li a').each(function(event){
				var currentPopup = $(this).closest('.bbh-popup');
				var dataLayout = $(this).attr('data-layout');
				var layoutTitle = $('.acf-field-flexible-content[data-key="'+key+'"]').find('.values > [data-layout="'+dataLayout+'"] > .acf-fc-layout-handle').clone().children().remove().end().text();
				var layoutTitle = 'Tilføj sektionen '+layoutTitle;
				var layoutImage = themepathImages+dataLayout+'.png';
				

				//
				$.ajax({
				    url: layoutImage,
				    type:'HEAD',
				    error: function()
				    {
				        return false;
				    },
				    success: function()
				    {
				        popup.find('.'+dataLayout+' .layout-image').append('<img src="'+layoutImage+'">');
				    }
				});

				$.ajax({
				    url: themepathDescriptions+dataLayout+'.php',
				    type:'HEAD',
				    error: function()
				    {
				    	return false;
				    },
				    success: function()
				    {
				        var layoutdescription = $.get(themepathDescriptions+dataLayout+'.php', function(data){
				        	$('.single-layout[data-layout="'+dataLayout+'"] .layout-description').append(data);
				        });
				    }
				});
				



				popup.find('.layout-inner').append('<div class="single-layout '+dataLayout+'" data-layout="'+dataLayout+'"><div class="layout-title"><h3>'+layoutTitle+'</h3></div><div class="layout-description"></div><div class="layout-image"></div><div class="add-layout"><a class="add-layout-button button-primary" href="" data-layout="'+dataLayout+'">Tilføj sektion</a></div></div>');
				
			})
			if(popup.find('.single-layout.welcome-message').length < 1){
				$.ajax({
				    url: themepathWelcome+'welcome.php',
				    type:'HEAD',
				    error: function()
				    {
				    	return false;
				    },
				    success: function()
				    {
				    	var welcomeTitle = 'Tilføj sektioner til siden';
				    	popup.find('.layout-inner').prepend('<div class="single-layout welcome-message" data-layout="welcome-message"><div class="layout-title"><h2>'+welcomeTitle+'</h2></div><div class="layout-description"></div><div class="layout-image"></div></div></div>');
				        var layoutdescription = $.get(themepathWelcome+'welcome.php', function(data){

				        	var dataUpdated = data.replace(/(\b([a-zA-Z0-9]+(.gif|.jpg|.png|.jpeg]$1)))/mig, themepathWelcome+'$1');

				        
				        	/*$(datamatch).each(function(i){
				        		data.replace(datamatch[i], themepathWelcome+datamatch[i]);
				        	});*/
				        	$('.single-layout.welcome-message .layout-description').append(dataUpdated);
				        });

				        
				        
				        
				        
				        $('.single-layout.welcome-message .layout-description').find('img').each(function(){
				        	var src = themepathWelcome + $(this).attr('src');
				        	console.log(src);
				        	$(this).attr('src', src);
				        })
				        var welcomeAdded = 1;
				    }
				});
			}


			/*==============================================
			=            Function - Close popup            =
			==============================================*/
			function closeLayoutPopup(event){
				event.preventDefault();
				var popup = $(event.target).closest('.bbh-popup');
				var overlay = $('.bbh-popup-overlay');
				var layouts = popup.find('.single-layout');
				var layoutMenu = popup.find('.bbh-layout-menu a');


				popup.fadeOut(function(){
					// Clear nav and layout bio
					layouts.hide();
					layoutMenu.removeClass('active');

					// show welcome message again
					popup.find('.welcome-message').show();
				});
				overlay.fadeOut();
				
			}

			/*=============================================
			=            Function - Open popup            =
			=============================================*/
			var openLayoutPopup = function(event){
				event.preventDefault();
				var target = $(event.target);
				var flexible_field = target.closest('.acf-field-flexible-content').attr('data-key');
				var popup = $('.'+flexible_field);
				var overlay = $('.bbh-popup-overlay');

				popup.addClass('open').fadeIn();
				overlay.fadeIn();

			}

			/*=============================================
			=            Function - Add Layout            =
			=============================================*/
			var addLayout = function(event){
				event.preventDefault();
				var target = $(event.target);
				var popup = target.closest('.bbh-popup');
				var dataLayout = target.attr('data-layout');				
				


				acf.fields.flexible_content.add(dataLayout);
				closeLayoutPopup(event);
				
			}

			/*==================================================
			=            Function - View layout bio            =
			==================================================*/
			
			var viewLayoutBio = function(event){
				event.preventDefault();
				var target = $(event.target);
				if(target.hasClass('active')){
					return;
				}
				var popup = target.closest('.bbh-popup');
				var dataLayout = target.attr('data-layout');
				var singleLayout = popup.find('.single-layout[data-layout="'+dataLayout+'"]');

				popup.find('.bbh-layout-menu a, .window-controls span.dashicons-info').removeClass('active');
				target.addClass('active');
				popup.find('.single-layout').hide();
				singleLayout.show();
				


			}
			
			

			/*----------  add section click - open popup  ----------*/
			$('.acf-field-flexible-content .acf-flexible-content > .acf-actions a.acf-button').on('click',openLayoutPopup);			
			/*----------  Close button click  ----------*/
			$('.layout-picker-close').on('click', closeLayoutPopup);

			/*----------  Add layout  ----------*/
			$('.bbh-popup a.add-layout-button').on('click', addLayout);
			
			$('.bbh-popup .bbh-layout-menu a, .window-controls .layout-picker-info').on('click', viewLayoutBio);
			
			
		})
	})  
	/*
	*  acf/setup_fields
	*
	*  This event is triggered when ACF adds any new elements to the DOM. 
	*
	*  @type	function
	*  @since	1.1.0
	*  @date	08/29/14
	*
	*  @param	event		e: an event object. This can be ignored
	*  @param	Element		postbox: An element which contains the new HTML
	*
	*  @return	N/A
	*/

	acf.add_action('show_field', function( $field, context ){
		$field.removeClass('conditional-hidden');		
	});
	
	acf.add_action('ready append', function( $el ){

	 	var acf_settings_cols = acf.model.extend({
	 		
	 		actions: {
	 			'open_field':			'render',
	 			'change_field_type':	'render'
	 		},
	 				
	 		render: function( $el ){
	 			
	 			// bail early if not correct field type
	 			if( $el.attr('data-type') != 'column' ) {
	 				
	 				return;
	 				
	 			}
	 		 			
	 			// clear name
	 			$el.find('.acf-field[data-name="name"] input').val('').trigger('change');
	 			
	 		}		
	 		
	 	});
		
		var count = 'first';
		
		// search $el for fields of type 'column'
		acf.get_fields({ type : 'column'}, $el).each(function(e, postbox) {

			var columns = $(postbox).find('.acf-column[class*="column-layout"]').data('column'),
				orig_key = $(postbox).find('.acf-column[class*="column-layout"]').data('id'),
				orig_class = $(postbox).attr('class');
				orig_class = orig_class.replace(/(\acf-field-\w+|\d+)|(acf-field)/gm, '');
				
				key = "acf-" + orig_key.replace("_", "-"),
				colClass = '',
				is_collapse_field = '';

			$(postbox).find('.acf-column').each(function() {
				var root = $(this).parents('.acf-field-column');
				if ( columns == '1' ) {
					$(postbox).replaceWith('<div class="acf-field acf-field-columngroup column-end-layout '+ orig_class +'"></div>');
					count = 'first';
				} else {
					var acf_fields = $(root).nextUntil('.acf-field-column');

					acf_fields.each(function() {
						if ( $(this).hasClass('-collapsed-target') ) {
							is_collapse_field = ' -collapsed-target';
							return is_collapse_field;
						}
					});

					if ( $(postbox).hasClass('hidden-by-tab') ) {
						colClass = 'acf-field acf-field-columngroup ' + key + ' column-layout-' + columns + ' ' + count + ' ' + orig_class + ' hidden-by-tab';
					} else {
						colClass = 'acf-field acf-field-columngroup ' + key + ' column-layout-' + columns + ' ' + count + ' ' + orig_class;
					}					

					if ( $(postbox).hasClass('hidden-by-conditional-logic') ) {
						colClass = colClass + ' conditional-hidden';
					}

					$(root)	.nextUntil('.acf-field-column')
							.removeClass('hidden-by-tab')
							.wrapAll('<div class="' + colClass + is_collapse_field + '" data-field="column" data-key="' + orig_key + '"></div>');
					$(postbox).remove();
					count = '';
				}
			});
		});
		
		// Fix for initiating TinyMCE when using in Flexible Content Field
		// Thanks to dsamson (https://github.com/dsamson/)

		if (typeof tinyMCE !== 'undefined') {
		  if ( tinyMCE ) {
				acf.get_fields({ type : 'wysiwyg'}, $el).each(function(e, postbox){
					$("textarea.wp-editor-area", postbox).each(function(){
						edit = tinyMCE.EditorManager.get(this.id);



						
						if ( edit !== null ) {
							settings = edit.settings;
							edit.remove();
						} else {
							settings = {};
						};						
						tinyMCE.EditorManager.init(settings);
					});
				});
			}
		}
	});

})(jQuery);


/*====================================================
=            Brand by hand - Page builder            =
====================================================*/

(function($){

	acf.add_action('ready append', function( $el ){
		
		// wrap wysiwyg fields in popup wrapper and add edit button
		
		acf.get_fields({ type : ''}, $el).each(function(e, postbox) {
			//var frameBox =$(postbox).find('iframe[id*="acf-editor"]').contents().find("[tokenid=" + token + "]").html();
			var frameBox = $(postbox).find('iframe');

			if($('body').hasClass('post-type-acf-field-group') == true){
				return true;
			}


			if($(postbox).hasClass('acf-field-message') == true){
				$(postbox).next().addClass('first-after-message');
			}

			var fieldType = $(postbox).data('type');

			var forbiddenArray = ['flexible_content', 'select', 'radio', 'checkbox', 'true_false', 'message', 'column'];

			//var forbiddenArray = ['text', 'textarea', 'image', 'wysiwyg']
			if(($.inArray(fieldType, forbiddenArray) !== -1)){
				return true;
			}
			if(fieldType == 'column'){
				return true;
			}


			if (!$(this).parent().hasClass('acf-field-columngroup')){
			    return true;
			} else if($(postbox).parent().hasClass('acf-row')){
				
			};


			// add custom builder-field class
			$(postbox).addClass('builder-field');
			// wrap in popup wrapper
			$(postbox).find('> .acf-input').wrap('<div class="popup-content-outer ' + fieldType + '"><div class="popup-content-inner"></div></div>');
			// add edit button
			$(postbox).append('<span class="edit-trigger"><a class="acf-icon -pencil dark" href="#" title="Edit"></a></span>');
			// append popup overlay
			$(postbox).append('<div class="popup-overlay"></div>');
			// clone field label for popup box
			var fieldLabel = $(postbox).find('.acf-label').clone().html();
			var closeBtnHtml = '<button class="close-btn button-link media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Luk mediepanel</span></span></button>';
			var fullscreenBtnHtml = '<button class="fullscreen-btn button-link media-modal-close"><span class="dashicons dashicons-editor-expand"><span class="screen-reader-text">Fuld skærm</span></span></button>';
			// generate popup box
			$(postbox).find('.popup-content-outer').prepend( fieldLabel + fullscreenBtnHtml + closeBtnHtml );
			
			

			function checkTinyMCE(){
				if(fieldType != 'wysiwyg'){
					return true;
				}
				var editorContent = tinyMCE.activeEditor.getContent();
				if(editorContent.length > 1){
					$(postbox).find('.edit-trigger').addClass('has-content');
				} else{
					$(postbox).find('.edit-trigger').removeClass('has-content');
				}

			}
			
			function dynamicEditBtn(){
				var contentArray = [];
				
				$(postbox).find('.popup-content-inner').find('input, textarea, .acf-repeater input, textarea.wp-editor-area').each(function(){

					
					var text = $(this).text();
					var value = $(this).val();


					
					if( value.length >= 1 || text.length >= 1){
						contentArray.push('true');
						
						//$(postbox).find('.edit-trigger').addClass('has-content');
					} else{
						contentArray.push('false');
						//$(postbox).find('.edit-trigger').removeClass('no-content')

					}
				})



				
				if($.inArray('true', contentArray) !== -1){
					$(postbox).find('.edit-trigger').addClass('has-content');
					var contentArray = [];
				} else{
					$(postbox).find('.edit-trigger').removeClass('has-content');
					var contentArray = [];
				}
				
			}
			
			function fullscreenBtnTrigger(e){
				// strip button of WordPress js
				e.preventDefault();
				// save btn element in var
				var btn = $(postbox).find('.fullscreen-btn');
				// toggle active class
				btn.toggleClass('fullscreen-active');
				// if fullscreen active / else
				if(btn.hasClass('fullscreen-active') == true){
					// remove draggable
					$(postbox).find('.popup-content-outer').addClass('fullscreen-mode').removeClass('ui-draggable');
				} else if(btn.hasClass('fullscreen-active') == false){
					// add draggable again after animation is complete
					$(postbox).find('.popup-content-outer').removeClass('fullscreen-mode').delay(1000).queue(function(next){
					    $(this).addClass('ui-draggable');
					    next();
					});

				}
			}


			window.scrollBarWidth = function() {
				document.body.style.overflow = 'hidden'; 
				var width = document.body.clientWidth;
				document.body.style.overflow = 'scroll'; 
				width -= document.body.clientWidth; 
				if(!width) width = document.body.offsetWidth - document.body.clientWidth;
				document.body.style.overflow = ''; 
				return width; 
			}
			// trigger edit button type
			dynamicEditBtn();

			// trigger fullscreen on button click
			$(postbox).find('.fullscreen-btn').on('click', fullscreenBtnTrigger);

			// trigger popup content show on edit btn click
			$(postbox).find('.edit-trigger').on('click', function(){

				// first fadeout previous popup if it's open
				$('.popup-content-outer').fadeOut();
				
				


				// fadein popup and overlay
				$(postbox).find('.popup-content-outer').fadeIn();
				
				$(postbox).find('.popup-overlay').fadeIn();
				// remove any previous edit button active and set new for triggered element
				$('.edit-trigger').removeClass('active');
				$(postbox).find('.edit-trigger').addClass('active');
				
				

				// lock body scroll
				$('body').addClass('noscroll');
				$('body').css('margin-right', window.scrollBarWidth());
				

				// TinyMCE fix for wysiwyg fields
				if(fieldType == 'wysiwyg'){

					$(postbox).find("textarea.wp-editor-area").each(function(){
						// get wp settings for TinyMCE
						edit = tinyMCE.EditorManager.get(this.id);

						tinyMCE.activeEditor.destroy(0);
						$('button#'+this.id+'-tmce').click();
						//tinyMCE.activeEditor.execCommand('mceToggleEditor',true,'content');
						


						// if settings exists
						if ( edit !== null ) {
							settings = edit.settings;
							edit.remove();
						} else {
							settings = {};
						};
						// reInit tinyMCE with settings
						tinyMCE.EditorManager.init(settings);

					});				
				}


				// trigger edit button function - detect if has content
				dynamicEditBtn();
				// trigger checkTinyMCE 
				checkTinyMCE();
				
				// sæt max height til popup-content-inner, så repeaters ikke flyder ud over popup
				$(postbox).find('.popup-content-inner').css({
					'max-height' : $(postbox).find('.popup-content-inner').parent().height() - $(postbox).find('.popup-content-inner').siblings('.description').outerHeight( true ),
				})
				
			})
			// lock in position and remove transform translate on first open. Position is saved upon drag end, for future opening
			$(postbox).find('.edit-trigger').one('click', function(){
				var contentOuter = $(postbox).find('.popup-content-outer');
				var contentOffset = contentOuter.offset();
				var scrollTop = $(window).scrollTop();

				contentOuter.css({
					'left'		: 	contentOffset.left,
					'top'		: 	contentOffset.top - scrollTop,
					'transform'	: 	'none',
				})
			})
			// set wysiwyg fields trigger as no content on new row layout
			$(postbox).find('textarea').each(function(){

				if($(this).length <= 1){
					$(postbox).find('.edit-trigger').removeClass('has-content');
				}
			})
			// trigger popup content hide on overlay or close btn click
			$(postbox).find('.popup-overlay, .close-btn').on('click', function(e){

				// remove active class from edit trigger
				$('.edit-trigger').removeClass('active');
				// prevent link follow, or other event handlers
				e.preventDefault();
				// fadeout overlay
				//$(postbox).find('.popup-overlay').fadeOut();
				// fadeout popupwindow and remove fullscren
				$(postbox).find('.popup-content-outer').fadeOut().delay(400).queue(function(next){
					$(this).removeClass('fullscreen-mode');
					$(postbox).find('.popup-content-outer').removeClass('fullscreen-mode').addClass('ui-draggable');
					$(postbox).find('.fullscreen-btn').removeClass('fullscreen-active');
					next();
				});

				// remove noscroll class form body
				$('body').removeClass('noscroll');
				$('body').css('margin-right', '0px');



				// trigger edit button function - detect if has content
				dynamicEditBtn();
				checkTinyMCE();

			})
			
			
			// initiate draggable on popup-content-outer
			$(postbox).find('.popup-content-outer').draggable({
					cancel: '.fullscreen-mode',
					// disabled dragability on iframe
					iframeFix: true,
					// move symbol on cursor while dragging
					cursor: "move",
					// only drag on label
					handle: 'label',
					opacity: 0.7,
					// stay within edit page container (won't move over sidebar)
					drag: function(e,ui){
						var windowWidth = $(window).width();
						var windowHeight = $(window).height();
						var popupOuterWidth = $(postbox).find('.popup-content-outer').outerWidth();
						var popupOuterHeight = $(postbox).find('.popup-content-outer').outerHeight();            
				        //Do not permit to be more close than 10 px of window minus sidebar 
				        if(ui.position.left < 170){    
				            ui.position.left = 170;
				        }
				        if(ui.position.top < 40){
				        	ui.position.top = 40;
				        }
				        if(ui.position.top > windowHeight - popupOuterHeight){
				        	ui.position.top = windowHeight - popupOuterHeight - 10;
				        }
				        if(ui.position.left > windowWidth - popupOuterWidth){
				        	ui.position.left = windowWidth - popupOuterWidth - 10;
				        }
				    },
					scroll: false,
					// on start, remove transform css, since this will cause placement to be wrong
					start: function() {
			        	$(this).css({
					          transform: 'none',
					    });
					},
					// on stop drag, save current screen position relative to window
					stop: function() {
						var endOffset = $(this).offset();
						$(this).css({
							'left' 	: endOffset.left,
							'top'	: endOffset.top - $(window).scrollTop(),
						})
					},
		       	  	
			      
				})

		})
		// show fields when function is done and markup is inserted.
		$('.acf-field-columngroup .acf-input').css('visibility', 'visible');
	})


})(jQuery);



