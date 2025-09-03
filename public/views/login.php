<?php
// public/views/login.php
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="card-title mb-4 text-center">Log In</h2>
        <form method="POST" action="/login" novalidate>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              name="email" 
              required
            >
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              required
            >
          </div>
          <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>
        <p class="text-center mt-3">
          Don&rsquo;t have an account? 
          <a href="/signup">Sign Up</a>
        </p>
      </div>
    </div>
  </div>
</div>
