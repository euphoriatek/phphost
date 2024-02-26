<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<style>
.upload-blog input {
    opacity: 0;
    position: absolute;
    width: 100%;
    left: 0;
    top: 0;
    height: 100%;
}
img{
    width:100%;
}
.upload-blog {
    background: #e7e7e7;
    position: relative;
    max-width: 240px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 2rem;
    color: #adadad;
    border-radius: 30px;
    border-style: dashed;
    border-width: 1px;
    border-color: #000;
    margin: 10px auto;
}
.loader-main {
    position: fixed;
    background: #fffffff0;
    width: 100%;
    height: 100%;
    top: 0;
    display: none; 
}
.loader {
    border: 10px solid #f3f3f3;
    border-top: 10px solid #3498db;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    animation: spin 2s linear infinite;
    position: fixed;
    left: 41%;
    transform: translate(-50% ,0%);
    top: 40px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.flash-message {
     display: none; 
    position: absolute;
    top: 0;
    width: 95%;
    transform: translate(-50%, 7px);
    left: 50%;
    z-index:99;
}

</style>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <form action="">
              <div class="upload-blog">
                <input type="file" id="myFile" name="filename" class="form-control" onchange="fileSelected(event)">  
                Upload
              </div>
              <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
        </form>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row" id="image-preview-invoice"></div>
  </div>

  <div class="loader-main"><div class="loader"></div></div>
  <div id="flash-message" class="flash-message alert">
  </div>

  <script>
    function fileSelected(event) {
      var fd = new FormData();
      const file = event.target.files[0];
      var invoice_id = $('#id').val();
      fd.append('invoice_id', invoice_id);
      fd.append('files', file);
      if (file) {
        // Show loader
        $(".loader-main").show();

        // Send AJAX request
        $.ajax({
            url: "https://app.graphiteartistries.com/fileupload/upload_item_image",
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                var responseData = JSON.parse(response);
                if(responseData.random_number != 0){
                  $("#flash-message").html(responseData.success);
                  $("#flash-message").addClass("alert-success");
                  $(".loader-main").hide();
              	  $(".flash-message").fadeIn().delay(3000).fadeOut();
                  var previewContainer = document.getElementById('image-preview-invoice');
              // Create a div container to hold all images
              var imagesContainer = document.createElement('div');
              imagesContainer.className = 'col-sm-2 col-xs-6 img-blog';
              imagesContainer.style.whiteSpace = 'nowrap'; // Ensure horizontal alignment

                  var reader = new FileReader();
                  var allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

                  // Check if the file type is allowed
                  if (allowedTypes.includes(file.type)) {
                      reader.onload = function(event) {
                          var imageUrl = event.target.result;
                          var image = new Image();
                          image.src = imageUrl;
                          image.alt = 'Image Preview';
                          image.className = 'preview-image'; // Apply CSS class for styling
                          image.style.marginRight = '10px'; // Add spacing between images
                          image.onclick = function() {
                              // openModal1(imageUrl);
                          };

                          // Append the image to the images container
                          imagesContainer.appendChild(image);
                      };
                      reader.readAsDataURL(file);
                  }
              // Append the images container to the preview container
              previewContainer.appendChild(imagesContainer);
                } else {
                  $("#flash-message").html(responseData.error);
                  $("#flash-message").addClass("alert-error");
                  $(".loader-main").hide();
              	  $(".flash-message").fadeIn().delay(3000).fadeOut();
                    // Handle file upload failure
                    // alert('File not uploaded');
                }
            },
            error: function(xhr, status, error) {
                // Hide loader
                $(".loader").hide();
                ("#flash-message").html(error);
              $("#flash-message").addClass("alert-error");
              $(".loader-main").hide();
          	  $(".flash-message").fadeIn().delay(3000).fadeOut();
                // Show error message
                // alert('Error: ' + error);
            }
        });
      }
    }
  </script>
</body>
</html>
