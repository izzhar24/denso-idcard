<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="container mt-5 card px-4 py-4 shadow-lg" style="max-width: 400px;">
            <h3 class="text-center mb-4">Login</h3>
            <form action="/login" method="POST">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter email"
                        required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <a href="/" class="text-xs">Back to Home Page</a>
            </form>
        </div>
    </div>
</section>