<?php
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_fee/assets/css/fee.css');
$document->addScript('components/com_fee/assets/js/function.js');


?>
<div class="container">
    
    <h1>Tải lên tệp sinh viên</h1>
    <form role="form" action="#" method="post" enctype="multipart/form-data" onsubmit="return doUpload();">
        <div class="form-group">
            <label for="myfile">File Upload</label>
            <input type="file" class="form-control" name="myfile" id="myfile" required="true">
        </div>	  
        <input type="submit" class="btn btn-default" value="Upload" />
        <input type="button" class="btn btn-default" value="Cancle" onclick="cancleUpload();"/>
        
    </form>
    <hr>
    <div id="progress-group">
    </div>
</div>