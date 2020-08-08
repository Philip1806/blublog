@if (blublog_setting("post_editor"))
<script src="https://cdn.ckeditor.com/4.14.1/full/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'editor', {

} );
CKEDITOR.on('instanceReady', function (ev) {
    ev.editor.dataProcessor.htmlFilter.addRules( {
        elements : {
            img: function( el ) {
                el.addClass('img-in-post');

                if (!el.attributes.alt)
                    delete el.attributes.alt;
            }
        }
    });
});
</script>
@endif
