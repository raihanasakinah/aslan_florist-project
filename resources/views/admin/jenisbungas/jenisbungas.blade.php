@extends('admin.layout.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Jenisbunga</h4>

                            <a href="{{ url('admin/add-edit-jenisbunga') }}"
                                style="max-width: 150px; float: right; display: inline-block"
                                class="btn btn-block btn-primary">Add Jenisbunga</a>

                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive pt-3">
                                <table id="jenisbunga" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Jenisbunga Name</th>
                                            <th>Parent Jenisbunga</th>
                                            {{-- <th>Parent Section</th> --}}
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jenisbungas as $jenisbunga)
                                            @if (isset($jenisbunga['parent_jenisbunga']['jenisbunga_name']) && !empty($jenisbunga['parent_jenisbunga']['jenisbunga_name']))
                                                @php $parent_jenisbunga = $jenisbunga['parent_jenisbunga']['jenisbunga_name']; @endphp
                                            @else
                                                @php $parent_jenisbunga = 'Root'; @endphp
                                            @endif
                                            <tr>
                                                <td>{{ $jenisbunga['id'] }}</td>
                                                <td>{{ $jenisbunga['jenisbunga_name'] }}</td>
                                                <td>{{ $parent_jenisbunga }}</td>
                                                {{-- @if (isset($jenisbunga['section']['name']))
                                                    <td>{{ $jenisbunga['section']['name'] }}</td>
                                                @else
                                                    <td>- </td>
                                                @endif --}}
                                                <td>{{ $jenisbunga['url'] }}</td>
                                                <td>
                                                    @if ($jenisbunga['status'] == 1)
                                                        <a class="updateJenisbungaStatus" id="jenisbunga-{{ $jenisbunga['id'] }}"
                                                            jenisbunga_id="{{ $jenisbunga['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check"
                                                                status="Active"></i>
                                                        </a>
                                                    @else
                                                        <a class="updateJenisbungaStatus" id="jenisbunga-{{ $jenisbunga['id'] }}"
                                                            jenisbunga_id="{{ $jenisbunga['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline"
                                                                status="Inactive"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/add-edit-jenisbunga/' . $jenisbunga['id']) }}">
                                                        <i style="font-size: 25px" class="mdi mdi-pencil-box"></i>
                                                    </a>

                                                    <a href="JavaScript:void(0)" class="confirmDelete" module="jenisbunga"
                                                        moduleid="{{ $jenisbunga['id'] }}">
                                                        <i style="font-size: 25px" class="mdi mdi-file-excel-box"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022. All rights
                    reserved.</span>
            </div>
        </footer>
    </div>
@endsection
