<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require 'db.php';

$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;

if (!$book_id) {
  die("Book ID is missing.");
}

$book_query = "SELECT id, title, author, description FROM books WHERE id = :book_id";
$stmt = $pdo->prepare($book_query);
$stmt->execute(['book_id' => $book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
  die("Book not found.");
}

$reviews_query = "SELECT reviews.id, reviews.review, reviews.rating, users.username AS reviewer_name
                  FROM reviews
                  JOIN users ON reviews.user_id = users.id
                  WHERE reviews.book_id = :book_id";
$stmt = $pdo->prepare($reviews_query);
$stmt->execute(['book_id' => $book_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Details</title>
  <!-- TailwindCSS -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
    rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen">
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

  <!-- Container -->
  <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Book Details Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <div class="flex flex-col lg:flex-row lg:space-x-8 mb-8">
        <div class="space-y-4">
          <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($book['title']) ?></h1>
          <p class="text-lg text-gray-600">by <span class="font-medium"><?= htmlspecialchars($book['author']) ?></span></p>
          <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($book['description']) ?></p>
        </div>
      </div>
      <a href="add-review.php?book_id=<?= $book['id'] ?>" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition mr-4">
        Write a Review
      </a>
      <a href="edit-book.php?book_id=<?= $book['id'] ?>" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition">
        Edit book details
      </a>
    </div>

    <!-- Reviews Section -->
    <div>
      <h2 class="text-2xl font-bold text-gray-700 mb-4">Reviews</h2>
      <div class="space-y-6">
        <?php foreach ($reviews as $review): ?>
          <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($review['reviewer_name']) ?></h3>
              <span class="text-sm text-gray-500"><?= htmlspecialchars($review['rating']) ?>/5</span>
            </div>
            <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($review['review']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>

</html>