@extends('admin.layout.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h4 class="card-title">Jenisbunga</h4>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="mdi mdi-calendar"></i> Today (22 Nov 2024)
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                        <a class="dropdown-item" href="#">January - March</a>
                                        <a class="dropdown-item" href="#">March - June</a>
                                        <a class="dropdown-item" href="#">June - August</a>
                                        <a class="dropdown-item" href="#">August - November</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $title }}</h4>

                            @if (Session::has('error_message'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> {{ Session::get('error_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form class="forms-sample" @if (empty($jenisbunga['id'])) action="{{ url('admin/add-edit-jenisbunga') }}" @else action="{{ url('admin/add-edit-jenisbunga/' . $jenisbunga['id']) }}" @endif method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="jenisbunga_name">Jenisbunga Name</label>
                                    <input type="text" class="form-control" id="jenisbunga_name" placeholder="Enter Jenisbunga Name" name="jenisbunga_name" @if (!empty($jenisbunga['jenisbunga_name'])) value="{{ $jenisbunga['jenisbunga_name'] }}" @else value="{{ old('jenisbunga_name') }}" @endif>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="section_id">Select Section</label>
                                    <select name="section_id" id="section_id" class="form-control" style="color: #000">
                                        <option value="">Select Section</option>
                                        @foreach ($getSections as $section)
                                            <option value="{{ $section['id'] }}" @if (!empty($jenisbunga['section_id']) && $jenisbunga['section_id'] == $section['id']) selected @endif>{{ $section['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div id="appendJenisbungaLevel">
                                    @include('admin.jenisbunga.append_jenisbunga_level')
                                </div>

                                <div class="form-group">
                                    <label for="jenisbunga_image">Jenisbunga Image</label>
                                    <input type="file" class="form-control" id="jenisbunga_image" name="jenisbunga_image">
                                    @if (!empty($jenisbunga['jenisbunga_image']))
                                        <a target="_blank" href="{{ url('front/images/jenisbunga_images/' . $jenisbunga['jenisbunga_image']) }}">View Jenisbunga Image</a>&nbsp;|&nbsp;
                                        <a href="JavaScript:void(0)" class="confirmDelete" module="jenisbunga-image" moduleid="{{ $jenisbunga['id'] }}">Delete Jenisbunga Image</a>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="jenisbunga_discount">Jenisbunga Discount</label>
                                    <input type="text" class="form-control" id="jenisbunga_discount" placeholder="Enter Jenisbunga Discount" name="jenisbunga_discount" @if (!empty($jenisbunga['jenisbunga_discount'])) value="{{ $jenisbunga['jenisbunga_discount'] }}" @else value="{{ old('jenisbunga_discount') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="description">Jenisbunga Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{ $jenisbunga['description'] }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="url">Jenisbunga URL</label>
                                    <input type="text" class="form-control" id="url" placeholder="Enter Jenisbunga URL" name="url" @if (!empty($jenisbunga['url'])) value="{{ $jenisbunga['url'] }}" @else value="{{ old('url') }}" @endif>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-light">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.layout.footer')
    </div>
@endsection
