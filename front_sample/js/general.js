var previewNode = document.querySelector('#template');
previewNode.id = '';
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
 
var myDropzone = new Dropzone(document.body, {
    url: "/mock.php?action=upload",
    uploadMultiple: false,
    thumbnailWidth: 140,
    thumbnailHeight: 140,
    previewTemplate: previewTemplate,
    init: function(){
        this.on('thumbnail', function(_, url){
            $('#upload-box').remove().appendTo('#preview');
            $('#preview-big').attr('src', url);
        });

        this.on('sending', function(f){
            if (f.type.match(/image\/*/))
            {
                this.removeAllFiles();
            }
        });
    },
    previewsContainer: '#preview',
    clickable: '#preview',
    fallback: function(){
        $('#preview').html('<input type="file" name="file" />');
    }
});

var $dyn_inputs = $('#title,#description');

$dyn_inputs.on('keyup keydown',function(){
    var $this = $(this);
    var id = $this.attr('id');
    var val = $this.val();

    $('#preview-'+id).html(val ? val : '&nbsp;');

    $('#counter-'+id).html(val.length+'/'+$this.attr('maxlength'));
}).trigger('keyup');

$(':reset').on('click', function(){
    $dyn_inputs.each(function(){
        var $this = $(this);
        var id = $this.attr('id');

        $('#preview-'+id).html('&nbsp;');

        $('#counter-'+id).html('0/'+$this.attr('maxlength'));
    });

    // $('#preview-title,#preview-description').html('&nbsp;');

    // $('.dz-image-preview').remove();
    myDropzone.removeAllFiles();

    $('#preview-big').attr('src','http://placehold.it/180x180');
});

$('form').submit(function(e){
    e.preventDefault();

    var $form = $(this);

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: $form.serialize(),
        success: function(r){
            $form.parents('.row').empty().append('<div class="jumbotron"><h1>Данные сохранены.</h1></div>');
        }
    });
});


