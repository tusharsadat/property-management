<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>Easy - RealState </title>

    <!-- Fav Icon -->
    <link rel="icon" href="{{ asset('frontend/assets/images/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Stylesheets -->
    <link href="{{ asset('frontend/assets/css/font-awesome-all.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/owl.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/color/theme-color.css') }}" id="jssDefault" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/switcher-style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/responsive.css') }}" rel="stylesheet">
    <!-- Toastr styles -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <!-- End Toastr styles -->
</head>


<!-- page wrapper -->

<body>

    <div class="boxed_wrapper">


        <!-- preloader -->
        @include('frontend.home.preloader')
        <!-- preloader end -->


        <!-- switcher menu -->

        <!-- end switcher menu -->


        <!-- main header -->
        @include('frontend.home.header')
        <!-- main-header end -->

        <!-- Mobile Menu  -->
        @include('frontend.home.mobile_menu')
        <!-- End Mobile Menu -->

        @yield('main')

        <!-- main-footer -->
        @include('frontend.home.footer')
        <!-- main-footer end -->



        <!--Scroll to top-->
        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fal fa-angle-up"></span>
        </button>
    </div>


    <!-- jequery plugins -->
    <script src="{{ asset('frontend/assets/js/jquery.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/owl.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/validation.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/appear.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/scrollbar.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jQuery.style.switcher.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nav-tool.js') }}"></script>

    <!-- map script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOQcZBfcZ5hWdaZ6PK3sQTkvmPGGLJomQ"></script>
    <script src="{{ asset('frontend/assets/js/gmaps.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/map-helper.js') }}"></script>

    <!-- main-js -->
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;
                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;
                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;
                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- End of SweetAlert2 -->

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // Add To Wishlist 
        function addToWishList(property_id) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "/add-to-wishList/" + property_id,
                success: function(data) {
                    if (data.success) {
                        toastr.success(data.success);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr);

                    if (xhr.status === 401) {
                        // User is not logged in - Show Toastr error first
                        toastr.error("Please log in first!");

                        // After 3 seconds, redirect to login page
                        setTimeout(function() {
                            window.location.href = "/login";
                        }, 3000);
                    } else if (xhr.status === 409) {
                        // Property already in wishlist
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        // General error handling
                        toastr.error("Something went wrong! Please try again.");
                    }
                }
            })
        }
    </script>

    <!-- // start load Wishlist Data  -->
    <script type="text/javascript">
        function wishlist() {
            $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "/get-wishlist-property/",
                })
                .done(function(response) {
                    // Update Wishlist Quantity
                    $('#wishQty').text(response.wishQty);

                    // Check if wishlist is empty
                    if (response.wishlist.length === 0) {
                        $('#wishlist').html(`<p class="text-center text-muted">Your wishlist is empty.</p>`);
                        return;
                    }

                    // Build Wishlist Items
                    let wishlistItems = response.wishlist.map(item => `
                    <div class="deals-block-one">
                        <div class="inner-box">
                            <div class="image-box">
                                <figure class="image">
                                    <img src="/${item.property.property_thambnail}" alt="${item.property.property_name}">
                                </figure>
                                <div class="batch"><i class="icon-11"></i></div>
                                <span class="category">Featured</span>
                                <div class="buy-btn">
                                    <a href="#">For ${item.property.property_status}</a>
                                </div>
                            </div>
                            <div class="lower-content">
                                <div class="title-text">
                                    <h4><a href="#">${item.property.property_name}</a></h4>
                                </div>
                                <div class="price-box clearfix">
                                    <div class="price-info pull-left">
                                        <h6>Start From</h6>
                                        <h4>$${item.property.lowest_price}</h4>
                                    </div>
                                </div>
                                <ul class="more-details clearfix">
                                    <li><i class="icon-14"></i>${item.property.bedrooms} Beds</li>
                                    <li><i class="icon-15"></i>${item.property.bathrooms} Baths</li>
                                    <li><i class="icon-16"></i>${item.property.property_size} Sq Ft</li>
                                </ul>
                                <div class="other-info-box clearfix">
                                    <ul class="other-option pull-right clearfix">
                                        <li><a type="submit" class="text-body" id="${item.id}" onclick="wishlistRemove(this.id)" ><i class="fa fa-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');

                    // Update Wishlist UI
                    $('#wishlist').html(wishlistItems);
                })
                .fail(function(xhr, status, error) {
                    console.error("Error fetching wishlist:", error);

                    if (xhr.status === 401) {
                        // User is not logged in, hide wishlist and show message instead of alert
                        $('#wishlist').html(
                            `<p class="text-center text-danger">Please log in to view your wishlist.</p>`);
                        $('#wishQty').text(0); // Reset wish quantity
                    } else {
                        // Show error only for actual server issues (not login-related)
                        toastr.error("Failed to load your wishlist. Please try again later!");
                    }
                });
        }

        // Load Wishlist on Page Load
        wishlist();

        // Wishlist Remove Function
        function wishlistRemove(id) {
            $.ajax({
                type: "DELETE", // Using DELETE instead of GET for better API design
                dataType: 'json',
                url: "/wishlist-remove/" + id,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                },
                success: function(data) {
                    wishlist(); // Refresh wishlist after removal

                    // SweetAlert Notification
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.success
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.error || "Something went wrong!"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle server errors gracefully
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: xhr.responseJSON?.error || "Failed to remove item from wishlist!"
                    });
                }
            });
        }
    </script>

    <script type="text/javascript">
        function addToCompare(property_id) {
            $.ajax({
                type: "POST",
                url: "/add-to-compare/" + property_id,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        toastr.success(data.success);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr);

                    if (xhr.status === 401) {
                        // User is not logged in - Show Toastr error first
                        toastr.error("Please log in first!");

                        // After 3 seconds, redirect to login page
                        setTimeout(function() {
                            window.location.href = "/login";
                        }, 3000);
                    } else if (xhr.status === 409) {
                        // Property already in compare list
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        // General error handling
                        toastr.error("Something went wrong! Please try again.");
                    }
                }
            });
        }
    </script>
    <script type="text/javascript">
        function compare() {
            $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "/get-compare-property/",
                })
                .done(function(response) {
                    // Update compare list Quantity
                    $('#compareCount').text(response.compareCount);

                    // Check if compare list is empty
                    if (response.compare.length === 0) {
                        $('#compare').html(`<p class="text-center text-muted">Your compare list is empty.</p>`);
                        return;
                    }

                    // Build compare list Items
                    let compareLists = response.compare.map(item => `
                    <tr>
                    <td>Image</td>
                    <td class="property-image">
                        <img src="/${item.property.property_thambnail}" alt="${item.property.property_name}" class="img-fluid" style="max-height: 100px;">
                    </td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td class="property-title">${item.property.property_name}</td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td class="property-price">$${item.property.lowest_price}</td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>${item.property.city}</td>
                </tr>
                <tr>
                    <td>Area</td>
                    <td>${item.property.property_size} Sq Ft</td>
                </tr>
                <tr>
                    <td>Bedrooms</td>
                    <td>${item.property.bedrooms}</td>
                </tr>
                <tr>
                    <td>Bathrooms</td>
                    <td>${item.property.bathrooms}</td>
                </tr>
                <tr>
                    <td>Actions</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeCompare(${item.id})">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </td>
                </tr>
                `).join('');

                    // Update Wishlist UI
                    $('#compare').html(compareLists);
                })
                .fail(function(xhr, status, error) {
                    console.error("Error fetching compare:", error);

                    if (xhr.status === 401) {
                        // User is not logged in, hide compare and show message instead of alert
                        $('#compare').html(
                            `<p class="text-center text-danger">Please log in to view your compare list.</p>`);
                        $('#compareCount').text(0); // Reset wish quantity
                    } else {
                        // Show error only for actual server issues (not login-related)
                        toastr.error("Failed to load your compare list. Please try again later!");
                    }
                });
        }

        // Load the compare list on page load
        compare();

        // Wishlist Remove Function
        function removeCompare(compareId) {
            $.ajax({
                type: "DELETE", // Use DELETE HTTP method
                url: "/compare-remove/" + compareId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token for Laravel
                },
                success: function(response) {
                    // Success Toast Notification
                    toastr.success(response.success, 'Success', {
                        timeOut: 3000
                    });

                    // Optionally refresh the compare list
                    compare(); // Call your compare function to reload the list
                },
                error: function(xhr) {
                    // Handle Unauthorized (401) Error
                    if (xhr.status === 401) {
                        toastr.error('Please log in to perform this action.', 'Unauthorized', {
                            timeOut: 3000
                        });
                        // Redirect to the login page
                        setTimeout(() => {
                            window.location.href = "/login";
                        }, 3000);
                    }
                    // Handle Not Found (404) Error
                    else if (xhr.status === 404) {
                        toastr.error('The property was not found in your compare list.', 'Not Found', {
                            timeOut: 3000
                        });
                    }
                    // Handle Other Errors
                    else {
                        toastr.error('Something went wrong. Please try again later.', 'Error', {
                            timeOut: 3000
                        });
                    }
                }
            });
        }
    </script>
    {{-- Use jQuery to handle property Message Form submission via AJAX. --}}
    <script>
        $(document).ready(function() {
            $('#propertyMessageForm').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('property.message') }}", // Laravel route for form submission
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show Toastr success alert
                            toastr.success(response.message);
                            // Reset the form
                            $('#propertyMessageForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            // Show Toastr error alert for unauthenticated users
                            toastr.error('Please login to send a message.');
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Show validation errors in Toastr
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Something went wrong! Please try again.');
                        }
                    },
                });
            });
        });
    </script>


</body><!-- End of .page_wrapper -->

</html>
