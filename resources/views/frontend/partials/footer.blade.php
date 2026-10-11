<footer>

    <div class="container">

        <div class="footer-columns">


            {{-- Company --}}

            <div class="footer-col">

                <a
                    href=""
                    class="brand-logo">

                    <div class="logo-text">

                        <img
                            src="{{ asset('public/frontend/images/logo-color.png') }}"
                            alt="MSG Group">

                    </div>

                </a>


                <p>
                    A comprehensive property development,
                    project management and asset optimization
                    platform for modern organizations.
                </p>


                <div class="social-links">

                    <a href="#">
                        <i class="ri-linkedin-fill"></i>
                    </a>

                    <a href="#">
                        <i class="ri-facebook-fill"></i>
                    </a>

                    <a href="#">
                        <i class="ri-youtube-fill"></i>
                    </a>

                    <a href="#">
                        <i class="ri-instagram-line"></i>
                    </a>

                </div>

            </div>


            {{-- Quick Links --}}

            <div class="footer-col">

                <h5>
                    Quick Links
                </h5>

                <ul class="footer-links-list">

                    <li>
                        <a href="">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="#about">
                            About Us
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Solutions
                        </a>
                    </li>

                    <li>
                        <a href="#pricing">
                            Pricing
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            News
                        </a>
                    </li>

                    <li>
                        <a href="#contact">
                            Contact
                        </a>
                    </li>

                </ul>

            </div>


            {{-- Solutions --}}

            <div class="footer-col">

                <h5>
                    Solutions
                </h5>

                <ul class="footer-links-list">

                    <li>
                        <a href="#features">
                            Land &amp; Feasibility
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Design Management
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Project Management
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Construction Management
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Contract Management
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Asset Management
                        </a>
                    </li>

                </ul>

            </div>


            {{-- Support --}}

            <div class="footer-col">

                <h5>
                    Support
                </h5>

                <ul class="footer-links-list">

                    <li>
                        <a href="#">
                            Help Center
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Documentation
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            API Services
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Security
                        </a>
                    </li>

                </ul>

            </div>


            {{-- Newsletter --}}

            <div class="footer-col">

                <h5>
                    Subscribe
                </h5>

                <p>
                    Get the latest updates and news directly.
                </p>


                <form
                    action="#"
                    method="POST"
                    class="newsletter-form">

                    @csrf

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email">

                    <button type="submit">

                        <i class="ri-arrow-right-line"></i>

                    </button>

                </form>

            </div>


        </div>


        <div class="footer-bottom-bar">

            <div>
                © {{ date('Y') }} MSG Group.
                All rights reserved.
            </div>


            <div class="footer-legal">

                <a href="#">
                    Privacy Policy
                </a>

                <a href="#">
                    Terms of Service
                </a>

            </div>

        </div>

    </div>

</footer>