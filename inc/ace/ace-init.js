 window.onload = function() {
	//var setup
	var textarea, editor, form, session, editDiv;
	textarea = document.getElementById('_wpce_custom_css');
	
	//create editor element
	editDiv = document.createElement('div');
	editDiv.id = 'wpce-ace-editor';
	editDiv.style.position = 'relative';
	editDiv.style.width = '500px';
	editDiv.style.height = '300px';
	textarea.parentNode.insertBefore(editDiv, textarea);
	
	// hide textarea
	textarea.style.display = 'none';
	
	// ace editor init
	editor = ace.edit('wpce-ace-editor');
	form = textarea.closest('form');
	session = editor.getSession();
	session.setValue(textarea.value);
	editor.setTheme("ace/theme/vibrant_ink");
	var cssMode = ace.require("ace/mode/css").Mode;
    editor.getSession().setMode(new cssMode());
	
	// copy back to textarea on form submit
	form.onsubmit = function () {
		textarea.value = session.getValue();
	};
}