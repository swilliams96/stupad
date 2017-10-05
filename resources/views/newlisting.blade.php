@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

        @include('common.dashboardsidebar', ['page' => 'newlisting'])


        <!-- MAIN PAGE -->
            <div class="col-lg-6 mainpage no-float no-select">
                <h1>New Listing</h1>
                <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

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
                    <input type="text" name="title" required/>

                    <label for="rent_value" class="space-top">Rent:</label>
                    £<input type="number" id="rentvalue" name="rent_value" step="0.01" min="0" required/>
                    per
                    <select name="rent_period" required>
                        <option>week</option>
                        <option>month</option>
                    </select>

                    <label for="address1" class="space-top">Address 1:</label>
                    <input type="text" name="address1" required/>

                    <label for="address2">Address 2:</label>
                    <input type="text" name="address2"/>

                    <label for="town">Town/City:</label>
                    <input type="text" name="town" required/>

                    <label for="postcode">Postcode:</label>
                    <input type="text" name="postcode" required/>

                    <div class="properties-table space-top">
                        <div>
                            <label for="bedrooms"><i class="fa fa-bed fa-pad-5 fa-pad-5l sr-icons"></i>Bedrooms:</label>
                            <select name="bedrooms" required>
                                <option value=""></option>
                                <option value="0">Studio</option>
                                <option value="1">1 bedroom</option>
                                <option value="2">2 bedrooms</option>
                                <option value="3">3 bedrooms</option>
                                <option value="4">4 bedrooms</option>
                                <option value="5">5 bedrooms</option>
                                <option value="6">6 bedrooms</option>
                                <option value="7">7 bedrooms</option>
                                <option value="8">8+ bedrooms</option>
                            </select>
                        </div>
                        <div>
                            <label for="bathrooms"><i class="fa fa-bath fa-pad-5 fa-pad-5l sr-icons"></i>Bathrooms:</label>
                            <select name="bathrooms" required>
                                <option value=""></option>
                                <option value="1">1 bathroom</option>
                                <option value="2">2 bathrooms</option>
                                <option value="3">3 bathrooms</option>
                                <option value="4">4 bathrooms</option>
                                <option value="5">5 bathrooms</option>
                                <option value="6">6+ bathrooms</option>
                            </select>
                        </div>
                    </div>

                    <div class="properties-table space-top">
                        <div>
                            <label for="furnished"><i class="fa fa-check  fa-pad-5 fa-pad-5l sr-icons"></i>Furnished:</label>
                            <select name="furnished" required>
                                <option value=""></option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div>
                            <label for="bills"><i class="fa fa-envelope-open fa-pad-5 fa-pad-5l sr-icons"></i>Bills Included:</label>
                            <select name="bills" required>
                                <option value=""></option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div>
                            <label for="pets"><i class="fa fa-paw fa-pad-5 fa-pad-5l sr-icons"></i>Pets Allowed:</label>
                            <select name="pets" required>
                                <option value=""></option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <label for="description" class="space-top">Description:</label>
                    <textarea rows="8" cols="20" name="description" required></textarea>

                    <label for="images[]" class="space-top">Gallery Images:</label>
                    <div class="input image-upload-box">
                        <input type="file" name="images[]" id="images" accept="image/jpeg, image/png" multiple required/>
                        <div id="preview-container">
                            <div id="preview">
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 12px;">Use Shift and Control keys to select multiple images.</p>

                    <button class="btn btn-primary btn-xl space-top">Create</button>
                </form>
            </div>

            <div class="col-lg-3 no-float"></div>

            @include('scripts.imageupload')

        </div>
    </div>

@endsection