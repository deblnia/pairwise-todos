<?php
session_start();
// Checking if we're in compare mode 
if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

// Function to get two random, different indexes
function getRandomPair($max) {
    $first = rand(0, $max - 1);
    do {
        $second = rand(0, $max - 1);
    } while ($second === $first);
    
    return [$first, $second];
}
$comparing = isset($_GET['compare']) && $_GET['compare'] === 'true';

if ($comparing && count($_SESSION['todos']) >= 2) {
    $pair = getRandomPair(count($_SESSION['todos']));
}

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
    <?php if (!$comparing): ?>
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
    <a href="?compare=true" style="
    background-color: #eeeeee;
    border: 1px dashed #222222;
    border-radius: 2px;
    color: #222222;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    font-size: 1rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
">Click to Organize</a>

        <!-- Your existing todo list view stays exactly the same -->
        
    <?php else: ?>
        <h1>Compare Tasks</h1>
        <p>Which task makes you more anxious?</p>
        
        <!-- Simple two-column layout for comparison -->
        <div style="display: flex; justify-content: space-between; gap: 20px; margin: 40px 0;">
        <!-- First task -->
            <div style="flex: 1; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; text-align: center; cursor: pointer;">
                <form method="POST">
                    <input type="hidden" name="choice" value="0">
                    <button type="submit" style="width: 100%; background: none; border: none; cursor: pointer; font-size: 18px;">
                        <?php echo htmlspecialchars($_SESSION['todos'][$pair[0]]); ?>
                    </button>
                </form>
            </div>
    
        <!-- Second task -->
        <div style="flex: 1; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; text-align: center; cursor: pointer;">
            <form method="POST">
                <input type="hidden" name="choice" value="1">
                <button type="submit" style="width: 100%; background: none; border: none; cursor: pointer; font-size: 18px;">
                    <?php echo htmlspecialchars($_SESSION['todos'][$pair[1]]); ?>
                </button>
            </form>
        </div>
    </div>
        
        <a href="?" style="
            background-color: #eeeeee;
            border: 1px dashed #222222;
            border-radius: 2px;
            color: #222222;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        ">Back to List</a>
    <?php endif; ?>
</body>