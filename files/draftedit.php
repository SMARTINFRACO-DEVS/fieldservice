<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

require 'config.php';

// Initialize variables for existing record data
$customer_name = $date = $comments = $EngName = $customer_location = $customer_address = $customer_contact = $customer_signature = $equipments_installed = $config_details = $serial_numbers = $type_of_service = $site_id = "";
$existing_images = [];
$existing_text_file = "";
$record_id = null;

if (isset($_GET['id'])) {
    $record_id = intval($_GET['id']);
    $query = "SELECT * FROM tb_images WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Fetch record data
        $customer_name = htmlspecialchars($row['customer_name']);
        $date = htmlspecialchars($row['date']);
        $comments = htmlspecialchars($row['comments']);
        $EngName = htmlspecialchars($row['EngName']);
        $customer_location = htmlspecialchars($row['customer_location']);
        $customer_address = htmlspecialchars($row['customer_address']);
        $customer_contact = htmlspecialchars($row['customer_contact']);
        $customer_signature = htmlspecialchars($row['customer_signature']);
        $equipments_installed = explode(",", htmlspecialchars($row['equipments_installed']));
        $config_details = htmlspecialchars($row['config_details']);
        $serial_numbers = htmlspecialchars($row['serial_numbers']);
        $type_of_service = htmlspecialchars($row['type_of_service']);
        $site_id = htmlspecialchars($row['site_id']);
        $existing_images = json_decode($row['image'], true);
        $existing_text_file = htmlspecialchars($row['text_file']);
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit a Draft Record</title>
    <link rel="stylesheet" href="./css/upload.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body class="bg-[#111827]">
    <a href="home.php" class="mt-[-20px] fixed left-0 right-0 pt-8">
        <img class="ml-[200px] mt-[-20px]" src="back-icon-new.png" width="40px" height="40px">
    </a>

    <form action="drafteditsend.php<?php echo isset($record_id) ? '?id=' . $record_id : ''; ?>" method="post" enctype="multipart/form-data" class="bg-[#111827]">
        <!-- Include hidden input to identify record -->
        <?php if (isset($record_id)): ?>
            <input type="hidden" name="record_id" value="<?php echo $record_id; ?>">
        <?php endif; ?>
        <div class="hidden text-xs font-semibold uppercase tracking-wider text-white">
                Draft ID: <span><?php echo htmlspecialchars($record_id); ?></span>
            </div>
        <div class="space-y-12">
    <div class="border-b border-gray-100/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-100">Edit a Draft</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600">This information will be displayed publicly so be careful what you share.</p>


      <div class="border-b border-gray-100/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-100">Installation Report</h2>
        <!-- <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p> -->
  
        <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <label for="customer_name" class="block text-sm font-medium leading-6 text-gray-100">Customer Name</label>
            <div class="mt-2">
              <input type="text" name="customer_name" id="customer_name" autocomplete="given-name" value="<?php echo htmlspecialchars($customer_name); ?>"  class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3">
            <label for="customer_location" class="block text-sm font-medium leading-6 text-gray-100"> Customer Location</label>
            <div class="mt-2">
              <input type="custloc" id="customer_location" name="customer_location" autocomplete=""  value="<?php echo htmlspecialchars($customer_location); ?>" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3 ">

            <label for="customer_address" class="block text-sm font-medium leading-6 text-gray-100">Address</label>
            <div class="mt-2">
              <input type="text" id="customer_address" name="customer_address" autocomplete="custaddress" value="<?php echo htmlspecialchars($customer_address); ?>"  class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>

          </div>
  
          <div class="sm:col-span-3">
            <label for="customer_contact" class="block text-sm font-medium leading-6 text-gray-100">Customer Contact</label>
            <div class="m-2">
                <input type="text" id="customer_contact" name="customer_contact" autocomplete="custnum" value="<?php echo htmlspecialchars($customer_contact); ?>" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              
            </div>
          </div>
          
          <div class="sm:col-span-3">
            <label for="customer_signature" class="block text-sm font-medium leading-6 text-gray-100">Customer Signature</label>
            <div class="m-2">
                <input type="text" id="customer_signature" name="customer_signature" autocomplete="custsig" value="<?php echo htmlspecialchars($customer_signature); ?>"  class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              
            </div>
          </div>



          <div class="sm:col-span-6">
                        <label for="equipments_installed" class="block text-sm font-medium leading-6 text-gray-100">Equipments Installed</label>
                        <div class="mt-2">
                            <input type="text" name="equipments_installed[]"  rows="3" id="equipments_installed" value="<?php echo htmlspecialchars(implode(",", $equipments_installed)); ?>" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>




  
          <div class="sm:col-span-3 ">
            <label for="config_details" class="block text-sm font-medium leading-6 text-gray-100">Configuration details</label>
            <div class="mt-2">
              <input type="text" name="config_details" id="config_details" value="<?php echo htmlspecialchars($config_details); ?>" autocomplete="address-level1" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3">
            <label for="serial_numbers" class="block text-sm font-medium leading-6 text-gray-100">Serial numbers</label>
            <div class="mt-2">
              <input type="text" name="serial_numbers" id="serial_numbers" autocomplete="serial_numbers" value="<?php echo htmlspecialchars($serial_numbers); ?>" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-full">
        <label for="comments" class="block text-sm font-medium leading-6 text-gray-100">Comments</label>
        <div class="mt-2">
        <textarea id="comments" name="comments" rows="3" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?php echo htmlspecialchars($comments); ?></textarea>
        </div>
        <p class="mt-3 text-sm leading-6 text-gray-600">Write a few comments.</p>
      </div>

  </div>

  <div class="border-b border-gray-100/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-100">Service Report</h2>
    <!-- <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p> -->

    <div class="sm:col-span-3">
        <label for="type_of_service" class="block text-sm font-medium leading-6 text-gray-100"> Type of Service</label>
        <div class="mt-2">
          <select id="type_of_service" name="type_of_service" autocomplete="type_of_service" value="<?php echo htmlspecialchars($type_of_service); ?>" class=" bg-gray-600/25 hover:bg-gray-600/50  block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
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
          <input type="text" name="site_id" id="site_id" autocomplete="site_id"  value="<?php echo htmlspecialchars($site_id); ?>"class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div class="sm:col-span-3">
        <label for="date" class="block text-sm font-medium leading-6 text-gray-100">Installation Date</label>
        <div class="mt-2">
          <input type="date" id="date" name="date" autocomplete="date" value="<?php echo htmlspecialchars($date); ?>"class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div class="sm:col-span-3 ">

        <label for="EngName" class="block text-sm font-medium leading-6 text-gray-100">Engineer Name</label>
        <div class="mt-2">
          <input type="text" id="EngName" name="EngName" autocomplete="EngName" value="<?php echo htmlspecialchars($EngName); ?>" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>

      </div>


<!-- Upload Photo -->

        <div class="col-span-full pb-12">
          <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-100">Upload photo</label>
          <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-100/25 px-6 py-10">
            <div class="text-center">
              <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
              </svg>
              <div class="mt-4 flex text-sm leading-6 text-gray-600">
                <label for="fileImg" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                  <span>Upload Photo(s)</span>
                  <input type="file" id="fileImg" name="fileImg[]" accept=".jpg, .jpeg, .png"  multiple >
                </label>
              
              </div>
              <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF</p>
            </div>
          </div>
        </div>


        <!-- Upload Config file -->

       
        <div class="col-span-full pb-12">
          <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-100">Upload Configuration File</label>
          <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-100/25 px-6 py-10">
            <div class="text-center">
              <div class="mt-4 flex text-sm leading-6 text-gray-600">
                <label for="fileTxt" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                  <span>Upload config file</span>
                  <input type="file" id="fileTxt" name="fileTxt" accept=".txt"  >
                </label>
              
              </div>
              <p class="text-xs leading-5 text-gray-600">TXT</p>
            </div>
          </div>
        </div>
  
      </div>
    </div>

            <div class="text-right">
                <button type="submit" name="submit" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Submit</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
