<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $book_id = $_POST['book_id'];
  $rating = $_POST['rating'];
  $review = $_POST['description'];

  try {
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, book_id, rating, review) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $book_id, $rating, $review])) {
      header("Location: books.php");
      exit;
    } else {
      echo "Error adding review. Please try again.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Review</title>
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
      <div class="w-full space-y-8 bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-center text-2xl font-bold">Write a Review</h2>
        <form class="space-y-6" action="add-review.php" method="post">
          <div>
            <input type="number" value="<?php echo $_GET['book_id'] ?>" name="book_id" hidden>

            <label class="text-gray-800 text-sm mb-2 block">Rate the book</label>
            <input
              name="rating"
              type="number"
              min="1"
              max="5"
              class="text-gray-800 bg-white border border-indigo-300 w-full text-sm px-4 py-3 rounded-md focus:outline-none"
              placeholder="from 1 to 5"
              required />
          </div>
          <div>
            <label
              for="description"
              class="block text-sm font-medium text-gray-700">
              Write about your experience with this book
            </label>
            <div class="mt-1">
              <textarea
                id="description"
                name="description"
                rows="4"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-indigo-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"></textarea>
            </div>
          </div>
          <button
            type="submit"
            class="w-full bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600">
            Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>