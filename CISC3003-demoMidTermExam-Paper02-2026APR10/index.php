<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dc228400 Wang Yufeng</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="js/script.js" defer></script>
</head>
<body>
    <main class="page-shell">
        <section class="container" id="container">
            <div class="form-container sign-up-container">
                <form action="register.php" method="POST">
                    <h1>Join Us</h1>
                    <div class="social-container">
                        <a href="#" class="social" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social" aria-label="Google"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>Use your email to sign up</span>
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Create Password" required>
                    <button type="submit">Register</button>
                </form>
            </div>

            <div class="form-container sign-in-container">
                <form action="login.php" method="POST">
                    <h1>Log In</h1>
                    <div class="social-container">
                        <a href="#" class="social" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social" aria-label="Google"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>Use your account to sign in</span>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <a href="#">Forgot Password?</a>
                    <button type="submit">Sign In</button>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Hello, Again!</h1>
                        <img src="images/website_7376495.png" alt="User illustration">
                        <p>Log in to stay connected with us</p>
                        <button class="ghost" id="signIn" type="button">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Welcome!</h1>
                        <img src="images/unsecure_10399884.png" alt="Security illustration">
                        <p>Enter your details to start your journey</p>
                        <button class="ghost" id="signUp" type="button">Sign Up</button>
                    </div>
                </div>
            </div>
        </section>

        <footer class="page-footer">CISC3003 Web Programming: dc228400 Wang Yufeng 2026</footer>
    </main>
</body>
</html>
