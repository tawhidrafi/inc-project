<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

require 'db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $description = $_POST['description'];
  $published_date = $_POST['publish_date'];

  try {
    $stmt = $pdo->prepare("INSERT INTO books (title, author, description, published_date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$title, $author, $description, $published_date])) {
      $success = "Successful";
    } else {
      $error = "Please try again.";
    }
  } catch (PDOException $e) {
    $error = "An error occurred. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add book</title>
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
        <h2 class="text-center text-2xl font-bold text-indigo-800">
          Add a New Book
        </h2>

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

        <form class="space-y-6" method="POST" action="add-book.php">
          <div>
            <label
              for="title"
              class="block text-sm font-medium text-gray-700">
              Book Title
            </label>
            <div class="mt-1">
              <input
                id="title"
                name="title"
                type="text"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                placeholder="Book Title" />
            </div>
          </div>
          <div>
            <label
              for="author"
              class="block text-sm font-medium text-gray-700">
              Author
            </label>
            <div class="mt-1">
              <input
                id="author"
                name="author"
                type="text"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                placeholder="Author" />
            </div>
          </div>
          <div>
            <label
              for="description"
              class="block text-sm font-medium text-gray-700">
              Description
            </label>
            <div class="mt-1">
              <textarea
                id="description"
                name="description"
                rows="4"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"></textarea>
            </div>
          </div>
          <div>
            <label
              for="publish_date"
              class="block text-sm font-medium text-gray-700">Publish Date</label>
            <input
              type="date"
              id="publish_date"
              name="publish_date"
              required
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" />
          </div>
          <button
            type="submit"
            class="w-full bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600">
            Add Book
          </button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>