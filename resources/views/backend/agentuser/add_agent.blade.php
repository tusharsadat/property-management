@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <div class="page-content">

        <div class="row profile-body">
            <!-- left wrapper start -->

            <!-- left wrapper end -->
            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Add Agent </h6>
                            <form id="myForm" method="POST" action="{{ route('store.agent') }}" class="forms-sample">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Agent Name </label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Agent Email </label>
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Agent Phone </label>
                                    <input type="text" id="phone" name="phone" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Agent Address </label>
                                    <input type="text" name="address" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" id="checkpassword" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" />
                                </div>


                                <button type="submit" class="btn btn-primary me-2">Save Changes </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- middle wrapper end -->
            <!-- right wrapper start -->

            <!-- right wrapper end -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "/validate-email", // Route for AJAX validation
                            type: "POST",
                            data: {
                                email: function() {
                                    return $('#email').val();
                                },
                                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                            },
                        },
                    },
                    phone: {
                        required: true,
                        remote: {
                            url: "/validate-phone", // Route for AJAX validation
                            type: "POST",
                            data: {
                                phone: function() {
                                    return $('#phone').val();
                                },
                                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                            },
                        },
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#checkpassword", // Ensures this matches the password field
                    },

                },
                messages: {
                    name: {
                        required: 'Agent Name is required'
                    },
                    email: {
                        required: 'Email is required',
                        email: 'Invalid email format',
                        remote: 'Email already exists',
                    },
                    phone: {
                        required: 'Phone number is required',
                        remote: 'Phone number already exists',
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long",
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match",
                    },

                },


                errorPlacement: function(error, element) {
                    // Display error messages directly under the input fields
                    error.insertAfter(element).addClass(
                        'text-danger'); // Adds bootstrap 'text-danger' class for styling
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },

            });
        });
    </script>
@endsection
