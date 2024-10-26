<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $customer_name = $_POST['customer_name'];
    $date = $_POST['date'];
    $comments = $_POST['comments'];
    $EngName = $_POST['EngName']; 
    $customer_location = $_POST['customer_location']; 
    $customer_address = $_POST['customer_address']; 
    $customer_contact = $_POST['customer_contact']; 
    $customer_signature = $_POST['customer_signature']; 
    $equipments_installed = isset($_POST['equipments_installed']) && is_array($_POST['equipments_installed']) ? implode(",", $_POST['equipments_installed']) : '';
    $config_details = $_POST['config_details']; 
    $serial_numbers = $_POST['serial_numbers']; 
    $type_of_service = $_POST['type_of_service']; 
    $site_id = $_POST['site_id']; 
    $smedium = $_POST['smedium']; 
    $creator = $_SESSION['user_name']; // Storing the creator's name from session
    $is_draft = isset($_POST['save_draft']) ? 1 : 0;

    $totalImgFiles = count($_FILES['fileImg']['name']);
    $imgFilesArray = array();

    // Handle image files upload
    for ($i = 0; $i < $totalImgFiles; $i++) {
        $imgName = $_FILES["fileImg"]["name"][$i];
        $imgTmpName = $_FILES["fileImg"]["tmp_name"][$i];
        $imgExtension = pathinfo($imgName, PATHINFO_EXTENSION);
        $newImgName = uniqid() . '.' . $imgExtension;
        $targetImgFilePath = 'uploads/' . $newImgName;

        if (move_uploaded_file($imgTmpName, $targetImgFilePath)) {
            $imgFilesArray[] = $newImgName;
        } else {
            echo "Failed to upload image file {$imgName}.<br>";
        }
    }

    // Handle text file upload
    $txtName = $_FILES["fileTxt"]["name"];
    $txtTmpName = $_FILES["fileTxt"]["tmp_name"];
    $txtExtension = pathinfo($txtName, PATHINFO_EXTENSION);
    $newTxtName = uniqid() . '.' . $txtExtension;
    $targetTxtFilePath = 'uploads/' . $newTxtName;

    if (move_uploaded_file($txtTmpName, $targetTxtFilePath)) {
        // Insert or update record in database
        $imgFilesJson = json_encode($imgFilesArray);

        // Check if it's an update or a new record
        if (isset($_POST['record_id'])) {
            // Update existing record
            $record_id = $_POST['record_id'];
            $query = "UPDATE tb_images SET customer_name=?, date=?, comments=?, image=?, text_file=?, EngName=?, customer_location=?,smedium=?, customer_address=?, customer_contact=?, customer_signature=?, equipments_installed=?, config_details=?, serial_numbers=?, type_of_service=?, site_id=?, creator=?, is_draft=? WHERE id=?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                // Note: Ensure that the types match your database schema
                $stmt->bind_param("sssssssssssssssssi", $customer_name, $date, $comments, $imgFilesJson, $newTxtName,$smedium, $EngName, $customer_location, $customer_address, $customer_contact, $customer_signature, $equipments_installed, $config_details, $serial_numbers, $type_of_service, $site_id, $creator, $is_draft, $record_id);
            }
        } else {
            // Insert new record
            $query = "INSERT INTO tb_images (customer_name, date, comments, image, text_file, EngName, customer_location, customer_address, smedium, customer_contact, customer_signature, equipments_installed, config_details, serial_numbers, type_of_service, site_id, creator, is_draft) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                // Note: Ensure that the types match your database schema
                $stmt->bind_param("sssssssssssssssssi", $customer_name, $date, $comments, $imgFilesJson, $newTxtName,$smedium,$EngName, $customer_location, $customer_address, $customer_contact, $customer_signature, $equipments_installed, $config_details, $serial_numbers, $type_of_service, $site_id, $creator, $is_draft);
            }
        }

        if ($stmt && $stmt->execute()) {
            echo "
                <script>
                    alert('Record saved successfully.');
                    window.location.href = 'home.php';
                </script>";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }

        if ($stmt) $stmt->close();
    } else {
        echo "Failed to upload text file {$txtName}.<br>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload A Record</title>
    <link rel="stylesheet" href="./css/upload.css">
    <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>

    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body class="bg-[#111827]">
<a href="home.php" class=" mt-[-20px] fixed left-0 right-0 pt-8">
<img class="ml-[200px] mt-[-20px]" src="back-icon-new.png" width="40px" height="40px">
</a>
 
<form action="upload.php"  method="post" enctype="multipart/form-data" class="bg-[#111827]">
  <div class="space-y-12">
    <div class="border-b border-gray-100/10 pb-12">
      <h2 class="text-base font-semibold leading-7 text-gray-100">Upload New a file</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600">This information will be displayed publicly so be careful what you share.</p>


      <div class="border-b border-gray-100/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-100">Installation Report</h2>
        <!-- <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p> -->
  
        <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <label for="customer_name" class="block text-sm font-medium leading-6 text-gray-100">Customer Name</label>
            <div class="mt-2">
              <input type="text" name="customer_name" id="customer_name" autocomplete="given-name" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3">
            <label for="customer_location" class="block text-sm font-medium leading-6 text-gray-100"> Customer Location</label>
            <div class="mt-2">
              <input type="custloc" id="customer_location" name="customer_location" autocomplete="" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3 ">

            <label for="customer_address" class="block text-sm font-medium leading-6 text-gray-100">Address</label>
            <div class="mt-2">
              <input type="text" id="customer_address" name="customer_address" autocomplete="custaddress" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>

          </div>
  
          <div class="sm:col-span-3">
            <label for="customer_contact" class="block text-sm font-medium leading-6 text-gray-100">Customer Contact</label>
            <div class="m-2">
                <input type="text" id="customer_contact" name="customer_contact" autocomplete="custnum" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              
            </div>
          </div>
          
          <div class="sm:col-span-3">
            <label for="customer_signature" class="block text-sm font-medium leading-6 text-gray-100">Customer Signature</label>
            <div class="m-2">
                <input type="text" id="customer_signature" name="customer_signature" autocomplete="custsig" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              
            </div>
          </div>

          <div class="sm:col-span-full">
    <h2 class="text-base font-semibold leading-7 text-gray-100">Equipment Details</h2>
    <label for="equipments_installed" class="block text-sm font-medium leading-6 text-gray-100">Equipment installed</label>
    <div class="mt-2">
      <select id="equipments_installed" name="equipments_installed[]" multiple >
        <option value="Huawei S5700 MPLS L3 Switch">Huawei S5700 MPLS L3 Switch</option>
        <option value="Huawei AR1220W Router">Huawei AR1220W Router</option>
        <option value="Huawei S2700 Switch">Huawei S2700 Switch</option>
        <option value="Huawei S2300 Switch">Huawei S2300 Switch</option>
        <option value="Huawei S3300 Switch">Huawei S3300 Switch</option>
        <option value="Huawei S3700 Switch">Huawei S3700 Switch</option>
        <option value="Huawei Quidway S5720-52X Switch">Huawei Quidway S5720-52X Switch</option>
        <option value="Huawei eA360-123 LTE CPE">Huawei eA360-123 LTE CPE</option>
        <option value="Huawei B222s-41 LTE CPE">Huawei B222s-41 LTE CPE</option>
        <option value="Huawei ATN950">Huawei ATN950</option>
        <option value="Ubiquiti PowerBeam 5AC Gen2">Ubiquiti PowerBeam 5AC Gen2</option>
        <option value="Ubiquiti LiteBeam 5AC Gen2">Ubiquiti LiteBeam 5AC Gen2</option>
        <option value="Ubiquiti NanoStation M5">Ubiquiti NanoStation M5</option>
        <option value="Ubiquiti NanoStation M2">Ubiquiti NanoStation M2</option>
        <option value="Ubiquiti Ethernet Surge Arrestor">Ubiquiti Ethernet Surge Arrestor</option>
        <option value="Ubiquiti U6 Mesh Pro">Ubiquiti U6 Mesh Pro</option>
        <option value="24 Volts PoE Adaptor">24 Volts PoE Adaptor</option>
        <option value="54 Volts PoE Adaptor">54 Volts PoE Adaptor</option>
        <option value="Pole And Arm">Pole And Arm</option>
        <option value="Anchor Bolts">Anchor Bolts</option>
        <option value="CAT6 Outdoor LAN Cable">CAT6 Outdoor LAN Cable</option>
        <option value="CAT6 Indoor LAN Cable">CAT6 Indoor LAN Cable</option>
        <option value="3m LAN Patch Cable">3m LAN Patch Cable</option>
        <option value="Fibre Patch Cord">Fibre Patch Cord</option>
        <option value="Media Convertor">Media Convertor</option>
        <option value="Fibre Drop Cable">Fibre Drop Cable</option>
        <option value="2-Core Fibre ODF">2-Core Fibre ODF</option>
        <option value="Huawei s5300">Huawei s5300</option>
        <option value="ISO Clips">ISO Clips</option>
      </select>
    </div>
  </div>
  
          <div class="sm:col-span-3 ">
            <label for="config_details" class="block text-sm font-medium leading-6 text-gray-100">Configuration details</label>
            <div class="mt-2">
              <input type="text" name="config_details" id="config_details" autocomplete="address-level1" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
  
          <div class="sm:col-span-3">
            <label for="serial_numbers" class="block text-sm font-medium leading-6 text-gray-100">Serial numbers</label>
            <div class="mt-2">
              <input type="text" name="serial_numbers" id="serial_numbers" autocomplete="serial_numbers" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-full">
        <label for="comments" class="block text-sm font-medium leading-6 text-gray-100">Comments</label>
        <div class="mt-2">
          <textarea id="comments" name="comments" rows="3" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
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
          <select id="type_of_service" name="type_of_service" autocomplete="type_of_service" class=" bg-gray-600/25 hover:bg-gray-600/50  block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
            <option>Internet Service</option>
            <option>Speedtest</option>
            <option>Fibre Maintenance</option>
          </select>
        </div>
      </div>
      <div class="sm:col-span-4">
        <label for="smedium" class="block text-sm font-medium leading-6 text-gray-100"> Mode Of Connectivty</label>
        <div class="mt-2">
          <select id="smedium" name="smedium" autocomplete="smedium" class=" bg-gray-600/25 hover:bg-gray-600/50  block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
            <option>LTE</option>
            <option>Fibre Optics</option>
            <option>Point to Point</option>
          </select>
        </div>
      </div>
      </div>


  <div class="border-b border-gray-100/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-100">Techincal Report</h2>

    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="sm:col-span-3">
        <label for="site_id" class="block text-sm font-medium leading-6 text-gray-100">Site ID</label>
        <div class="mt-2">
          <input type="text" name="site_id" id="site_id" autocomplete="site_id" class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div class="sm:col-span-3">
        <label for="date" class="block text-sm font-medium leading-6 text-gray-100">Installation Date</label>
        <div class="mt-2">
          <input type="date" id="date" name="date" autocomplete="date" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div class="sm:col-span-3 ">

        <label for="EngName" class="block text-sm font-medium leading-6 text-gray-100">Engineer Name</label>
        <div class="mt-2">
          <input type="text" id="EngName" name="EngName" autocomplete="EngName" class=" bg-gray-600/25 hover:bg-gray-600/50 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
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
                  <input type="file" id="fileTxt" name="fileTxt" accept=".txt,.bin"  >
                </label>
              
              </div>
              <p class="text-xs leading-5 text-gray-600">TXT</p>
            </div>
          </div>
        </div>
  
      </div>
    </div>

   

  <div class=" action mt-6 flex items-center justify-end gap-x-6">
  <button type="submit" name="save_draft" class="btn btn-secondary">Save as Draft</button>
    <button type="submit" name="submit" value="SUBMIT" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
  </div>
</form>



 <script>
    new MultiSelectTag('equipments_installed', {
      rounded: true,
      shadow: true,
      placeholder: 'Search',
      tagColor: {
        textColor: '#f3f8f3',
        borderColor: '#92e681',
        bgColor: '#4f6d4a',
      },
      onChange: function(values) {
        console.log(values);
      }
    });
  </script>

</body>
</html>
