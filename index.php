<?php

require_once 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$conn="DefaultEndpointsProtocol=https;AccountName=bintangwebapp2;AccountKey=Vgmc9ahnHtX3COcrWyRm+8Dn0JL9Lv68Na+T8z4sxNnGdq2At8LIhFP1P14yJclLwwlB9UuMP0C18xuWzkZUPA==";

$containerName = "bintang";

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($conn);

if (isset($_POST['submit'])) {
	$fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: index.php");
}

$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");

$result = $blobClient->listBlobs($containerName, $listBlobsOptions);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Bintang Azure submision 2</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
	<div class="container mt-4">

		<h1 class="text-center">Bintang Azure submision 2</h1>
		
<div class="row">
    <div class="col">        
        <form action="index.php" enctype="multipart/form-data" method="post" class="form-inline">
           <div class="input-group mb-3">                
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
                    <label class="custom-file-label" for="fileToUpload">Choose file</label>
                </div>
                <div class="input-group-append">  
				<input type="submit" name="submit" value="Upload" class="btn btn-primary">
				
                </div>
            </div>            
        </form>        
    </div>
    <div>

    </div>
</div>


		<table class='table table-hover'>
				
				<?php

				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
        <thead>

						<tr>
							<td>
							<img src="<?php echo $blob->getUrl() ?>"  height="150" width="150"/>
			                <p class="card-text">File Name : <?php echo $blob->getName() ?></p>
							
							<form action="proses.php" method="post">
							<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
							<input type="submit" name="submit" value="Analyze!" class="btn btn-secondary">
								</form>
							</td>
						</tr>
        </thead>

						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());

				?>
		
                </div>
            </div> 
		</table>

	</div>
</body>

</html>