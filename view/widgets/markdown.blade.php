<link rel="stylesheet" type="text/css" href="{{ asset('zhiyicx/plus-component-pc/markdown/css/editormd.css') }}">
<div id="layout" class="div">
    <div class="editormd" id="TS_edit">
        <textarea></textarea>
    </div>
</div>
<script type="text/javascript" charset="utf-8" src="{{ asset('zhiyicx/plus-component-pc/markdown/js/editormd.js') }}"></script>

<script type="text/javascript">
    var editor = editormd("TS_edit",{
        id   : "editormd",
        width: "{{ $width }}",
        height: "{{ $height }}",
        watch : false,
        path : "{{ asset('zhiyicx/plus-component-pc/markdown/lib') }}/",
        saveHTMLToTextarea : true,
        imageUpload : true,
        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
        imageUploadURL : "/api/v2/files",
        uploadSuccess: function (image, f, storage_id) {}
    });
    editor.setToolbarAutoFixed(false);
</script>