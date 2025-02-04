@extends('frontend.frontend_dashboard')
@section('main')
    <style>
        .comparison-table {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .comparison-table th,
        .comparison-table td {
            text-align: center;
            vertical-align: middle;
        }

        .property-image img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
        }

        .property-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .property-price {
            font-size: 1rem;
            color: #28a745;
        }

        .comparison-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
    <!--Page Title-->
    <section class="page-title-two bg-color-1 centred">
        <div class="pattern-layer">
            <div class="pattern-1" style="background-image: url({{ asset('frontend/assets/images/shape/shape-9.png') }});">
            </div>
            <div class="pattern-2" style="background-image: url({{ asset('frontend/assets/images/shape/shape-10.png') }});">
            </div>
        </div>
        <div class="auto-container">
            <div class="content-box clearfix">
                <h1>Compare Properties</h1>
                <ul class="bread-crumb clearfix">
                    <li><a href="index.html">Home</a></li>
                    <li>Compare Properties</li>
                </ul>
            </div>
        </div>
    </section>
    <!--End Page Title-->
    <!-- properties-section -->
    <section>
        <div class="container my-5">
            <div class="comparison-table table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Features</th>
                            <th>Property Details</th>
                        </tr>
                    </thead>
                    <tbody id="compare">
                        <!-- Compare list items will be dynamically inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- properties-section end -->
    <!-- subscribe-section -->
    <section class="subscribe-section bg-color-3">
        <div class="pattern-layer" style="background-image: url({{ asset('frontend/assets/images/shape/shape-2.png') }});">
        </div>
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                    <div class="text">
                        <span>Subscribe</span>
                        <h2>Sign Up To Our Newsletter To Get The Latest News And Offers.</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 form-column">
                    <div class="form-inner">
                        <form action="contact.html" method="post" class="subscribe-form">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Enter your email" required="">
                                <button type="submit">Subscribe Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- subscribe-section end -->
@endsection
