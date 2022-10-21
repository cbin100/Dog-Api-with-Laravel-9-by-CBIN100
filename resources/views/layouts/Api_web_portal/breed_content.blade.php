@extends('layouts.Api_web_portal.app-master')

@section('content')
    <div class="bg-light p-5 rounded">

        <div class="container px-4">
            <h1>Dog Api</h1>
            <form method="post" action="{{ route('portal_get_breeds') }}">
                @csrf

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h2>Import Breeds from External API</h2>
                        <div class="input-group">
                            <input class="btn btn-outline-secondary" type="submit" name="btn_import_all_breeds_from_external" value="Click to import and save breeds">
                        </div>

                    </div>
                </div>







                <div class="card">
                    <div class="card-body">
                        <h2>Get All Breed</h2>
                        <div class="input-group">
                            <input class="btn btn-outline-secondary" type="submit" name="btn_get_all_breed_from_external" value="From External Api">
                        </div>
                    @if(isset($data['breeds_from_external']))
                    <div class="row">
                        <strong> Endpoint:  {{ $data['endpoint'] }} </strong>
                        <ol class="list-group list-group-numbered">
                            {{ $data['general_helper']->apiGetResponse($data['breeds_from_external'], 'message') }}

                        </ol>
                    </div>
                    @endif

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h2>Get Specific Breed</h2>
                        <div class="input-group">
                            <input class="form-control form-control-lg" type="text" name="txt_specific_breed_from_external" class="form-control @error('txt_specific_breed_from_external') is-invalid @enderror" placeholder="Enter Specific Breed. e.g hound" aria-label=".form-control-lg example">
                            @error('txt_specific_breed_from_external')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <input class="btn btn-outline-secondary" type="submit" name="btn_get_specific_breed_from_external" value="From External Api">

                        </div>

                    @if(isset($data['specific_breeds']))
                        @if(is_array($data['specific_breeds']))
                            <div class="row">
                                <strong> Endpoint:  {{ $data['endpoint'] }} </strong>
                                <ol class="list-group list-group-numbered">
                                    {{ $data['general_helper']->apiGetResponse($data['specific_breeds'], 'message') }}
                                </ol>
                            </div>
                        @endif
                    @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h2>Get Random Breed</h2>
                        <div class="input-group">

                            <input class="btn btn-outline-secondary" type="submit" name="btn_get_5_random_breeds_from_external" value="Get 5 Random breeds from External Api">

                        </div>
                        @if(isset($data['five_random_breeds']))
                            <div class="row">
                                <ol class="list-group list-group-numbered">
                                   <strong> Endpoint:  {{ $data['endpoint'] }} </strong>
                                    {{ $data['general_helper']->apiGetResponse($data['five_random_breeds'], 'message') }}
                                </ol>
                            </div>
                        @endif


                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h2>Get Breed Random Image</h2>
                        <div class="input-group">

                            <input class="btn btn-outline-secondary" type="submit" name="btn_get_random_breeds_image_from_external" value="From External Api">
                        </div>
                        @if(isset($data['random_breeds_image_from_external']))
                            <div class="row">
                                <strong> Endpoint:  {{ $data['endpoint'] }} </strong>
                              <img src="{{ $data['random_breeds_image_from_external'] }}" style="width:700px;height:600px;">
                            </div>
                        @endif

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

