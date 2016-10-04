<?php

require 'classes/database.php';
require 'classes/tags.php';

$database = new Database;

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

if(@$_POST['delete']){
	$delete_id = $_POST['delete_id'];
	$database->query('DELETE FROM blog_posts WHERE id = :id');
	$database->bind(':id', $delete_id);
	$database->execute();
}

if(@$post['update']){
	$id = $post['id'];
	$title = $post['title'];
	$post = $post['post'];

		$database->query('UPDATE blog_posts SET title = :title, post = :post WHERE id = :id');
		$database->bind(':title', $title);
		$database->bind(':post', $post);
		$database->bind(':id', $id);
		$database->execute();
}

if(@$post['submit']){
  $title = $post['title'];
  $post = $post['post'];

		$database->query('INSERT INTO blog_posts (title, post) VALUES(:title, :post)');
		$database->bind(':title', $title);
		$database->bind(':post', $post);
		$database->execute();
		if($database->lastInsertId()){
		  echo '<p>Post Added!</p>';
		}
}

$Tags = new Tags();
$Tags->query('SELECT * FROM blog_posts');
$rows = $Tags->resultset();
?>

<h1>Add Post</h1>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

	<!-- <label>Post ID</label><br />
	<input type="text" name="id" placeholder="Specify ID" /><br /><br /> -->

	<label>Post Title</label><br />
	<input type="text" name="title" placeholder="Add a Title..." /><br /><br />

	<label>Post Body</label><br />
	<textarea name="post"></textarea><br /><br />

	<label>Post Tags</label><br/ >
	<input type="text" name="tags" placeholder="Tags" />
	<br />
	<br />
	<input type="submit" name="submit" value="Submit" />
</form>
<html>
<head>
	<title>Blog</title>
	<link rel="stylesheet" type="text/css" href="classes/styleSheet.css">
</head>
<body>
 <h1>Posts</h1>
 <div>
 <?php foreach($rows as $row) : ?>
 		<h3><?php echo $row['title']; ?></h3>
 			<p><?php echo $row['post']; ?></p>
			<p>Tags: <?php echo $row['tags']; ?></p>
 			<br />

 		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
 			<input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
 			<input type="submit" name="delete" value="Delete" />

 		</form>
 	</div>
 <?php endforeach; ?>
 </div>
 </body>
</html>
