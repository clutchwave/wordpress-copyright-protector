jQuery(function($){
	

	$("body").bind('copy', function (e) {


	
	            if (typeof window.getSelection == "undefined") return; //IE8 or earlier...
	            
	            var body_element = document.getElementsByTagName('body')[0];
	            var selection = window.getSelection();
	            
	            //if the selection is short let's not annoy our users
	            if (("" + selection).length < 30) return;
	    
	            //create a div outside of the visible area
	            //and fill it with the selected text
	            var newdiv = document.createElement('div');
	            newdiv.style.position = 'absolute';
	            newdiv.style.left = '-99999px';
	            body_element.appendChild(newdiv);
	            newdiv.appendChild(selection.getRangeAt(0).cloneContents());
	            
	            //we need a <pre> tag workaround
	            //otherwise the text inside "pre" loses all the line breaks!
	            if (selection.getRangeAt(0).commonAncestorContainer.nodeName == "PRE") {
	                    newdiv.innerHTML = "<pre>" + newdiv.innerHTML
	                    + "</pre><br />"+wpcp_config.wpcp_text_before_url+" <a href='" + document.location.href + "'>"
	                    + remove_trailing_slash(document.location.href) + "</a>";
	            }
	            else
	                    newdiv.innerHTML += "<br /><br />"+wpcp_config.wpcp_text_before_url+" <a href='"
	                    + document.location.href + "'>"
	                    + remove_trailing_slash(document.location.href) + "</a>";
	                            
	            selection.selectAllChildren(newdiv);
	            window.setTimeout(function () { body_element.removeChild(newdiv); }, 200);

	  });
	
});

// from http://stackoverflow.com/a/6680877/1636799
function remove_trailing_slash(incoming_string) {
  return incoming_string.replace(/\/+$/, "");
}
