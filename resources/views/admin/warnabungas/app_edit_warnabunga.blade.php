@extends('admin.layout.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h4 class="card-title">Warnabunga</h4>
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

                            <form class="forms-sample" @if (empty($warnabunga['id'])) action="{{ url('admin/add-edit-warnabunga') }}" @else action="{{ url('admin/add-edit-warnabunga/' . $warnabunga['id']) }}" @endif method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="warnabunga_name">Warnabunga Name</label>
                                    <input type="text" class="form-control" id="warnabunga_name" placeholder="Enter Warnabunga Name" name="warnabunga_name" @if (!empty($warnabunga['warnabunga_name'])) value="{{ $warnabunga['warnabunga_name'] }}" @else value="{{ old('warnabunga_name') }}" @endif>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="section_id">Select Section</label>
                                    <select name="section_id" id="section_id" class="form-control" style="color: #000">
                                        <option value="">Select Section</option>
                                        @foreach ($getSections as $section)
                                            <option value="{{ $section['id'] }}" @if (!empty($warnabunga['section_id']) && $warnabunga['section_id'] == $section['id']) selected @endif>{{ $section['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div id="appendWarnabungaLevel">
                                    @include('admin.warnabunga.append_warnabunga_level')
                                </div>

                                <div class="form-group">
                                    <label for="warnabunga_image">Warnabunga Image</label>
                                    <input type="file" class="form-control" id="warnabunga_image" name="warnabunga_image">
                                    @if (!empty($warnabunga['warnabunga_image']))
                                        <a target="_blank" href="{{ url('front/images/warnabunga_images/' . $warnabunga['warnabunga_image']) }}">View Warnabunga Image</a>&nbsp;|&nbsp;
                                        <a href="JavaScript:void(0)" class="confirmDelete" module="warnabunga-image" moduleid="{{ $warnabunga['id'] }}">Delete Warnabunga Image</a>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="warnabunga_discount">Warnabunga Discount</label>
                                    <input type="text" class="form-control" id="warnabunga_discount" placeholder="Enter Warnabunga Discount" name="warnabunga_discount" @if (!empty($warnabunga['warnabunga_discount'])) value="{{ $warnabunga['warnabunga_discount'] }}" @else value="{{ old('warnabunga_discount') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="description">Warnabunga Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{ $warnabunga['description'] }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="url">Warnabunga URL</label>
                                    <input type="text" class="form-control" id="url" placeholder="Enter Warnabunga URL" name="url" @if (!empty($warnabunga['url'])) value="{{ $warnabunga['url'] }}" @else value="{{ old('url') }}" @endif>
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
