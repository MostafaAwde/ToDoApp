<?php
// public/views/sign-up.php
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="card-title mb-4 text-center">Sign Up</h2>
        <form method="POST" action="signup">
          <div class="mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="passwordConfirmation">Confirm Password</label>
            <input type="password" name="passwordConfirmation" id="passwordConfirmation" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-success w-100 mt-4">Sign Up</button>
        </form>

        <p class="text-center mt-3">
          Already have an account?
          <a href="/login">Log In</a>
        </p>
      </div>
    </div>
  </div>
</div>