            <script>
                function toggleChecked(btn) {
                    const checkbox = btn.parents('.image').children('.checkbox-deleted');

                    let checked = !!checkbox.attr('checked');

                    checkbox.attr('checked', !checked);


                    if (checked) {
                        btn.children('i').removeClass('fa-undo').addClass('fa-trash');
                        btn.attr('title', 'Delete image');

                        btn.parents('.image').css('-webkit-filter', 'grayscale(0%) brightness(100%)');
                        btn.parents('.image').css('filter', 'grayscale(0%) brightness(100%)');
                        btn.parents('.image').css('height', '200px');

                        $('#button-delete-all').css('visibility', 'visible');

                        btn.siblings('.button-set-header').css('display', 'inline-block');

                    } else {
                        btn.children('i').removeClass('fa-trash').addClass('fa-undo');
                        btn.attr('title', 'Undo delete');

                        btn.parents('.image').css('-webkit-filter', 'grayscale(100%) brightness(150%)');
                        btn.parents('.image').css('filter', 'grayscale(100%) brightness(150%)');
                        btn.parents('.image').css('height', '75px');

                        let alldeleted = true;
                        btn.parents('.existing-images').children('.image').each(function() {
                            if (!$(this).children('.checkbox-deleted').attr('checked'))
                                alldeleted = false;
                        });
                        if (alldeleted)
                            $('#button-delete-all').css('visibility', 'hidden');

                        if ($('#input-header-image').val() == btn.parents('.image').attr('class').substr(19)) {
                            setHeaderImage(0);
                        }

                        btn.siblings('.button-set-header').css('display', 'none');
                    }
                }

                function setHeaderImage(imagenum) {
                    $('#input-header-image').val(imagenum);

                    $('.button-set-header').each(function() {
                        $(this).parents('.image').css('outline', 'none');
                        $(this).parents('.image').css('box-shadow', 'none');
                        if (!$(this).parents('.image').children('.checkbox-deleted').attr('checked'))
                            $(this).css('display', 'inline-block');
                        $(this).parents('.image').attr('title', '');
                    });

                    const image = $('.image-number-' + imagenum);
                    image.css('outline', '3px solid #D64439');
                    image.css('box-shadow', 'inset 0px 0px 40px rgba(0, 0, 0, 0.9)');
                    image.attr('title', 'Header image');
                    image.children('.buttons').children('.button-set-header').css('display', 'none');
                }


                $(window).on('load', function () {
                    setHeaderImage({{ $listing->header->image_number }});
                });

                if ($(window).width() >= 768) {
                    $(window).on('load', function () {
                        $('.buttons').css('display', 'none');
                    });

                    $('.image').hover(function () {
                            $(this).children('.buttons').css('display', 'inline');
                        },
                        function () {
                            $(this).children('.buttons').css('display', 'none');
                        });
                } else {
                    $(window).on('load', function () {
                        $('.buttons').css('display', 'inline');
                    });
                }

                $('a.button-toggle-delete').click(function() {
                    toggleChecked($(this));
                    return false;
                });

                $('a.button-set-header').click(function() {
                    const classname = $(this).parents('.image').attr('class');
                    const imagenum = parseInt(classname.substr(19));

                    if (isNaN(imagenum)){
                        console.error('Failed to parse int from class on .image: ' + classname);
                        return false;
                    }

                    setHeaderImage(imagenum);
                    return false;
                });

                $('#button-delete-all').click(function() {
                    $('.existing-images').children('.image').children('.buttons').children('.button-toggle-delete').each(function() {
                        toggleChecked($(this));
                        if (!$(this).parents('.image').children('.checkbox-deleted').attr('checked'))
                            toggleChecked($(this));
                    });

                    $('#button-delete-all').css('visibility', 'hidden');

                    return false;
                })

                /*$('#save-button').click(function() {
                    if ($('#input-header-image').val() == 0) {
                        const error = $(
                            '<div class="error">' +
                            '   <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Error:</b> Listings must have a header image. Please select an image that has not been deleted or upload a new one.' +
                            '</div>'
                        );
                        $('#error-container').html(error);
                        $("html, body").animate({ scrollTop: $('#error-container').offset().top - 100 }, 500);
                        return false;
                    }
                });*/
            </script>