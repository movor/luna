@extends('layouts.default')

@section('content')

    <div class="container page-about">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="px-lg-3 mb-5">
                    <h1 class="mb-3">About Us</h1>
                    <p>
                        Movor Uni is developed by Movor.
                        You can find <strong><a href="https://movor.io" target="_blank">more about us here</a></strong>.
                    </p>
                </div>
                <div class="px-lg-3 mb-5">
                    <h2 class="display-5">
                        <span class="text-primary">The Team:</span>
                    </h2>
                </div>
                <div class="row">

                    {{-- John Doe --}}

                    <div class="col-lg-4 col-md-6">
                        <div class="card mb-5">
                            <div class="card-header">
                                <img class="rounded mb-4" src="img/placeholders/john_doe.jpg?width=120&height=120">
                                <h2 class="text-uppercase">John Doe</h2>
                                <p>American Farmer</p>
                            </div>

                            <div class="card-body">
                                <span class="separator d-block"></span>
                                <h5 class="card-title">Farmer</h5>

                                <p class="card-text">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Accusamus ad consequuntur illum itaque maiores minima nam nulla saepe tempora vel.
                                </p>

                                <p class="card-text">
                                    <span class="d-block">Primary technologies:</span>
                                    <span class="badge badge-primary">Lorem</span>
                                    <span class="badge badge-primary">Ipsum</span>
                                    <span class="badge badge-primary">Dolor</span>
                                    <span class="badge badge-primary">Sit</span>
                                </p>

                                <p class="card-text">
                                    <span class="d-block">Familiar technologies:</span>
                                    <span class="badge badge-secondary">Amet</span>
                                    <span class="badge badge-secondary">Consectetur</span>
                                    <span class="badge badge-secondary">Adipisicing</span>
                                    <span class="badge badge-secondary">Elit</span>
                                </p>

                                <p class="card-text">
                                    Hobby: Accusamus
                                </p>
                            </div>

                            <div class="card-footer">
                                <div class="border-bottom"></div>
                            </div>
                        </div>
                    </div>

                    {{-- /John Doe --}}

                </div>
            </div>
        </div>
    </div>

@endsection