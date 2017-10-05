            <script>
                $(document).ready(function() {
                    if(window.File && window.FileList && window.FileReader) {       // Check File API support
                        $('#images').change(function (event) {
                            var files = event.target.files;
                            $('#preview').html('');
                            for(var i = 0; i < files.length; i++) {
                                var file = files[i];

                                if(!file.type.match('image')) continue;

                                var reader = new FileReader();

                                reader.onload = function (event) {
                                    $('#preview').append('<img class="image-preview" src="' + event.target.result + '" draggable="false"/>');
                                };

                                $('#preview').css('width', (i+1)*275);

                                reader.readAsDataURL(file);
                            }
                        });

                        var files = $('#images').target.files;
                        $('#preview').html('');
                        for(var i = 0; i < files.length; i++) {
                            var file = files[i];

                            if(!file.type.match('image')) continue;

                            var reader = new FileReader();

                            reader.onload = function (event) {
                                $('#preview').append('<img class="image-preview" src="' + event.target.result + '" draggable="false"/>');
                            };

                            $('#preview').css('width', (i+1)*275);

                            reader.readAsDataURL(file);
                        }
                    } else {
                        console.log("Your browser does not support File API!");
                    }
                });
            </script>