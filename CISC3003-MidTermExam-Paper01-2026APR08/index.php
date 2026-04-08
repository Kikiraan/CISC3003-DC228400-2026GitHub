<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dc228400 Wang Yufeng</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="page">
        <section class="container" id="container">
            <div class="form-container sign-up-container">
                <form action="#" method="post">
                    <h1>Join Us</h1>
                    <div class="social-container">
                        <a href="#" class="social" aria-label="Facebook">f</a>
                        <a href="#" class="social" aria-label="Google">G+</a>
                        <a href="#" class="social" aria-label="LinkedIn">in</a>
                    </div>
                    <span>Use your email to sign up</span>
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <input type="email" name="signup_email" placeholder="Email" required>
                    <input type="password" name="signup_password" placeholder="Create Password" required>
                    <button type="submit">REGISTER</button>
                </form>
            </div>

            <div class="form-container sign-in-container">
                <form action="#" method="post">
                    <h1>Log In</h1>
                    <div class="social-container">
                        <a href="#" class="social" aria-label="Facebook">f</a>
                        <a href="#" class="social" aria-label="Google">G+</a>
                        <a href="#" class="social" aria-label="LinkedIn">in</a>
                    </div>
                    <span>Use your account to sign in</span>
                    <input type="email" name="signin_email" placeholder="Email" required>
                    <input type="password" name="signin_password" placeholder="Password" required>
                    <a href="#" class="forgot">Forgot Password?</a>
                    <button type="submit">SIGN IN</button>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Hello, Again!</h1>
                        <img src="images/website_7376495.png" alt="Website icon">
                        <p>Log in to stay connected with us</p>
                        <button class="ghost" id="signIn" type="button">SIGN IN</button>
                    </div>

                    <div class="overlay-panel overlay-right">
                        <h1>Welcome!</h1>
                        <img src="images/unsecure_10399884.png" alt="Secure email icon">
                        <p>Enter your details to start your journey</p>
                        <button class="ghost" id="signUp" type="button">SIGN UP</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        CISC3003 Web Programming: dc228400 Wang Yufeng 2026
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
