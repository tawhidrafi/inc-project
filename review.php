<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require 'db.php';

$review_id = isset($_GET['review_id']) ? $_GET['review_id'] : null;

if (!$review_id) {
  die("Review ID is missing.");
}

$review_query = "
    SELECT reviews.id, reviews.review, reviews.rating, books.title AS book_title, books.author AS book_author, users.username AS reviewer_name, users.email AS reviewer_email 
    FROM reviews
    JOIN books ON reviews.book_id = books.id
    JOIN users ON reviews.user_id = users.id
    WHERE reviews.id = :review_id
";
$stmt = $pdo->prepare($review_query);
$stmt->execute(['review_id' => $review_id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
  die("Review not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Single Review</title>
  <meta name="author" content="David Grzyb" />
  <meta name="description" content="" />
  <!-- TailwindCSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>

<body>
  <div class="min-h-screen bg-gray-100">
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Full Review Section -->
        <section class="bg-white shadow-lg rounded-lg p-6">
          <h1 class="text-3xl font-bold">Review of "<?= htmlspecialchars($review['book_title']) ?>"</h1>
          <p class="text-sm text-gray-500 mt-2">Reviewed by <?= htmlspecialchars($review['reviewer_name']) ?></p>
          <p class="text-gray-700 mt-6 leading-relaxed"><?= nl2br(htmlspecialchars($review['review'])) ?></p>
          <a
            href="edit-review.php?review_id=<?= htmlspecialchars($review['id']) ?>"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block mt-8">
            Update
          </a>
        </section>
      </div>

      <!-- Sidebar -->
      <aside class="space-y-8">
        <!-- Book Information -->
        <section class="bg-white shadow-lg rounded-lg p-6">
          <h2 class="text-xl font-bold">About the Book</h2>
          <div class="mt-4 flex">
            <div>
              <h3 class="text-lg font-semibold"><?= htmlspecialchars($review['book_title']) ?></h3>
              <p class="text-sm">by <?= htmlspecialchars($review['book_author']) ?></p>
              <p class="mt-3 text-sm">A classic novel exploring the themes of love, wealth, and the American Dream...</p>
            </div>
          </div>
        </section>

        <!-- Reviewer Information -->
        <section class="bg-white shadow-lg rounded-lg p-6">
          <h2 class="text-xl font-bold">About the Reviewer</h2>
          <p class="mt-4"><?= htmlspecialchars($review['reviewer_name']) ?> </p>
          <p class="mt-4"><?= htmlspecialchars($review['reviewer_email']) ?> </p>
        </section>
      </aside>
    </div>
  </div>
</body>

</html>