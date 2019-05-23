<?php

    require_once 'vendor/autoload.php';
    require_once "./random_string.php";

    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
    use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
    use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
    use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
    
    if (isset($_FILES["image"])) {
        $errors= array();
        $file_get_name = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $file_name = $_FILES['image']['name'];
        $file_size =$_FILES['image']['size'];
        $file_tmp =$_FILES['image']['tmp_name'];
        $file_type=$_FILES['image']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

        $extensions= array("jpeg","jpg","png");
          
        if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
          
        if($file_size > 2097152){
            $errors[]='File size must be excately 2 MB';
        }
          
        if(empty($errors)==true){
            move_uploaded_file($file_tmp, $file_name);
            upImage($file_name, $file_get_name);
        }else{
            print_r($errors);
        }
    }

    function upImage($file_name, $file_get_name)
    {
        $connectionString = "DefaultEndpointsProtocol=https;AccountName=armanwebapp;AccountKey=w7nelSUskJiDheADIcyIEkufPSKBWTBuPARQI9vHqUPe73JqfnQTdDem28a9phMishXDmzKAL++S+NlNUk5qvw==";

        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($connectionString);

        $createContainerOptions = new CreateContainerOptions();
     
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

        // Set container metadata.
        $createContainerOptions->addMetaData("key1", "value1");
        $createContainerOptions->addMetaData("key2", "value2");

          $containerName = "blockblobs".generateRandomString();

        try {
            // Create container.
            $blobClient->createContainer($containerName, $createContainerOptions);

            // Getting local file so that we can upload it to Azure
            $myfile = fopen($file_name, "r") or die("Unable to open file!");
            fclose($myfile);
            
            # Upload file as a block blob
            // echo "Uploading BlockBlob: ".PHP_EOL;
            // echo $file_name;
            // echo "<br />";
            
            $content = fopen($file_name, "r");

            //Upload blob
            $blobClient->createBlockBlob($containerName, $file_name, $content);

            // List blobs.
            $listBlobsOptions = new ListBlobsOptions();
            $listBlobsOptions->setPrefix($file_get_name);

            // echo "These are the blobs present in the container: ";

            do{
                $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
                foreach ($result->getBlobs() as $blob)
                {
                    $linkURL = $blob->getUrl();
                    sendURL($linkURL);
                    // echo "<img src='" .$blob->getUrl() . "' /> <br />";
                }
            
                $listBlobsOptions->setContinuationToken($result->getContinuationToken());
            } while($result->getContinuationToken());
            // echo "<br />";

            // Get blob.
            // echo "This is the content of the blob uploaded: ";
            // $blob = $blobClient->getBlob($containerName, $file_name);
            // fpassthru($blob->getContentStream());
            // echo "<br />";
        }
        catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
        catch(InvalidArgumentTypeException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }

        return $linkURL;
    }

    
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

    <script type="text/javascript">
        function processImage() {
            var subscriptionKey = "eb3427a18a864197a229a27ee6c0ba57";
     
            var uriBase = "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
     
            // Request parameters.
            var params = {
                "visualFeatures": "Categories,Description,Color",
                "details": "",
                "language": "en",
            };
     
            // Display the image.
            // var sourceImageUrl = document.getElementById("inputImage").value;
            // document.querySelector("#sourceImage").src = sourceImageUrl;
     
            // Make the REST API call.
            $.ajax({
                url: uriBase + "?" + $.param(params),
     
                // Request headers.
                beforeSend: function(xhrObj){
                    xhrObj.setRequestHeader("Content-Type","application/json");
                    xhrObj.setRequestHeader(
                        "Ocp-Apim-Subscription-Key", subscriptionKey);
                },
     
                type: "POST",
     
                // Request body.
                data: '{"url": http://upload.wikimedia.org/wikipedia/commons/3/3c/Shaki_waterfall.jpg"}',
            })
     
            .done(function(data) {
                // Show formatted JSON on webpage.
                $("#responseTextArea").val(JSON.stringify(data, null, 2));
            })
     
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                var errorString = (errorThrown === "") ? "Error. " :
                    errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                    jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
        };
    </script>
    
    <h1>Upload Image Success</h1>
    <h1>Analyze image:</h1>
    Click the <strong>Analyze image</strong> button.
    <br><br>
    Image to analyze:

    <?php

        function sendURL($linkURL)
        {
            echo '<input type="hidden" name="inputImage" id="inputImage" value="' . $linkURL . '" />';

        }
    ?>

    <button onclick="processImage()">Analyze image</button>
    <br><br>
    <div id="wrapper" style="width:1020px; display:table;">
        <div id="jsonOutput" style="width:600px; display:table-cell;">
            Response:
            <br><br>
            <textarea id="responseTextArea" class="UIInput"
                      style="width:580px; height:400px;"></textarea>
        </div>
        <div id="imageDiv" style="width:420px; display:table-cell;">
            Source image:
            <br><br>
            <img id="sourceImage" width="400" />
        </div>
    </div>

</body>
</html>