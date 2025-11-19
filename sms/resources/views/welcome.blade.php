@extends('layouts.app')

@section('content')

    <section class="bg-primary text-white py-3 py-md-5">
    <div class="container py-3 py-md-5">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3 mb-md-4">Transform Your School Management</h1>
                <p class="lead mb-4 mb-md-5 fs-5">
                    Axia School Management System provides a comprehensive platform for schools, students, teachers, and
                    parents to streamline educational processes and enhance learning experiences.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-light btn-lg px-5 py-3 fw-semibold">
                            <i class="fas fa-arrow-right me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-semibold">
                            Register Now
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                            Learn More
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Stats Section -->
    <section class="bg-white py-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="text-primary fw-bold display-6">500+</div>
                    <p class="text-muted mb-0">Schools</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-primary fw-bold display-6">50K+</div>
                    <p class="text-muted mb-0">Students</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-primary fw-bold display-6">5K+</div>
                    <p class="text-muted mb-0">Teachers</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-primary fw-bold display-6">99%</div>
                    <p class="text-muted mb-0">Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-0 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Powerful Features for Every Role</h2>
                <p class="lead text-muted mx-auto" style="max-width: 600px;">
                    Designed to meet the unique needs of every member in the educational ecosystem
                </p>
            </div>

            <!-- Role Features - Horizontal Layout -->
            <div class="row g-4">
                <!-- Student Feature -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="role-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                            <h3 class="h4 fw-bold text-center mb-4">For Students</h3>
                            <ul class="feature-list list-unstyled">
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Access course materials and assignments</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Track academic progress and grades</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Communicate with teachers directly</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>View class schedules and timetables</span>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Submit assignments online</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Teacher Feature -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="role-icon bg-success bg-opacity-10 text-success mx-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                            </div>
                            <h3 class="h4 fw-bold text-center mb-4">For Teachers</h3>
                            <ul class="feature-list list-unstyled">
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Manage classes and assignments efficiently</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Grade student submissions digitally</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Track student attendance and performance</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Create and share educational resources</span>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Communicate with students and parents</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Parent Feature -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="role-icon bg-warning bg-opacity-10 text-warning mx-auto">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h3 class="h4 fw-bold text-center mb-4">For Parents</h3>
                            <ul class="feature-list list-unstyled">
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Monitor child's academic progress</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>View attendance and grade records</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Communicate with teachers directly</span>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Receive important notifications</span>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="fas fa-check text-success mt-1 me-3"></i>
                                    <span>Track assignment deadlines</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-3 bg-white">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">About Axia School Management System</h2>
                    <p class="lead text-muted mb-4">
                        Axia SMS is a comprehensive, cloud-based school management solution designed to streamline
                        educational processes and enhance communication between all stakeholders in the educational
                        ecosystem.
                    </p>
                    <p class="text-muted mb-4">
                        Our platform empowers schools to manage administrative tasks efficiently while providing students,
                        teachers, and parents with the tools they need to succeed in today's digital learning environment.
                    </p>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <span class="fw-medium">Secure & Reliable</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-mobile-alt text-primary me-2"></i>
                            <span class="fw-medium">Mobile Friendly</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-headset text-primary me-2"></i>
                            <span class="fw-medium">24/7 Support</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-primary text-white rounded-3 p-4 p-lg-5">
                        <h3 class="h2 fw-bold mb-4">Why Choose Axia?</h3>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-rocket text-warning mt-1 me-3 fs-5"></i>
                                <span>Easy to implement and use with minimal training required</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-cogs text-warning mt-1 me-3 fs-5"></i>
                                <span>Customizable to fit your school's unique needs and workflows</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-chart-line text-warning mt-1 me-3 fs-5"></i>
                                <span>Scalable solution that grows with your institution</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="fas fa-lock text-warning mt-1 me-3 fs-5"></i>
                                <span>Enterprise-grade security and data protection</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Start Your Educational Transformation Today</h2>
                    <p class="lead mb-5 opacity-90">
                        Join the growing community of schools that trust Axia for their management needs and experience the
                        difference.
                    </p>
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-light btn-lg px-5">
                            <i class="fas fa-arrow-right me-2"></i>Access Your Dashboard
                        </a>
                    @else
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">
                                Sign In
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>


    <footer class="bg-dark text-white pt-5">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <span class="text-white fw-bold">A</span>
                        </div>
                        <span class="fs-4 fw-bold">Axia SMS</span>
                    </div>
                    <p class="text-white-50 mb-4">
                        Transforming education through innovative technology solutions.
                    </p>

                    <!-- Social Links -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Follow Us</h6>
                        <div class="d-flex gap-3">
                            <a href="https://facebook.com" target="_blank"
                                class="text-white-50 hover-text-white text-decoration-none">
                                <i class="fab fa-facebook-f fa-lg"></i>
                            </a>
                            <a href="https://twitter.com" target="_blank"
                                class="text-white-50 hover-text-white text-decoration-none">
                                <i class="fab fa-twitter fa-lg"></i>
                            </a>
                            <a href="https://linkedin.com" target="_blank"
                                class="text-white-50 hover-text-white text-decoration-none">
                                <i class="fab fa-linkedin-in fa-lg"></i>
                            </a>
                            <a href="https://instagram.com" target="_blank"
                                class="text-white-50 hover-text-white text-decoration-none">
                                <i class="fab fa-instagram fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="fw-semibold mb-3">Product</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features"
                                class="text-white-50 text-decoration-none hover-text-white">Features</a></li>
                        <li class="mb-2"><a href="#about"
                                class="text-white-50 text-decoration-none hover-text-white">About</a></li>
                        <li class="mb-2"><a href="#pricing"
                                class="text-white-50 text-decoration-none hover-text-white">Pricing</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="fw-semibold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#help"
                                class="text-white-50 text-decoration-none hover-text-white">Help Center</a></li>
                        <li class="mb-2"><a href="#docs"
                                class="text-white-50 text-decoration-none hover-text-white">Documentation</a></li>
                        <li class="mb-2"><a href="#contact"
                                class="text-white-50 text-decoration-none hover-text-white">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="fw-semibold mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#privacy"
                                class="text-white-50 text-decoration-none hover-text-white">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#terms"
                                class="text-white-50 text-decoration-none hover-text-white">Terms of Service</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="fw-semibold mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#about"
                                class="text-white-50 text-decoration-none hover-text-white">About Us</a></li>
                        <li class="mb-2"><a href="#careers"
                                class="text-white-50 text-decoration-none hover-text-white">Careers</a></li>
                        <li class="mb-2"><a href="#blog"
                                class="text-white-50 text-decoration-none hover-text-white">Blog</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 border-secondary">

            <div class="text-center">
                <p class="text-white-50 mb-0">&copy; 2025 Axia School Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>


@endsection
