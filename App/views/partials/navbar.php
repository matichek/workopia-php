<?php use Framework\Session; ?>
<!-- Nav -->
<header class="bg-blue-800 text-white p-4 shadow-lg">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-3xl font-bold">
      <a href="/" class="flex items-center">
        <i class="fas fa-briefcase mr-2"></i>
        <span class="hover:text-yellow-400 transition duration-300">Workopia</span>
      </a>
    </h1>
    <nav class="flex items-center gap-2">
      <?php if (Session::has('user')): ?>
        <div class="flex items-center">
          <i class="fas fa-user-circle text-xl"></i>
          <p class="text-white mr-4">Hello, <?php echo Session::get('user')['name']; ?></p>
        </div>
        <div class="flex justify-between items-center gap-4">
          <form>
            <button type="submit"
              class="text-white hover:text-yellow-400 transition duration-300 flex items-center mr-2"></button>
          </form>
        </div>


        <a href="/listings/create"
          class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-full hover:shadow-md transition duration-300 flex items-center">
          <i class="fas fa-plus-circle mr-1"></i> Post a Job
        </a>
      <?php else: ?>
        <a href="/auth/login" class="text-white hover:text-yellow-400 transition duration-300 flex items-center">
          <i class="fas fa-sign-in-alt mr-1"></i> Login
        </a>
        <a href="/auth/register" class="text-white hover:text-yellow-400 transition duration-300 flex items-center">
          <i class="fas fa-user-plus mr-1"></i> Register
        </a>
      <?php endif; ?>



    </nav>
  </div>
</header>