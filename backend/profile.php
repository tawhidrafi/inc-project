<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require 'db.php';

$user_id = $_SESSION['user_id'];

$user_query = "SELECT username, email FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($user_query);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$reviews_query = " SELECT b.title,
r.review, r.rating FROM reviews r JOIN books b ON r.book_id = b.id WHERE
r.user_id = :user_id ";
$stmt = $pdo->prepare($reviews_query);
$stmt->execute(['user_id' => $user_id]);
$reviews =
  $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css"
    rel="stylesheet" />
  <!-- component -->
  <link
    rel="stylesheet"
    href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css" />
  <link
    rel="stylesheet"
    href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
  <!-- alpine -->
  <script
    defer
    src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <title>hey</title>
</head>

<body>
  <main class="profile-page">
    <header class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Main Menu -->
          <nav class="flex space-x-6">
            <a href="index.php" class="hover:text-yellow-300 font-medium">Home</a>
            <a href="books.php" class="hover:text-yellow-300 font-medium">Books</a>
            <a href="profile.php" class="hover:text-yellow-300 font-medium">Profile</a>
          </nav>
          <!-- User Menu -->
          <div class="flex items-center space-x-6">
            <span class="font-medium">Hello, <?= $_SESSION['username'] ?></span>
            <a
              href="logout.php"
              class="bg-yellow-400 text-purple-800 px-4 py-2 rounded hover:bg-yellow-300 font-medium">
              Logout
            </a>
          </div>
        </div>
      </div>
    </header>

    <section class="relative block h-500-px">
      <div
        class="absolute top-0 w-full h-full bg-center bg-cover"
        style="
            background-image: url('https://images.unsplash.com/photo-1499336315816-097655dcfbda?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=2710&amp;q=80');
          ">
        <span
          id="blackOverlay"
          class="w-full h-full absolute opacity-50 bg-black"></span>
      </div>
      <div
        class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px"
        style="transform: translateZ(0px)">
        <svg
          class="absolute bottom-0 overflow-hidden"
          xmlns="http://www.w3.org/2000/svg"
          preserveAspectRatio="none"
          version="1.1"
          viewBox="0 0 2560 100"
          x="0"
          y="0">
          <polygon
            class="text-blueGray-200 fill-current"
            points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </section>
    <section class="relative py-16 bg-blueGray-200">
      <div class="container mx-auto px-4">
        <div
          class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
          <div class="px-6">
            <div class="text-center mt-12">
              <h3
                class="text-4xl font-semibold leading-normal mb-2 text-blueGray-700 mb-2">
                <?= htmlspecialchars($user['username']) ?>
              </h3>
              <div
                class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                <i
                  class="fas fa-map-marker-alt mr-2 text-lg text-blueGray-400"></i>
                <?= htmlspecialchars($user['email']) ?>
              </div>
            </div>
            <!-- divider -->
            <div class="mt-10 py-4 border-t border-blueGray-200 text-center">
              <div class="flex flex-wrap justify-center"></div>
            </div>
            <!-- divider -->
            <div class="lg:col-span-2 space-y-6 mb-8">
              <h2 class="text-2xl font-bold">Recently Reviews</h2>

              <?php if (empty($reviews)): ?>
                <p class="text-center text-gray-500">No reviews yet.</p>
              <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                  <div
                    class="bg-white shadow-md p-6 rounded-lg border-l-4 border-purple-400 mb-4">
                    <h3 class="text-xl font-semibold text-blue-800">
                      <?= htmlspecialchars($review['title']) ?>
                    </h3>
                    <p class="text-gray-700 mt-3">
                      <?= htmlspecialchars($review['review']) ?>
                    </p>
                    <button
                      class="mt-4 bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                      Read More
                    </button>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</body>

</html>