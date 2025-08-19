<?php

$pdo = new PDO("sqlite:students.db");

$sql = "SELECT * FROM students_table";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo->exec("CREATE TABLE IF NOT EXISTS students_table (
    	id INTEGER PRIMARY KEY AUTOINCREMENT,
    	first_name TEXT NOT NULL,
    	middle_name TEXT,
    	last_name TEXT NOT NULL,
    	age INTEGER NOT NULL,
    	date_created INTEGER NOT NULL
		)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'] ?? '';
    $middle = $_POST['middle_name'] ?? '';
    $last = $_POST['last_name'] ?? '';
    $age = (int)($_POST['age'] ?? 0);
    $created = time();

    if ($first && $last && $age > 0) {
        $stmt = $pdo->prepare("INSERT INTO students_table (
		first_name, 
		middle_name, 
		last_name, 
		age, 
		date_created) VALUES (?, ?, ?, ?, ?)
		");

        $stmt->execute([$first, $middle, $last, $age, $created]);
        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
</head>
<body>

<h1>Students List</h1>
<?php if ($rows): ?>
<table>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>Date Created (UNIX)</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
        <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
        <td><?php echo htmlspecialchars($row['age']); ?></td>
        <td><?php echo htmlspecialchars($row['date_created']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No students found.</p>
<?php endif; ?>

<h1>Add Student</h1>
<form method="POST" action="">
    First Name: <input type="text" name="first_name" required><br><br>
    Middle Name: <input type="text" name="middle_name"><br><br>
    Last Name: <input type="text" name="last_name" required><br><br>
    Age: <input type="number" name="age" required><br><br>
    <button type="submit">Submit</button>
</form>

</body>
</html>
