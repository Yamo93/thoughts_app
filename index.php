<?php

/*
To implement update:

FINALLY DONE :D A COMPLETE CRUD APP

*/ 
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_NAME', 'thoughts');
define('DB_PASS', 'yamo123');

if (isset($_POST['edit_btn'])) {
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $thought = filter_input(INPUT_POST, 'thought', FILTER_SANITIZE_STRING);
    // $subject = $_POST['subject'];
    // $thought = $_POST['thought'];
    $update_id = $_POST['updated_id'];

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_errno) {
        printf("Connect failed %s\n", $mysqli->connect_error);
        exit();
    }

    $query = $mysqli->prepare("UPDATE posts SET 
    subject = ?, 
    content = ?, 
    created_at = now() 
    WHERE id = ?");
    $query->bind_param('ssd', $subject, $thought, $update_id);

    $query->execute();
    //$update_result = $mysqli->query($query);

}

if (isset($_POST['edit'])) {

    $update_id = $_POST['update_id'];

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_errno) {
        printf("Connect failed %s\n", $mysqli->connect_error);
        exit();
    }

}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($mysqli->connect_errno) {
        printf("Connect failed %s\n", $mysqli->connect_error);
        exit();
    }
    
    $query = $mysqli->prepare("DELETE FROM posts WHERE id = ?");
    $query->bind_param("d", $delete_id);
    
    $query->execute();
    
}

if (isset($_POST['submit_btn'])) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'thought', FILTER_SANITIZE_STRING);
        //$subject = $_POST['subject'];
        //$content = $_POST['thought'];

        $new_query = $mysqli->prepare("INSERT INTO posts (subject, content) 
        VALUES (?, ?)");
        $new_query->bind_param("ss", $subject, $content);

        //$result = $mysqli->query($new_query);
        $new_query->execute();
}

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_errno) {
        printf("Connect failed %s\n", $mysqli->connect_error);
        exit();
    }

    $resource = $mysqli->query("SELECT * FROM posts ORDER BY created_at DESC");

    $results = array();

    while($row = $resource->fetch_assoc()) {
        $results[] = $row;
    }

    $resource->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>What's On Your Mind?</title>
    <link rel="stylesheet" type="text/css" href="https://bootswatch.com/4/minty/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-5">What's On Your Mind?</h1>
        <p class="text-center">Please write your thoughts here:</p>
        <div class="d-flex justify-content-center mb-4">
        <form action="" method="POST">
        <input type="text" name="subject" id="subject" class="mb-2 form-control" placeholder="Subject of Thought:" value="<?php if (isset($_POST['edit'])) { echo $_POST['update_subject']; } ?>">
        <input type="text" name="thought" id="thought" placeholder="My thoughts are..." class="form-control mb-3" value="<?php if (isset($_POST['edit'])) { echo $_POST['update_content']; } ?>">
        <input type="hidden" name="updated_id" id="updated_id" value="<?php if (isset($_POST['update_id'])) { echo $_POST['update_id']; } ?>">
        <div class="text-center">
        <input type="submit" value="<?php if (isset($_POST['edit'])) { echo 'Edit'; } else { echo 'Submit'; } ?>" name="<?php if (isset($_POST['edit'])) { echo 'edit_btn'; } else { echo 'submit_btn'; }?>" class="btn-primary btn-small btn">
        </div>
        </form>
        </div>
        <?php if($results) : ?>
        <div class="list-group">
        <?php foreach($results as $key) : ?>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?php echo $key['subject']; ?></h5>
            <small>
            <?php echo $key['created_at'] ?>
            <form method="POST" action="">
            <input type="hidden" name="delete_id" value="<?php echo $key['id']; ?>">
            <input type="hidden" name="update_id" value="<?php echo $key['id']; ?>">
            <input type="hidden" name="update_subject" value="<?php echo $key['subject']; ?>">
            <input type="hidden" name="update_content" value="<?php echo $key['content']; ?>">
            <input type="submit" name="edit" class="edit-btn btn mt-3 ml-5 btn-small btn-secondary" value="Edit">
            <input type="submit" name="delete" class="btn mt-3 ml-5 btn-small btn-danger" value="Delete">
            </form>
            </small>
            </div>
            <p class="mb-1"><?php echo $key['content']; ?></p>
            <!-- <small>Donec id elit non mi porta.</small> -->
            </a>
        <?php endforeach; ?>
</div>
<?php endif; ?>
    </div>
</body>
</html>