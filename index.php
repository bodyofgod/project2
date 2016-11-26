<?php
try {
    //create connection
    $conn = new PDO ( "sqlsrv:server = tcp:projectitcs443.database.windows.net,1433; Database = Project", "u5788135", "Zhang0077");
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    // create table if it does not exist
    $sql = "IF OBJECT_ID('posts', 'U') IS NULL 
  CREATE TABLE posts (
      id INT NOT NULL IDENTITY(1,1) PRIMARY KEY(id),
      message TEXT NOT NULL,
      author VARCHAR(64) NOT NULL,
      timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  );";
    $conn->query($sql);

} catch ( PDOException $e ) {

    print( "Error connecting to SQL Server." );
    die(print_r($e));

}

//check if user is posted
if(isset($_POST['message']) && isset($_POST['author'])){
    //insert new post into database
    $sql = "INSERT INTO posts(message, author) VALUES (?,?);";
    $stmt = $conn->prepare($sql);
    $stmt->execute( array($_POST['message'], $_POST['author']) );
}

//get all posts
$sql = "SELECT * FROM posts ORDER BY timestamp DESC;";
$result = $conn->query($sql);
$posts = $result->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Posting Board</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
<!-- navbar -->
<nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">My Posting Board</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<!-- newpost -->
<div class="container newpost">
    <h3>Leave a post</h3>

    <form class="form-inline" method="POST" action="">
        <div class="form-group">
            <label for="message">Message: </label>
            <input name="message" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="author">Author: </label>
            <input name="author" type="text" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>
</div><!-- /.newpost -->

<!-- posts -->
<div class="container posts">
    <h3>Previous posts</h3>

    <?php foreach ($posts as $post): ?>

        <div class="panel panel-info post">
            <div class="panel-heading">
                <h4 class="panel-title">Message <?php echo $post['id'];?></h4>
            </div>
            <div class="panel-body">
                <p><?php echo htmlspecialchars($post['message']);?></p>
                By <?php echo htmlspecialchars($post['author']);?>
                <i><?php echo $post['timestamp'];?></i>
            </div>
        </div><!-- /.post -->

    <?php endforeach; ?>

</div><!-- /.posts -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>