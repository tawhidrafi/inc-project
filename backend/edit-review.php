<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require 'db.php';

// Get the review ID from the URL
$review_id = isset($_GET['review_id']) ? $_GET['review_id'] : null;

if (!$review_id) {
  die("Review ID is missing.");
}

// Fetch the review details to pre-fill the form
$review_query = "
    SELECT id, rating, review
    FROM reviews
    WHERE id = :review_id
";
$stmt = $pdo->prepare($review_query);
$stmt->execute(['review_id' => $review_id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
  die("Review not found.");
}

// Handle form submission for updating the review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rating = $_POST['rating'];
  $review = trim($_POST['review']);

  // Basic validation
  if (empty($rating) || empty($review)) {
    $error = "All fields are required.";
  } elseif ($rating < 1 || $rating > 5) {
    $error = "Rating must be between 1 and 5.";
  } else {
    // Update the review in the database
    $update_query = "
            UPDATE reviews
            SET rating = :rating, review = :review
            WHERE id = :review_id
        ";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute([
      'rating' => $rating,
      'review' => $review,
      'review_id' => $review_id
    ]);

    // Redirect to the updated review page (or any other desired page)
    header("Location: review.php?review_id=" . $review_id);
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Review</title>
  <meta name="author" content="David Grzyb" />
  <meta name="description" content="" />
  <!-- TailwindCSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
  <!-- AlpineJS -->
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
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

    <div class="max-w-7xl mx-auto px-4 pb-8 sm:px-6 lg:px-8 mt-8">
      <div class="w-full space-y-8 bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-center text-2xl font-bold">Edit Review</h2>

        <!-- Display Error Message -->
        <?php if (isset($error)): ?>
          <div class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Edit Review Form -->
        <form action="edit-review.php?review_id=<?= $review_id ?>" method="POST" class="space-y-6">
          <div>
            <label class="text-gray-800 text-sm mb-2 block">Rate the book</label>
            <input
              name="rating"
              type="number"
              min="1"
              max="5"
              value="<?= htmlspecialchars($review['rating']) ?>"
              class="text-gray-800 bg-white border border-indigo-300 w-full text-sm px-4 py-3 rounded-md focus:outline-none"
              placeholder="from 1 to 5"
              required />
          </div>
          <div>
            <label for="review" class="block text-sm font-medium text-gray-700">Write about your experience with this book</label>
            <div class="mt-1">
              <textarea
                id="review"
                name="review"
                rows="4"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-indigo-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"><?= htmlspecialchars($review['review']) ?></textarea>
            </div>
          </div>
          <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600">Update Review</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>