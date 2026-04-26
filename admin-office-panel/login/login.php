<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - FCARR</title>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: Arial, sans-serif;
  overflow-x: hidden;
}

/* ================= MAIN WRAPPER ================= */
#login-page {
  display: flex;
  min-height: 100vh;
}

/* ================= LOGIN SIDE ================= */
.login {
  width: 35%;
  background: #fff;

  display: flex;
  justify-content: center;
  align-items: center;

  padding: 20px;
}

/* LOGIN CARD */
.login-card {
  width: 100%;
  max-width: 360px;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  z-index: 5;
}

.login-title {
  text-align: center;
  margin-bottom: 5px;
}

.notice {
  text-align: center;
  font-size: 13px;
  color: #666;
  margin-bottom: 20px;
}

label {
  font-size: 13px;
  margin-top: 12px;
  display: block;
}

input {
  width: 100%;
  padding: 12px;
  margin-top: 5px;
  border: 1px solid #ddd;
  border-radius: 8px;
  outline: none;
}

button {
  width: 100%;
  margin-top: 20px;
  padding: 12px;
  border: none;
  border-radius: 8px;
  background: #0a1a3a;
  color: white;
  cursor: pointer;
}

/* ================= BACKGROUND SIDE (INDEX STYLE) ================= */
.background {
  width: 65%;
  position: relative;
  overflow: hidden;
}

/* IMAGE */
.background::before {
  content: "";
  position: absolute;
  inset: 0;
  background: url('/assets/faithbg.png') center/cover no-repeat;
  z-index: 1;
}

/* BLUE GLASSMORPHISM OVERLAY */
.background::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    135deg,
    rgba(10, 25, 60, 0.85),
    rgba(0, 0, 0, 0.55)
  );

  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);

  z-index: 2;
}

/* TEXT ON BACKGROUND */
.bg-content {
  position: relative;
  z-index: 3;

  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;

  padding: 60px;
  color: #fff;
}

.bg-content h1 {
  font-size: 38px;
  margin-bottom: 10px;
}

.bg-content p {
  font-size: 16px;
  opacity: 0.9;
}

/* ================= MOBILE FIX (KEYBOARD SAFE CENTERING) ================= */
@media (max-width: 768px) {

  #login-page {
    flex-direction: column;
    min-height: 100dvh; /* FIX: prevents keyboard pushing layout */
  }

  /* IMAGE ON TOP */
  .background {
    width: 100%;
    height: 35vh;
  }

  /* LOGIN ALWAYS CENTERED */
  .login {
    width: 100%;
    flex: 1;

    display: flex;
    justify-content: center;
    align-items: center;

    padding: 20px;
  }

  .login-card {
    width: 92%;
    max-width: 360px;

    /* keeps it stable even when keyboard opens */
    margin: auto;
  }

  .bg-content {
    padding: 20px;
    text-align: center;
  }

  .bg-content h1 {
    font-size: 22px;
  }
}

</style>

</head>
<body>

<div id="login-page">

  <!-- LOGIN -->
  <div class="login">
    <div class="login-card">

      <h2 class="login-title">Login</h2>
      <p class="notice">Please login to access the system</p>

      <form>
        <label>Email</label>
        <input type="email" required>

        <label>Password</label>
        <input type="password" required>

        <button type="submit">Sign in</button>
      </form>

    </div>
  </div>

  <!-- BACKGROUND -->
  <div class="background">
    <div class="bg-content">
      <h1>Faith Christian Academy</h1>
      <p>Empowering Minds, Building Futures</p>
    </div>
  </div>

</div>

</body>
</html>