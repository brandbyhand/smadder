(function () {
    tinymce.PluginManager.add('bbh_custom_mce_button', function(editor, url) {
        // example of a dashicons icon labelled button that when pressed results
        // in a popup window appearing
        editor.addButton( 'bbh_custom_mce_button', {
          	title: 'Insert button',
          	image: url + '/button-sharpen.png',
          	onclick: function() {
            	editor.windowManager.open( {
	              	title: 'Insert button',
	              	body: [
		              	{
		                	type: 'textbox',
		                	name: 'text',
		                	label: 'Button text'
		              	},
		              	{
		                	type: 'textbox',
		                	name: 'link',
		                	label: 'Button link',
		                	class: 'custom-link',

		                	onkeydown: function( event ){
		                    	var link = jQuery(event.target).val();
		                    	var windowID = event.currentTarget.id;
		                    	jQuery(event.target).addClass('custom-link-' + windowID);



		                    	/*if(link.indexOf('mailto:') === -1 && link.indexOf('tel:') === -1){
		                        	link = (link.indexOf('://') === -1) ? 'http://' + link : link;    
		                    	}

		                    	jQuery(event.target).val(link);*/
		                	},
		                	onfocusout: function( event ){
		                    	var link = jQuery(event.target).val();

		                    	var windowID = event.currentTarget.id;
		                    	jQuery(event.target).addClass('custom-link-' + windowID);

		                    	if(link.indexOf('mailto:') === -1 && link.indexOf('tel:') === -1){
		                    	    link = (link.indexOf('://') === -1) ? 'http://' + link : link;    
		                    	}

		                    	jQuery(event.target).val(link);
		                	}
		              	},
		              	{
		                    type   : 'listbox',
		                    name   : 'style',
		                    label  : 'Button style',
		                    values : [
		                        { text: 'Empty', value: 'ghost' },
		                        { text: 'Filled', value: 'filled' },
		                    ],
		                    value : 'style1' // Sets the default
		                },
		              	{
		              		type   : 'checkbox',
		                    name   : 'target',
		                    label  : 'Open link in a new tab',
		                    checked : false

		              	}
	              	],
	              	onsubmit: function( e ) {
	                	e.stopPropagation();

	                	var windowID = e.target._eventsRoot._id;


	                	var link = jQuery('.custom-link-' + windowID).val();

	                	if(link.indexOf('mailto:') === -1 && link.indexOf('tel:') === -1){
	                	    link = (link.indexOf('://') === -1) ? 'http://' + link : link;
	                	}

	                	jQuery('.custom-link-' + windowID).val(link);
	                	

	                	var target = '_self';
	                	if(e.data.target === true){
	                		target = '_blank';
	                	} else if(e.data.target === false){
	                		target = '_self'
	                	}
	                	
	                	editor.insertContent( '<span class="clearfix"><a class="btn btn-custom ' + e.data.style + '" target="' + target + '" href="' + link + '">' + e.data.text + '</a></span>' );
	                	editor.windowManager.close();
	              	}
            	});
          	}
        });
    });
})();

