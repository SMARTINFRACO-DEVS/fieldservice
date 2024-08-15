<?php
session_start();


if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. Missing record ID.");
}

$id = $_GET['id'];

try {
  
    $query = "SELECT * FROM tb_images WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_name = htmlspecialchars($row['customer_name']);
        $date = htmlspecialchars($row['date']);
        $comments = htmlspecialchars($row['comments']);
        $images = json_decode($row['image']);
        $txtFile = htmlspecialchars($row['text_file']);
        $customer_location= htmlspecialchars($row['customer_location']);
        $customer_address= htmlspecialchars($row['customer_address']);
        $customer_contact= htmlspecialchars($row['customer_contact']);
        $customer_signature= htmlspecialchars($row['customer_signature']);
        $equipments_installed= explode("\n", $row['equipments_installed']);
        $config_details= htmlspecialchars($row['config_details']);
        $serial_numbers= htmlspecialchars($row['serial_numbers']);
        $type_of_service= htmlspecialchars($row['type_of_service']);
        $site_id=  htmlspecialchars($row['site_id']);
        $EngName=  htmlspecialchars($row['EngName']);
    
    } else {
        die("Record not found.");
    }

  function displayTextFile($filename) {
    $filepath = 'uploads/' . $filename;
    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        echo "<textarea readonly rows='10' style='width: 100%;'>$content</textarea>";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit A Record</title>
    <link rel="stylesheet" href="./css/edit.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
   
</head>
<body class="bg-[#111827]">
<a href="home.php" class=" mt-[-20px] fixed left-0 right-0 pt-8">
<img class="ml-[200px] mt-[-20px]" src="back-icon-new.png" width="40px" height="40px">
</a>

<form action="upload.php"  method="post" enctype="multipart/form-data" class="bg-[#111827]">
  <div class="space-y-12">
    <div class="border-b border-gray-100/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-100"> View Recorded Information</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600">This information will be displayed publicly so be careful what you share.</p>


      <div class="border-b border-gray-100/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-100">Installation Report</h2>
        <!-- <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p> -->
  
        <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <label for="customer_name" class="block text-sm font-medium leading-6 text-gray-100">Customer Name</label>
            <div class="mt-2">
              <input type="text" name="customer_name" id="customer_name" value="<?php echo $customer_name; ?>"  autocomplete="given-name" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3">
            <label for="customer_location" class="block text-sm font-medium leading-6 text-gray-100"> Customer Location</label>
            <div class="mt-2">
              <input type="custloc" id="customer_location" name="customer_location" value="<?php echo $customer_location; ?>" autocomplete="" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3 ">

            <label for="customer_address" class="block text-sm font-medium leading-6 text-gray-100">Address</label>
            <div class="mt-2">
              <input type="text" id="customer_address" name="customer_address" autocomplete="custaddress" value="<?php echo $customer_address; ?>" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>

          </div>
  
          <div class="sm:col-span-3">
            <label for="customer_contact" class="block text-sm font-medium leading-6 text-gray-100">Customer Contact</label>
            <div class="m-2">
                <input type="text" id="customer_contact" name="customer_contact" autocomplete="custnum"  value="<?php echo $customer_contact; ?>"      class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              
            </div>
          </div>
          
          <div class="sm:col-span-3">
            <label for="customer_signature" class="block text-sm font-medium leading-6 text-gray-100">Customer Signature</label>
            <div class="m-2">
                <input type="text" id="customer_signature" name="customer_signature" autocomplete="custsig"  value="<?php echo $customer_signature; ?>"  class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              
            </div>
          </div>

          <div class="sm:col-span-full">
  <label for="equipments_installed" class="block text-sm font-medium leading-6 text-gray-100">Equipment Installed</label>
  <div class="mt-2">
    <textarea id="equipments_installed" name="equipments_installed" rows="3" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?php echo htmlspecialchars(implode("\n", $equipments_installed)); ?></textarea>
  </div>
</div>
  
          <div class="sm:col-span-3 ">
            <label for="config_details" class="block text-sm font-medium leading-6 text-gray-100">Configuration details</label>
            <div class="mt-2">
              <input type="text" name="config_details" id="config_details"  value="<?php echo $config_details; ?>"  autocomplete="address-level1" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3">
            <label for="serial_numbers" class="block text-sm font-medium leading-6 text-gray-100">Serial numbers</label>
            <div class="mt-2">
              <input type="text" name="serial_numbers" id="serial_numbers"   value="<?php echo $serial_numbers; ?>"  autocomplete="serial_numbers" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-full">
  <label for="comments" class="block text-sm font-medium leading-6 text-gray-100">Comments</label>
  <div class="mt-2">
    <textarea id="comments" name="comments" rows="3" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?php echo htmlspecialchars($comments); ?></textarea>
  </div>
</div>

  </div>

  <div class="border-b border-gray-100/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-100">Service Report</h2>

    <div class="sm:col-span-3">
        <label for="type_of_service" class="block text-sm font-medium leading-6 text-gray-100"> Type of Service</label>
        <div class="mt-2">
          <select id="type_of_service" name="type_of_service" autocomplete="type_of_service"  value="<?php echo $type_of_service; ?>"     class=" bg-gray-600/25 hover:bg-gray-600/50  block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
            <option>Internet Service</option>
            <option>Speedtest</option>
            <option>Fibre Maintenance</option>
          </select>
        </div>
      </div>
      </div>


  <div class="border-b border-gray-100/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-100">Techincal Report</h2>
    <!-- <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p> -->

    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="sm:col-span-3">
        <label for="site_id" class="block text-sm font-medium leading-6 text-gray-100">Site ID</label>
        <div class="mt-2">
          <input type="text" name="site_id" id="site_id" autocomplete="site_id"  value="<?php echo $site_id; ?>"  class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div class="sm:col-span-3">
        <label for="date" class="block text-sm font-medium leading-6 text-gray-100">Installation Date</label>
        <div class="mt-2">
          <input type="date" id="date" name="date" autocomplete="date"  value="<?php echo $date; ?>"  class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div class="sm:col-span-3 ">

        <label for="EngName" class="block text-sm font-medium leading-6 text-gray-100">Engineer Name</label>
        <div class="mt-2">
          <input type="text" id="EngName" name="EngName" autocomplete="EngName"   value="<?php echo $EngName; ?>"  class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>

      </div>


<!-- Upload Photo -->

        <div class="col-span-full pb-12">
          <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-100">Photo(s)</label>
          <div class="full">
        <div class="item">
        <table class="tabletable">
        <tbody id="table-body">
        <div class="images">
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
       
        </div>


        <!-- Upload Config file -->

        <div class="col-span-full pb-12">
          <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-100">Configuration File</label>
          <div class="full">
        <div class="item">
        <table>
        <tbody id="table-body">
        <p><strong>Text File Content:</strong></p>
            <div class="text-file-content">
                <?php
                displayTextFile($row['text_file']);
                ?>
            </div>
        </tbody>
    </table>  </div>
      </div>
       
        </div>
  
      </div>
    </div>

   

  <div class=" action mt-6 flex items-center justify-end gap-x-6">
  <a href="delete.php?id=<?php echo $id; ?>" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
    <button type="button" onclick="redirectToPage()" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cancel</button>  </div>
</form>

</body>


<script>
        function redirectToPage() {
            window.location.href = 'home.php';
        }
    </script>
</html>
