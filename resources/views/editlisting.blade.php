@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

        @include('common.dashboardsidebar', ['page' => 'editlisting'])


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

                    <label for="address1" class="space-top">Address 1:<i class="fa fa-pad-5 fa-pad-5l fa-pad-5t fa-lock float-right sr-icons" data-toggle="tooltip" data-placement="left" title="To change this field please create a new listing or contact support."></i></label>
                    <input type="text" name="address1" value="{{ $listing->address1 }}" disabled/>

                    <label for="address2">Address 2:<i class="fa fa-pad-5 fa-pad-5l fa-pad-5t fa-lock float-right sr-icons" data-toggle="tooltip" data-placement="left" title="To change this field please create a new listing or contact support."></i></label>
                    <input type="text" name="address2" value="{{ $listing->address2 }}" disabled/>

                    <label for="town">Town/City:<i class="fa fa-pad-5 fa-pad-5l fa-pad-5t fa-lock float-right sr-icons" data-toggle="tooltip" data-placement="left" title="To change this field please create a new listing or contact support."></i></label>
                    <input type="text" name="town" value="{{ $listing->town }}" disabled/>

                    <label for="postcode">Postcode:<i class="fa fa-pad-5 fa-pad-5l fa-pad-5t fa-lock float-right sr-icons" data-toggle="tooltip" data-placement="left" title="To change this field please create a new listing or contact support."></i></label>
                    <input type="text" name="postcode" value="{{ $listing->postcode }}" disabled/>

                    <div class="properties-table split-content-2 space-top">
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

                    <label for="contact_phone" class="space-top">Contact Phone Number:<i class="fa fa-pad-5 fa-pad-5l fa-pad-5t fa-lock float-right sr-icons" data-toggle="tooltip" data-placement="left" title="This information will not be shown to anyone until you share it with them."></i></label>
                    <input type="tel" name="contact_phone" value="{{ $listing->contact_phone }}"/>

                    <label for="contact_email">Contact Email Address:<i class="fa fa-pad-5 fa-pad-5l fa-pad-5t fa-lock float-right sr-icons" data-toggle="tooltip" data-placement="left" title="This information will not be shown to anyone until you share it with them."></i></label>
                    <input type="text" name="contact_email" value="{{ $listing->contact_email }}"/>

                    <label for="description" class="space-top">Description:</label>
                    <textarea rows="8" cols="20" name="description" required>{{ str_replace('\n', "\n\n", $listing->description) }}</textarea>

                    <label class="space-top">Existing Gallery Images:</label>
                    <div id="error-container"></div>
                    <div class="existing-images">
                        @foreach ($listing->images as $image)
                        <div class="image image-number-{{ $image->image_number }}" style="background-image: url('{{ $image->file() }}');">
                            <div class="buttons">
                                <a href="#" class="button-set-header" title="Set as header image"><i class="fa fa-photo fa-2x sr-icons"></i></a>
                                <a href="#" class="button-toggle-delete" title="Delete image"><i class="fa fa-trash fa-2x sr-icons"></i></a>
                            </div>
                            <input type="checkbox" class="checkbox-deleted" name="existingimages.{{ $image->image_number }}.deleted"/>
                        </div>
                        @endforeach
                        <input type="hidden" id="input-header-image" name="header_image" value="{{ $listing->header->image_number }}"/>
                    </div>
                    <a href="#" id="button-delete-all"><i class="fa fa-trash fa-pad-5 sr-icons"></i>Delete all existing images.</a>

                    <label for="images[]" class="space-top">New Gallery Images:</label>
                    <div class="input image-upload-box">
                        <input type="file" name="images[]" id="images" accept="image/jpeg, image/png" multiple title="test"/>
                        <div id="preview-container">
                            <div id="preview">
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 12px;">Use Shift and Control keys to select multiple images.</p>

                    <button class="btn btn-primary btn-xl space-top" id="save-button">Save</button>
                </form>
            </div>

            <div class="col-lg-3 no-float"></div>

            @include('scripts.imageupload')

            @include('scripts.editexistingimages')

            @include('scripts.tooltips')

        </div>
    </div>

@endsection