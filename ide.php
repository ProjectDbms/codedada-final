<!DOCTYPE html>
<html lang="en">
<head>
<title>ACE in Action</title>
<?php include("includes/header.php"); ?>
<style type="text/css" media="screen">
    #editor { 
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
</head>
<body>

<div id="editor"></div>
    
<script src="assets/js/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setOptions({
		  // fontFamily: "tahoma",
		  fontSize: "10pt"
	});
	t = '#include <stdio.h>\n\
int main() {\n\
	\n\
	return 0;\n\
}';
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/c_cpp");
    editor.session.setValue(t)
    // console.log($("#editor").getValue());
    // editor.session.setValue()
    // console.log(editor.session.getValue());
</script>
</body>
</html>