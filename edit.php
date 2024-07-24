<?php
require 'config.php';

// Ensure ID parameter is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. Missing record ID.");
}

$id = $_GET['id'];

try {
  
    // Fetch record details from database
    $query = "SELECT * FROM table_backup WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $date = htmlspecialchars($row['date']);
        $comments = htmlspecialchars($row['comments']);
        $images = json_decode($row['image']);
        $txtFile = htmlspecialchars($row['text_file']);
    } else {
        die("Record not found.");
    }
    function displayTextFileEditable($filename) {
      $id = $_GET['id'];
      $filepath = 'uploads/' . $filename;
      if (file_exists($filepath)) {
          $content = file_get_contents($filepath);
          echo "<form action='records.php?id=$id' method='post'>";
          echo "<textarea name='edited_content' rows='10' style='width: 100%;'>$content</textarea>";
          echo "<br><br>";
          echo "<input type='submit' name='submit' value='Go back'>";
          echo "</form>";
      } else {
          echo "File not found.";
      }
  }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Record Details</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
  </head>
  <body>
  <nav>
        <ul>
            <li style="float: left;"><a href="home.php">Home</a></li>
            <li style="float: left;"><a href="#">Edit</a></li>
        
            <li style="float: right;"><a href="logout.php">Logout</a></li> 
    </nav>
    <form action="home.php" method="" >
      <div class="title">
        <h2>View Details</h2>
      </div>
      <div class="half">
                <div class="item">
                    <label for="name">Site ID/ID</label>
                    <input type="text" id="name" name="name" readonly value="<?php echo $name; ?>">
                </div>
                <div class="item">
                    <label for="date">Date of Installation</label>
                    <input type="text" id="date" name="date" readonly value="<?php echo $date; ?>">
                </div>
            </div>
            <div class="full">
                <div class="item">
                    <label for="comments">Comments</label>
                    <textarea id="comments" name="comments" rows="4" readonly><?php echo $comments; ?></textarea>
                </div>
            </div>
      <div class="full">
        <div class="item">
        <table>
        <tbody id="table-body">
        <div class="images">
                <h3>Images</h3>
                <?php
                foreach ($images as $image) {
                    echo "<img src='uploads/{$image}'>";
                }
                ?>
            </div>
        </tbody>
    </table>
      
        </div>
      </div>
      <div class="full">
        <div class="item">
        <table>
        <tbody id="table-body">
        <p><strong>Text File Content:</strong></p>
            <div class="text-file-content">
                <?php
             displayTextFileEditable($txtFile); 
                ?>
            </div>
        </tbody>
    </table> 
  
  </div>
      </div>
   
    </form>
  </body>
</html>