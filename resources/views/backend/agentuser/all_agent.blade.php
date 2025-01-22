@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <div class="page-content">
        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <a href="{{ route('add.agent') }}" class="btn btn-inverse-info"> Add Agent </a>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Agent All </h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Sl </th>
                                        <th>Image </th>
                                        <th>Name </th>
                                        <th>Role </th>
                                        <th>Status </th>
                                        <th>Change </th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allagent as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><img src="{{ !empty($item->photo) ? url('upload/agent_images/' . $item->photo) : url('upload/no_image.jpg') }}"
                                                    style="width:70px; height:40px;"> </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->role }}</td>
                                            <td>
                                                @if ($item->status == 'active')
                                                    <span class="badge rounded-pill bg-success">Active</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger">InActive</span>
                                                @endif
                                            </td>
                                            <td>Change </td>
                                            <td>
                                                <input data-id="{{ $item->id }}" class="toggle-class" type="checkbox"
                                                    data-onstyle="success" data-offstyle="danger" data-toggle="toggle"
                                                    data-on="Active" data-off="Inactive"
                                                    {{ $item->status ? 'checked' : '' }}>
                                            </td>
                                            <td>

                                                <a href="{{ route('edit.agent', $item->id) }}"
                                                    class="btn btn-inverse-warning" title="Edit"> <i
                                                        data-feather="edit"></i> </a>
                                                <form action="{{ route('delete.agent', $item->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-inverse-danger"
                                                        id="delete-btn">Delete</button>
                                                </form>

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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.toggle-class').change(function() {
                var status = $(this).prop('checked') ? 1 : 0;
                var user_id = $(this).data('id');

                $.ajax({
                    url: '/changeStatus',
                    type: 'PATCH', // Ensure you match the backend HTTP method
                    data: {
                        status: status,
                        user_id: user_id,
                        _token: $('meta[name="csrf-token"]').attr('content') // Send CSRF token
                    },
                    success: function(data) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        });
                    },
                    error: function(error) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'error',
                            title: error.responseJSON.message || 'An error occurred'
                        });
                    }
                });
            });
        });
    </script>
@endsection
