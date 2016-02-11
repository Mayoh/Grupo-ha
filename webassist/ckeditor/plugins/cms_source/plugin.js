CKEDITOR.plugins.add('cms_source',
{
    init: function(editor)
    {
        var pluginName = 'cms_source';
		editor.addCommand( pluginName, {
        exec: function( editor ) {
			editor.resetDirty();
			var cms_class = "cms-content";
			var nodeList = document.querySelectorAll(".cke");
			for (var i = 0, length = nodeList.length; i < length; i++) {
				nodeList[i].style.display = "none";
			}
			document.getElementById('overlay-bg').style.display = "block";
  			document.getElementById("cke_cms-editor").style.display = "block";
			document.getElementById('overlay-cms-content').style.display = "block";
			
			//adjust popup
			var overlay_size = document.getElementById('overlay-cms-content').offsetHeight;
			var window_size = window.innerHeight;
			
			var window_top = ((window_size -overlay_size)/2);
			if (window_top < 0) window_top = 0;
			document.getElementById('overlay-cms-content').style.top = window_top + 'px';
			//populate
			var data = (editor.getData());
			CKEDITOR.instances['cms-editor'].focus();
			CKEDITOR.instances['cms-editor'].setData(data);
			var element = editor.element;
			var contentID = (element.getParent());
			if (contentID) contentID = contentID.getAttribute("id");
			contentID = contentID.substring(contentID.lastIndexOf("-")+1);
			document.getElementById('cms-content-id').setAttribute('value', contentID);
        }
    } );
        editor.ui.addButton('Cms_source',
            {
                label: 'Edit Source',
                command: pluginName
            });
    }
});