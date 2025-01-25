@extends('agent.agent_dashboard')
@section('agent')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <div class="page-content">
        <nav class="page-breadcrumb">
            <ol class="breadcrumb">

            </ol>
        </nav>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-3 mt-4">Choose a plan</h3>

                        <div class="container">
                            <div class="row">
                                @foreach ($packages as $package)
                                    <div class="col-md-4 stretch-card grid-margin grid-margin-md-0">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="text-center mt-3 mb-4">{{ $package->package_name }}</h4>
                                                <i data-feather="award"
                                                    class="text-primary icon-xxl d-block mx-auto my-3"></i>
                                                <h1 class="text-center">${{ $package->package_amount }}</h1>
                                                <p class="text-muted text-center mb-4 fw-light">Limited</p>
                                                <h5 class="text-primary text-center mb-4">Up to
                                                    {{ $package->property_limit }} Property </h5>
                                                <table class="mx-auto">
                                                    <tr>
                                                        <td><i data-feather="check" class="icon-md text-primary me-2"></i>
                                                        </td>
                                                        <td>
                                                            <p>Up to {{ $package->property_limit }} Property</p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td><i data-feather="x" class="icon-md text-danger me-2"></i></td>
                                                        <td>
                                                            <p class="text-muted">Premium Support</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div class="d-grid">
                                                    <a href="{{ route('package.invoice', $package->id) }}"
                                                        class="btn btn-primary mt-4">Start Now </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
