$(function(){
    var $container = $('#observation_images');
    var index = $container.find('> div').length;

    //si des blocks images sont déjà présent
    if(index > 0){
        redefineAll();
    }

    $('#add-another-image').click(function(e){
        addImage($container);

        e.preventDefault();

        return false;
    });

    /**
     * Ajout un block image
     */
    function addImage($container)
    {
        var prototype =  $container.attr('data-prototype').replace(/__name__/g, index);
        $prototype = $(prototype);
        $container.append($prototype);

        // Incrémentation du compteur de photo
        index++;

        redefineCounter($prototype);
        addDeleteBtn($prototype);
        displayOrNotAddImage();
    }

    $(document).on('click', '.delete-image', function(e){
        var $delBtn = $(this);

        bootbox.dialog({
            message: messageImageConfirmationDelete,
            title: messageImageConfirmationTitle,
            buttons:{
            cancel:{
                label: cancelBtnLabel
            },
            delete:{
                label: delBtnLabel,
                    className: 'btn btn-danger btn-supprimer',
                    callback: function(){
                    $delBtn.parents('div.form-group').remove();
                    displayOrNotAddImage();
                    redefineAll();
                }
            }
        }
    });

        e.preventDefault();
        return false;
    });

    /**
     * Limite le nombre d'ajout de photos
     */
    function displayOrNotAddImage(){
        var nbElements = $container.find('> div').length;

        $('#add-another-image').css('display', 'inline');

        if(nbElements > 3){
            $('#add-another-image').css('display', 'none');
        }
    }

    /**
     * Redéfinie le numéro du block image
     */
    function redefineCounter($imageBlock){
        var counter = ($imageBlock.index() + 1);
        var counterHmtl = '<span class="counter">'+ counter +'</span>';
        var $label = $imageBlock.find('> label');
        //Vérifie si le block image vient du prototype ou s'il est déjà affiché dans la page
        if( /__num__/.test($label.html())){
            var textLabel = $label.html();
            textLabel = textLabel.replace('__num__', counterHmtl);
            $label.html(textLabel);
        }
        else{
            $label.find('.counter').text(counter);
        }
    }

    /**
     * Ajoute un bouton supprimer au block image
     * @param $imageBlock
     */
    function addDeleteBtn($imageBlock){
        //verification si le bouton est déjà présent ou pas
        var test = $imageBlock.find('.delete-image').html();
        if(typeof(test) === 'undefined'){
            //S'il n'est pas présent on l'ajoute
            var deleteBtn = '<p class="text-right"><a href="#" class="delete-image btn btn-danger"><i class="glyphicon glyphicon-trash"></i> '+ delBtnLabel +'</a></p>';
            $imageBlock.prepend(deleteBtn);
        }
    }

    function redefineAll(){
        $container.find('> div').each(function(){
            redefineCounter($(this));
            addDeleteBtn($(this));
        });
    }
});