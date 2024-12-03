<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

require 'db.php';

try {
  $stmt = $pdo->query("SELECT * FROM books");
  $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching books: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Books</title>
  <!-- TailwindCSS -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
    rel="stylesheet" />
</head>

<body class="bg-gray-200 min-h-screen">
  <!-- Top Bar -->
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

  <!-- Main Container -->
  <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg p-8 space-y-8 shadow-md">
      <!-- Header -->
      <div class="flex justify-between">
        <h1 class="text-2xl font-bold text-gray-700">Book Listings</h1>
        <a
          href="add-book.php"
          class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block">
          Add new Book
        </a>
      </div>
      <!-- Book Grid -->
      <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <?php if (!empty($books)): ?>
          <?php foreach ($books as $book): ?>
            <!-- Book Card -->
            <div
              class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
              <img
                src="https://via.placeholder.com/150"
                alt="Cover"
                class="h-40 w-full object-cover rounded mb-4" />
              <h2 class="text-lg font-semibold text-gray-800">
                <?= htmlspecialchars($book['title']) ?>
              </h2>
              <p class="text-sm text-gray-500">
                <?= htmlspecialchars($book['author']) ?>
              </p>
              <a
                href="add-review.php?book_id=<?= $book['id'] ?>"
                class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block mr-2">
                Write Review
              </a>

              <a
                href="book.php?book_id=<?= $book['id'] ?>"
                class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block">
                view
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center text-gray-500">No books available.</p>
        <?php endif; ?>
      </div>
      <!-- Book Card -->
    </div>
    </div>
  </main>
</body>

</html>