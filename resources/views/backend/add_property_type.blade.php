@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">

        <div class="row profile-body">

            <!-- wrapper start -->
            <div class="d-none d-md-block left-wrapper">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Add Property Type</h6>

                        <form method="POST" action="{{ route('store.type') }}" class="forms-sample">
                            @csrf
                            <div class="mb-3">
                                <label for="Username" class="form-label">Type name</label>
                                <input type="text" name="type_name"
                                    class="form-control @error('type_name') is-invalid @enderror" autocomplete="off">

                                @error('type_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Type Icon</label>
                                <input type="text" name="type_icon"
                                    class="form-control @error('type_icon') is-invalid @enderror" autocomplete="off">

                                @error('type_icon')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
            <!-- wrapper end -->
        </div>

    </div>
@endsection
