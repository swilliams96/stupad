            <script>
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

                function toggleChecked(btn, setchecked = null) {
                    var checkbox = btn.parent().parent().children('.checkbox-deleted');

                    var checked = !!checkbox.attr('checked');

                    checkbox.attr('checked', !checked);


                    if (checked) {
                        btn.html('<i class="fa fa-trash sr-icons"></i>');
                        btn.attr('title', 'Delete');
                        btn.parent().parent().css('-webkit-filter', 'grayscale(0%) brightness(100%)');
                        btn.parent().parent().css('filter', 'grayscale(0%) brightness(100%)');
                        btn.parent().parent().css('height', '200px');
                        $('#button-delete-all').css('visibility', 'visible');
                    } else {
                        btn.html('<i class="fa fa-undo sr-icons"></i>');
                        btn.attr('title', 'Undo delete');
                        btn.parent().parent().css('-webkit-filter', 'grayscale(100%) brightness(150%)');
                        btn.parent().parent().css('filter', 'grayscale(100%) brightness(150%)');
                        btn.parent().parent().css('height', '75px');

                        var alldeleted = true;
                        btn.parent().parent().parent().children('.image').each(function() {
                            if (!$(this).children('.checkbox-deleted').attr('checked'))
                                alldeleted = false;
                        });
                        if (alldeleted)
                            $('#button-delete-all').css('visibility', 'hidden');
                    }
                }

                $('a.button-toggle-delete').click(function() {
                    toggleChecked($(this));
                    return false;
                });

                $('#button-delete-all').click(function() {
                    $('.existing-images').children('.image').children('.buttons').children('.button-toggle-delete').each(function() {
                        toggleChecked($(this), false);
                        if (!$(this).parent().parent().children('.checkbox-deleted').attr('checked'))
                            toggleChecked($(this), false);
                    });

                    $('#button-delete-all').css('visibility', 'hidden');

                    return false;
                })
            </script>