@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

        @include('common.dashboardsidebar', ['page' => 'newlisting'])


        <!-- MAIN PAGE -->
            <div class="col-lg-6 mainpage no-float no-select">
                <h1>Edit Listing</h1>
                <form method="POST" action="{{ route('listings.update', $listing->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    @if (count($errors) > 0)
                        <div class="error">
                            @if (count($errors) == 1)
                                <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Error:</b> {{ $errors->first() }}
                            @else
                                <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Errors:</b>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <label for="title">Title:</label>
                    <input type="text" name="title" value="{{ $listing->title }}" required/>

                    <label for="rent_value" class="space-top">Rent:</label>
                    £<input type="number" id="rentvalue" name="rent_value" step="0.01" min="0" value="{{ $listing->rent_period == 'week' ? round($listing->rent_value) : round($listing->rent_value * 52 / 12) }}" required/>
                    per
                    <select name="rent_period" required>
                        <option{{ $listing->rent_period == 'week' ? ' selected' : '' }}>week</option>
                        <option{{ $listing->rent_period == 'month' ? ' selected' : '' }}>month</option>
                    </select>

                    <label for="address1" class="space-top">Address 1:</label>
                    <input type="text" name="address1" value="{{ $listing->address1 }}" required/>

                    <label for="address2">Address 2:</label>
                    <input type="text" name="address2" value="{{ $listing->address2 }}"/>

                    <label for="town">Town/City:</label>
                    <input type="text" name="town" value="{{ $listing->town }}" required/>

                    <label for="postcode">Postcode:</label>
                    <input type="text" name="postcode" value="{{ $listing->postcode }}" required/>

                    <div class="properties-table space-top">
                        <div>
                            <label for="bedrooms"><i class="fa fa-bed fa-pad-5 fa-pad-5l sr-icons"></i>Bedrooms:</label>
                            <select name="bedrooms" required>
                                <option value="0"{{ $listing->bedrooms == 0 ? ' selected' : '' }}>Studio</option>
                                <option value="1"{{ $listing->bedrooms == 1 ? ' selected' : '' }}>1 bedroom</option>
                                <option value="2"{{ $listing->bedrooms == 2 ? ' selected' : '' }}>2 bedrooms</option>
                                <option value="3"{{ $listing->bedrooms == 3 ? ' selected' : '' }}>3 bedrooms</option>
                                <option value="4"{{ $listing->bedrooms == 4 ? ' selected' : '' }}>4 bedrooms</option>
                                <option value="5"{{ $listing->bedrooms == 5 ? ' selected' : '' }}>5 bedrooms</option>
                                <option value="6"{{ $listing->bedrooms == 6 ? ' selected' : '' }}>6 bedrooms</option>
                                <option value="7"{{ $listing->bedrooms == 7 ? ' selected' : '' }}>7 bedrooms</option>
                                <option value="8"{{ $listing->bedrooms == 8 ? ' selected' : '' }}>8+ bedrooms</option>
                            </select>
                        </div>
                        <div>
                            <label for="bathrooms"><i class="fa fa-bath fa-pad-5 fa-pad-5l sr-icons"></i>Bathrooms:</label>
                            <select name="bathrooms" required>
                                <option value="1"{{ $listing->bathrooms == 1 ? ' selected' : '' }}>1 bathroom</option>
                                <option value="2"{{ $listing->bathrooms == 2 ? ' selected' : '' }}>2 bathrooms</option>
                                <option value="3"{{ $listing->bathrooms == 3 ? ' selected' : '' }}>3 bathrooms</option>
                                <option value="4"{{ $listing->bathrooms == 4 ? ' selected' : '' }}>4 bathrooms</option>
                                <option value="5"{{ $listing->bathrooms == 5 ? ' selected' : '' }}>5 bathrooms</option>
                                <option value="6"{{ $listing->bathrooms == 6 ? ' selected' : '' }}>6+ bathrooms</option>
                            </select>
                        </div>
                    </div>

                    <div class="properties-table space-top">
                        <div>
                            <label for="furnished"><i class="fa fa-check  fa-pad-5 fa-pad-5l sr-icons"></i>Furnished:</label>
                            <select name="furnished" required>
                                <option value="1"{{ $listing->furnished == 1 ? ' selected' : '' }}>Yes</option>
                                <option value="0"{{ $listing->furnished == 0 ? ' selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="bills"><i class="fa fa-envelope-open fa-pad-5 fa-pad-5l sr-icons"></i>Bills Included:</label>
                            <select name="bills" required>
                                <option value="1"{{ $listing->bills_included == 1 ? ' selected' : '' }}>Yes</option>
                                <option value="0"{{ $listing->bills_included == 0 ? ' selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="pets"><i class="fa fa-paw fa-pad-5 fa-pad-5l sr-icons"></i>Pets Allowed:</label>
                            <select name="pets" required>
                                <option value="1"{{ $listing->pets_allowed == 1 ? ' selected' : '' }}>Yes</option>
                                <option value="0"{{ $listing->pets_allowed == 0 ? ' selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <label for="description" class="space-top">Description:</label>
                    <textarea rows="6" cols="20" name="description" required>{{ str_replace('\n', "\n\n", $listing->description) }}</textarea>

                    <label for="images[]" class="space-top">Existing Gallery Images:</label>
                    <div class="existing-images">
                        @foreach ($listing->images as $image)
                            <div class="image" style="background-image: url('{{ $image->file() }}');">
                                <div class="buttons">
                                    <a href="#" class="button-toggle-delete" title="Delete"><i class="fa fa-trash sr-icons"></i></a>
                                </div>
                                <input type="checkbox" class="checkbox-deleted" name="existingimages.{{ $image->image_number }}.deleted"/>
                                <input type="checkbox" class="checkbox-header" name="existingimages.{{ $image->image_number }}.header"{{ $image == $listing->header ? ' checked' : '' }}/>
                            </div>
                        @endforeach
                    </div>
                    <a href="#" id="button-delete-all"><i class="fa fa-trash fa-pad-5 sr-icons"></i>Delete all existing images.</a>

                    <label for="images[]" class="space-top">New Gallery Images:</label>
                    <div class="input image-upload">
                        <input type="file" name="images[]" id="images" accept="image/jpeg, image/png" multiple title="test"/>
                        <div id="preview">

                        </div>
                    </div>
                    <p style="font-size: 12px;">Use Shift and Control keys to select multiple images.</p>

                    <button class="btn btn-primary btn-xl space-top">Create</button>
                </form>
            </div>

            <div class="col-lg-3 no-float"></div>

            <script>
                $(document).ready(function() {
                    if(window.File && window.FileList && window.FileReader) {       // Check File API support
                        $('#images').change(function (event) {
                            var files = event.target.files;
                            $('#preview').html('');
                            for(var i = 0; i < files.length; i++) {
                                var file = files[i];

                                if(!file.type.match('image')) continue;

                                var picReader = new FileReader();

                                picReader.onload = function (event) {
                                    $('#preview').append('<img class="image-preview" src="' + event.target.result + '"/>');
                                };

                                picReader.readAsDataURL(file);
                            }
                        });

                        var files = $('#images').target.files;
                        $('#preview').html('');
                        for(var i = 0; i < files.length; i++) {
                            var file = files[i];

                            if(!file.type.match('image')) continue;

                            var picReader = new FileReader();

                            picReader.onload = function (event) {
                                $('#preview').append('<img class="image-preview" src="' + event.target.result + '"/>');
                            };

                            picReader.readAsDataURL(file);
                        }
                    } else {
                        console.log("Your browser does not support File API!");
                    }
                });
            </script>

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
                        btn.parent().parent().css('-webkit-filter', 'grayscale(0%)');
                        btn.parent().parent().css('filter', 'grayscale(0%)');
                        btn.parent().parent().css('height', '200px');
                        $('#button-delete-all').css('visibility', 'visible');
                    } else {
                        btn.html('<i class="fa fa-undo sr-icons"></i>');
                        btn.attr('title', 'Undo delete');
                        btn.parent().parent().css('-webkit-filter', 'grayscale(100%)');
                        btn.parent().parent().css('filter', 'grayscale(100%)');
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

        </div>
    </div>

@endsection