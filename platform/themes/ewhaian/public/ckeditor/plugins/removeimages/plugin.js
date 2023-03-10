CKEDITOR.plugins.add( 'removeimages', {
    icons: 'removeImages',
    init: function( editor ) {
        editor.addCommand( 'removeImages', {
            exec: function( editor ) {
                let old_content =  editor.getData();
                let new_editor = '';
                let pattern = /<img.*?src="(.*?)"[^\>]+>/gm;
                new_editor = old_content.replace(pattern,'')
                editor.setData(new_editor);
            }
        });
        editor.ui.addButton( 'RemoveImages', {
            label: 'Remove Images',
            command: 'removeImages',
            toolbar: 'insert'
        });
    }
});
