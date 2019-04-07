<?php

if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Bintang Submision 2</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
	<script type="text/javascript">
		$(document).ready(function () {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************

        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "dcb73a408ce5455ca4cc5043ee853e15";

        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
        "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";

        // Request parameters.
        var params = {
        	"visualFeatures": "Categories,Description,Color",
        	"details": "",
        	"language": "en",
        };

        // Display the image.
        var sourceImageUrl = "<?php echo $url ?>";
        document.querySelector("#sourceImage").src = sourceImageUrl;

        // Make the REST API call.
        $.ajax({
        	url: uriBase + "?" + $.param(params),

            // Request headers.
            beforeSend: function(xhrObj){
            	xhrObj.setRequestHeader("Content-Type","application/json");
            	xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
            },

            type: "POST",

            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })

        .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
            $("#deskripsi").text(data.description.captions[0].text);
        })

        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
            errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
            jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    });
</script>

	<div id="imageDiv" style="width:420px; display:table-cell;">
		<br><br>
		<img id="sourceImage" width="400" />
		<br>
		<h3 id="deskripsi">Loading . . .</h3>
	</div>
</body>
</html>
