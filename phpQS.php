<?php
/**----------------------------------------------------------------------------------
* Microsoft Developer & Platform Evangelism
*
* Copyright (c) Microsoft Corporation. All rights reserved.
*
* THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
* EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
* OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
*----------------------------------------------------------------------------------
* The example companies, organizations, products, domain names,
* e-mail addresses, logos, people, places, and events depicted
* herein are fictitious.  No association with any real company,
* organization, product, domain name, email address, logo, person,
* places, or events is intended or should be inferred.
*----------------------------------------------------------------------------------
**/

/** -------------------------------------------------------------
# Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service. 
# Blob storage stores unstructured data such as text, binary data, documents or media files. 
# Blobs can be accessed from anywhere in the world via HTTP or HTTPS. 
#
# Documentation References: 
#  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php 
#  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/ 
#  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
#  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx 
#  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx 
#  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
#  - Storage Emulator - http://azure.microsoft.com/en-us/documentation/articles/storage-use-emulator/ 
#
**/

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=armanwebapp;AccountKey=w7nelSUskJiDheADIcyIEkufPSKBWTBuPARQI9vHqUPe73JqfnQTdDem28a9phMishXDmzKAL++S+NlNUk5qvw==";

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$name = "arman";
$format = ".jpg";

$fileToUpload = "rossi.jpg";
// $fileToUpload = $name . $format;

// if (!isset($_GET["Cleanup"])) {
//     // Create container options object.
//     $createContainerOptions = new CreateContainerOptions();

//     // Set public access policy. Possible values are
//     // PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
//     // CONTAINER_AND_BLOBS:
//     // Specifies full public read access for container and blob data.
//     // proxys can enumerate blobs within the container via anonymous
//     // request, but cannot enumerate containers within the storage account.
//     //
//     // BLOBS_ONLY:
//     // Specifies public read access for blobs. Blob data within this
//     // container can be read via anonymous request, but container data is not
//     // available. proxys cannot enumerate blobs within the container via
//     // anonymous request.
//     // If this value is not specified in the request, container data is
//     // private to the account owner.
//     $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

//     // Set container metadata.
//     $createContainerOptions->addMetaData("key1", "value1");
//     $createContainerOptions->addMetaData("key2", "value2");

//       $containerName = "blockblobs".generateRandomString();

//     try {
//         // Create container.
//         $blobClient->createContainer($containerName, $createContainerOptions);

//         // Getting local file so that we can upload it to Azure
//         $myfile = fopen($fileToUpload, "r") or die("Unable to open file!");
//         fclose($myfile);
        
//         # Upload file as a block blob
//         echo "Uploading BlockBlob: ".PHP_EOL;
//         echo $fileToUpload;
//         echo "<br />";
        
//         $content = fopen($fileToUpload, "r");

//         //Upload blob
//         $blobClient->createBlockBlob($containerName, $fileToUpload, $content);

//         // List blobs.
//         $listBlobsOptions = new ListBlobsOptions();
//         $listBlobsOptions->setPrefix("rossi");

//         echo "These are the blobs present in the container: ";

//         do{
//             $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
//             foreach ($result->getBlobs() as $blob)
//             {
//                 echo $blob->getName().": ".$blob->getUrl()."<br />";
//             }
        
//             $listBlobsOptions->setContinuationToken($result->getContinuationToken());
//         } while($result->getContinuationToken());
//         echo "<br />";

//         // Get blob.
//         echo "This is the content of the blob uploaded: ";
//         $blob = $blobClient->getBlob($containerName, $fileToUpload);
//         fpassthru($blob->getContentStream());
//         echo "<br />";
//     }
//     catch(ServiceException $e){
//         // Handle exception based on error codes and messages.
//         // Error codes and messages are here:
//         // http://msdn.microsoft.com/library/azure/dd179439.aspx
//         $code = $e->getCode();
//         $error_message = $e->getMessage();
//         echo $code.": ".$error_message."<br />";
//     }
//     catch(InvalidArgumentTypeException $e){
//         // Handle exception based on error codes and messages.
//         // Error codes and messages are here:
//         // http://msdn.microsoft.com/library/azure/dd179439.aspx
//         $code = $e->getCode();
//         $error_message = $e->getMessage();
//         echo $code.": ".$error_message."<br />";
//     }
// } 
// else 
// {

//     try{
//         // Delete container.
//         echo "Deleting Container".PHP_EOL;
//         echo $_GET["containerName"].PHP_EOL;
//         echo "<br />";
//         $blobClient->deleteContainer($_GET["containerName"]);
//     }
//     catch(ServiceException $e){
//         // Handle exception based on error codes and messages.
//         // Error codes and messages are here:
//         // http://msdn.microsoft.com/library/azure/dd179439.aspx
//         $code = $e->getCode();
//         $error_message = $e->getMessage();
//         echo $code.": ".$error_message."<br />";
//     }
// }

    

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>

    
    <div class="file-upload">
      <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>

        <form method="post" action="upload_image.php" enctype="multipart/form-data">
            <div class="image-upload-wrap">
                <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" name="image" />
                <div class="drag-text">
                    <h3>Drag and drop a file or select add Image</h3>
                </div>
            </div>
            <div class="file-upload-content">
                <img class="file-upload-image" src="#" alt="your image" />
                <div class="image-title-wrap">
                    <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                </div>
            </div>
            <input type="submit" name="addImage" value="Upload Image" class="file-upload-btn">
        </form>
    </div>

    <!-- <form method="post" action="phpQS.php?Cleanup&containerName=<?php echo $containerName; ?>">
        <button type="submit">Press to clean up all resources created by this sample</button>
    </form> -->


<script type="text/javascript">
    function readURL(input) {
      if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function(e) {
          $('.image-upload-wrap').hide();

          $('.file-upload-image').attr('src', e.target.result);
          $('.file-upload-content').show();

          $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);

      } else {
        removeUpload();
      }
    }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
    }
        $('.image-upload-wrap').bind('dragover', function () {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function () {
        $('.image-upload-wrap').removeClass('image-dropping');
    });

</script>
</body>
</html>