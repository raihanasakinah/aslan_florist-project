@extends('admin.layout.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Warnabunga</h4>

                            <a href="{{ url('admin/add-edit-warnabunga') }}"
                                style="max-width: 150px; float: right; display: inline-block"
                                class="btn btn-block btn-primary">Add Warnabunga</a>

                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive pt-3">
                                <table id="warnabunga" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Warnabunga Name</th>
                                            <th>Parent Warnabunga</th>
                                            {{-- <th>Parent Section</th> --}}
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($warnabungas as $warnabunga)
                                            @if (isset($warnabunga['parent_warnabunga']['warnabunga_name']) && !empty($warnabunga['parent_warnabunga']['warnabunga_name']))
                                                @php $parent_warnabunga = $warnabunga['parent_warnabunga']['warnabunga_name']; @endphp
                                            @else
                                                @php $parent_warnabunga = 'Root'; @endphp
                                            @endif
                                            <tr>
                                                <td>{{ $warnabunga['id'] }}</td>
                                                <td>{{ $warnabunga['warnabunga_name'] }}</td>
                                                <td>{{ $parent_warnabunga }}</td>
                                                {{-- @if (isset($warnabunga['section']['name']))
                                                    <td>{{ $warnabunga['section']['name'] }}</td>
                                                @else
                                                    <td>- </td>
                                                @endif --}}
                                                <td>{{ $warnabunga['url'] }}</td>
                                                <td>
                                                    @if ($warnabunga['status'] == 1)
                                                        <a class="updateWarnabungaStatus" id="warnabunga-{{ $warnabunga['id'] }}"
                                                            warnabunga_id="{{ $warnabunga['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check"
                                                                status="Active"></i>
                                                        </a>
                                                    @else
                                                        <a class="updateWarnabungaStatus" id="warnabunga-{{ $warnabunga['id'] }}"
                                                            warnabunga_id="{{ $warnabunga['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline"
                                                                status="Inactive"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/add-edit-warnabunga/' . $warnabunga['id']) }}">
                                                        <i style="font-size: 25px" class="mdi mdi-pencil-box"></i>
                                                    </a>

                                                    <a href="JavaScript:void(0)" class="confirmDelete" module="warnabunga"
                                                        moduleid="{{ $warnabunga['id'] }}">
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
