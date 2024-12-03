<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require 'db.php';

// Get the book ID from the URL
$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;

if (!$book_id) {
  die("Book ID is missing.");
}

// Fetch the book details to pre-fill the form
$book_query = "
    SELECT id, title, author, description, published_date 
    FROM books 
    WHERE id = :book_id
";
$stmt = $pdo->prepare($book_query);
$stmt->execute(['book_id' => $book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
  die("Book not found.");
}

// Handle form submission for updating book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $author = trim($_POST['author']);
  $description = trim($_POST['description']);
  $published_date = $_POST['published_date'];

  // Basic validation
  if (empty($title) || empty($author) || empty($description) || empty($published_date)) {
    $error = "All fields are required.";
  } else {
    // Update the book details in the database
    $update_query = "
            UPDATE books
            SET title = :title, author = :author, description = :description, published_date = :published_date
            WHERE id = :book_id
        ";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute([
      'title' => $title,
      'author' => $author,
      'description' => $description,
      'published_date' => $published_date,
      'book_id' => $book_id
    ]);

    // Redirect to the updated book page (or any other desired page)
    header("Location: book.php?book_id=" . $book_id);
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Book</title>
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
        <h2 class="text-center text-2xl font-bold text-indigo-800">Edit Book</h2>

        <!-- Display Error Message -->
        <?php if (isset($error)): ?>
          <div class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Edit Book Form -->
        <form action="edit-book.php?book_id=<?= $book_id ?>" method="POST" class="space-y-6">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Book Title</label>
            <div class="mt-1">
              <input
                id="title"
                name="title"
                type="text"
                value="<?= htmlspecialchars($book['title']) ?>"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                placeholder="Book Title" />
            </div>
          </div>
          <div>
            <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
            <div class="mt-1">
              <input
                id="author"
                name="author"
                type="text"
                value="<?= htmlspecialchars($book['author']) ?>"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                placeholder="Author" />
            </div>
          </div>
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <div class="mt-1">
              <textarea
                id="description"
                name="description"
                rows="4"
                required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"><?= htmlspecialchars($book['description']) ?></textarea>
            </div>
          </div>
          <div>
            <label for="published_date" class="block text-sm font-medium text-gray-700">Publish Date</label>
            <input
              type="date"
              id="published_date"
              name="published_date"
              value="<?= htmlspecialchars($book['published_date']) ?>"
              required
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" />
          </div>
          <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600">Update Book Details</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>