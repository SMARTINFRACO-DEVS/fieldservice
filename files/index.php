<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111827]">
<div>
        <div class=" flex justify-center items-center p-1">
            <img src="./CPEMS.png" alt="" srcset="">
        </div>

           <form class="border-gray-600 mt-[1%] border-[0.25px] bg-gray-600/5 backdrop-blur-md transition duration-300 ease-in-out" action="login.php" method="post">
					 <h1 class="block text-xl font-bold  leading-6 text-gray-100">LOGIN</h1>
				<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>

    <label class="block text-sm font-medium leading-6 text-gray-100 text-md">User Name</label>
                <input type="text" name="uname" placeholder="User Name" 
               class=" bg-gray-600/25 hover:bg-gray-600/50 py-4 px-2 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><br>
       
                <label class="block text-sm font-medium leading-6 text-gray-100 text-md">Password</label>
                <input type="password" name="password" placeholder="Password" 
               class=" bg-gray-600/25 hover:bg-gray-600/50 py-4 px-2 block w-full p-[5px] rounded-md mb-4 border-0 py-1.5 text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><br>
       
                <div class=" action mt-6 flex items-center justify-end gap-x-6">
                   <button type="submit" name="submit" value="SUBMIT" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                 </div>
     </form>
    </div>



</body>
</html>


