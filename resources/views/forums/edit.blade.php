@extends('layouts.app')

@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-7 col-xl-7">
            @foreach ($data->discussions as $item)      
                <div class="card__wrapper">
                    <div class="project__details-top align-items-center gap-10">
                        <div class="header-user d-flex">
                            <div class="d-flex gap-2">
                                <figure class="img-height-user">
                                    <img src="{{$item->user->profileImage()}}" alt="">
                                </figure>
                                <div class="mr-3">
                                    <p class="m-0"><strong>{{$item->user->name}}</strong> </p>
                                    <p class="m-0">{{$item->created_at->format('h:i A | d F, Y')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="project__details-title mt-2">
                            <div class="project__details-meta d-flex flex-wrap align-items-center g-5">
                                {!! $item->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="col-xxl-5 col-xl-5">
            <div class="position-sticky">
                <div class="card__wrapper">
                    <div class="card__body">
                        <form action="{{route('forums.update',$data->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                           
                            <div class="from__input-box">
                                <div class="form__input-title">
                                    <label>Content <span>*</span></label>
                                </div>
                                <div class="from__input-box">
                                    <textarea name="content" id="tinymce_simple_textarea"></textarea>
                                </div>
                            </div>
                            <div class="from__input-box">
                                <div class="form__input-title">
                                    <label>Attached files</label>
                                </div>
                                <div class="from__input-box">
                                    <input id="image-file" type="file" name="images[]" multiple
                                        data-browse-on-zone-click="true">
                                </div>
                            </div>
                            <div class="form__input-box d-flex justify-content-end gap-15">
                                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/fileinput.css') }}">
        
    @endpush
    @push('js')
        <script src="{{ asset('js/fileinput.js') }}"></script>

        <script>
            $("#image-file").fileinput({
                showUpload: false,
                theme: 'fa',
                showBrowse: false,
                dropZoneEnabled: true,
                uploadUrl: "{{ route('forums.upload') }}",
                overwriteInitial: false,
                maxFileSize: 20000000,
                maxFilesNum: 20,
                
                uploadExtraData: function() {
                    return {
                        created_at: $('.created_at').val()
                    };
                }
            });
        </script>
    @endpush
