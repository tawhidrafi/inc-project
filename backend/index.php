<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require 'db.php';

$reviews_query = "SELECT reviews.id, reviews.review, reviews.rating, books.title AS book_title, users.username AS reviewer_name
                  FROM reviews
                  JOIN books ON reviews.book_id = books.id
                  JOIN users ON reviews.user_id = users.id";

try {
  $reviews = $pdo->query($reviews_query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching reviews: " . $e->getMessage());
}

$books_query = "SELECT id, title, author, description FROM books";

try {
  $books = $pdo->query($books_query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching books: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <meta name="author" content="David Grzyb" />
  <meta name="description" content="" />
  <!-- Tailwind -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
    rel="stylesheet" />
</head>

<body>
  <div class="min-h-screen bg-gray-200">
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
    <div class="max-w-7xl mx-auto px-4 pb-8 sm:px-6 lg:px-8 mt-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recently Added Reviews -->
        <div class="lg:col-span-2 space-y-6">
          <h2 class="text-2xl font-bold">Recently Added Reviews</h2>
          <?php foreach ($reviews as $review): ?>
            <div class="bg-white shadow-md p-6 rounded-lg border-l-4 border-purple-400">
              <h3 class="text-xl font-semibold text-blue-800"><?= htmlspecialchars($review['book_title']) ?></h3>
              <p class="text-sm text-gray-500">Reviewed by <?= htmlspecialchars($review['reviewer_name']) ?></p>
              <p class="text-gray-700 mt-3"><?= htmlspecialchars(substr($review['review'], 0, 100)) ?>...</p>
              <button class="mt-4 bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                <a href="review.php?review_id=<?= $review['id'] ?>">Read More</a>
              </button>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Recently Added Books -->
        <aside class="space-y-6">
          <h2 class="text-2xl font-bold">Recently Added Books</h2>
          <?php foreach ($books as $book): ?>
            <div class="bg-white shadow-md p-4 rounded-lg flex border-l-4 border-blue-400">
              <div>
                <h3 class="text-lg font-semibold text-blue-800"><?= htmlspecialchars($book['title']) ?></h3>
                <p class="text-sm text-gray-500">by <?= htmlspecialchars($book['author']) ?></p>
                <p class="text-gray-600 mt-2 text-sm"><?= htmlspecialchars(substr($book['description'], 0, 80)) ?>...</p>
                <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                  <a href="book.php?book_id=<?= $book['id'] ?>">View Book</a>
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </aside>
      </div>
    </div>
  </div>
</body>

</html>