<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm-password'];

  if ($password !== $confirm_password) {
    $error = "Passwords do not match.";
  }

  if (!$error && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  }

  if (!$error) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->execute([$username, $email, $hashed_password]);
      header("Location: login.php");
      exit();
    } catch (PDOException $e) {
      if ($e->errorInfo[1] == 1062) {
        if (str_contains($e->getMessage(), 'username')) {
          $error = "Username already exists.";
        } elseif (str_contains($e->getMessage(), 'email')) {
          $error = "Email already exists.";
        } else {
          $error = "Duplicate entry found.";
        }
      } else {
        $error = "An unexpected error occurred. Please try again later.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign up</title>
  <!-- Tailwind -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
    rel="stylesheet" />
</head>

<body>
  <div
    class="flex flex-col justify-center font-[sans-serif] sm:h-screen p-4 bg-gray-100">
    <div class="max-w-md w-full mx-auto shadow-lg rounded-2xl p-8">
      <div class="text-center mb-4">
        <h1 class="text-3xl font-bold text-gray-700">Sign up</h1>
      </div>

      <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4">
          <strong class="font-bold">Error!</strong>
          <span class="block sm:inline"><?php echo $error; ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4">
          <strong class="font-bold">Success!</strong>
          <span class="block sm:inline"><?php echo $success; ?></span>
        </div>
      <?php endif; ?>

      <!-- Inside your existing HTML -->
      <form method="POST" action="signup.php">
        <div class="space-y-2">
          <div>
            <label class="text-gray-800 text-sm mb-2 block">Username</label>
            <input
              name="username"
              type="text"
              class="text-gray-800 bg-white border border-indigo-300 w-full text-sm px-4 py-3 rounded-md outline-indigo-500"
              placeholder="Choose a username"
              required />
          </div>
          <div>
            <label class="text-gray-800 text-sm mb-2 block">Email</label>
            <input
              name="email"
              type="email"
              class="text-gray-800 bg-white border border-indigo-300 w-full text-sm px-4 py-3 rounded-md outline-indigo-500"
              placeholder="Enter email"
              required />
          </div>
          <div>
            <label class="text-gray-800 text-sm mb-2 block">Password</label>
            <input
              name="password"
              type="password"
              class="text-gray-800 bg-white border border-indigo-300 w-full text-sm px-4 py-3 rounded-md outline-indigo-500"
              placeholder="Enter password"
              required />
          </div>
          <div>
            <label class="text-gray-800 text-sm mb-2 block">Confirm Password</label>
            <input
              name="confirm-password"
              type="password"
              class="text-gray-800 bg-white border border-indigo-300 w-full text-sm px-4 py-3 rounded-md outline-indigo-500"
              placeholder="Enter password again"
              required />
          </div>
        </div>

        <div class="mt-4">
          <button
            type="submit"
            class="w-full py-3 px-4 text-sm tracking-wider font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
            Create an account
          </button>
        </div>
        <p class="text-gray-800 text-sm mt-6 text-center">
          Already have an account?
          <a
            href="login.php"
            class="text-indigo-600 font-semibold hover:underline ml-1">Login here</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>