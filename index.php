<?php
session_start();
// Initialize todos array if it doesn't exist
if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['todo']) && !empty(trim($_POST['todo']))) {
        $_SESSION['todos'][] = trim($_POST['todo']);
    }
    // Handle delete action
    if (isset($_POST['delete'])) {
        $index = $_POST['delete'];
        unset($_SESSION['todos'][$index]);
        $_SESSION['todos'] = array_values($_SESSION['todos']); // Re-index array
    }
}
?>
<!DOCTYPE html>
<head>
    <title>A Little Pairwise To-Do List</title>
</head>
<body>
    <style>
        body {
            margin: 40px auto;
            max-width: 650px;
            line-height: 1.6;
            font-size: 18px;
            color: #444;
            padding: 0 10px
        }
        h1,
        h2,
        h3 {
            line-height: 1.2
        }
        form {
            width: 100%;
        }
        input {
            width: 100%;
            line-height: 1.6;
            font-size: 24px;
            box-sizing: border-box;
            padding: 8px;
        }
        .delete-btn {
            color: #999;
            background: none;
            border: none;
            padding: 0;
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .delete-btn:hover {
            color: #ff0000;
        }
    </style>
    <h1> A Pairwise To-Do List </h1>
    <p> The idea here is that people are really good at making either/or decisions, but not so good at prioritizing an entire list of things. So this works by letting you enter a todo, show those to-dos, and rank your to-dos head to head.  </p>
    <form method="POST">
        <input type="text" name="todo" placeholder="What do you need to do?"> 
    </form>
    <ul>
        <?php foreach ($_SESSION['todos'] as $index => $todo): ?>
            <li>
                <?php echo htmlspecialchars($todo); ?>
                <form method="POST" style="display: inline; width: auto;">
                    <input type="hidden" name="delete" value="<?php echo $index; ?>">
                    <button type="submit" class="delete-btn">×</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <button style="
    background-color: #eeeeee;
    border: 1px dashed #222222;
    border-radius: 2px;
    color: #222222;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    font-size: 1rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
">Click to Organize</button>
</body>