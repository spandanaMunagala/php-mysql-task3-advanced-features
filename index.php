<?php
include 'config.php';

/* ---------- SEARCH ---------- */
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

/* ---------- PAGINATION ---------- */
$limit = 3; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

/* ---------- FETCH POSTS ---------- */
$query = "SELECT * FROM posts
          WHERE title LIKE '%$search%'
          OR content LIKE '%$search%'
          ORDER BY created_at DESC
          LIMIT $start, $limit";

$result = mysqli_query($conn, $query);

/* ---------- TOTAL COUNT ---------- */
$totalQuery = "SELECT COUNT(*) as total FROM posts
               WHERE title LIKE '%$search%'
               OR content LIKE '%$search%'";

$totalResult = mysqli_query($conn, $totalQuery);
$totalPosts = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalPosts / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task 3 - Advanced PHP Features</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>📄 Blog Posts</h1>

<!-- SEARCH FORM -->
<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search posts..."
           value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<!-- POSTS -->
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='post'>";
        echo "<h3>".$row['title']."</h3>";
        echo "<p>".$row['content']."</p>";
        echo "</div>";
    }
} else {
    echo "<p class='no-data'>No posts found</p>";
}
?>

<!-- PAGINATION -->
<div class="pagination">
<?php
for ($i = 1; $i <= $totalPages; $i++) {
    $active = ($i == $page) ? "active" : "";
    echo "<a class='$active' href='?page=$i&search=$search'>$i</a>";
}
?>
</div>

</body>
</html>
