<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_fee/assets/css/fee.css');
$document->addScript('components/com_fee/assets/js/function.js');
?>
<div class="container">

    <h1>Tải lên tệp sinh viên</h1>
    <form action="#" method="post" enctype="multipart/form-data" name="adminForm" id="fee-form" class="form-validate" onsubmit="return doUpload();">
        <div class="form-horizontal">
            <div class="row-fluid">
                <div class="form-horizontal">
                    <fieldset class="adminform">
                        <div class="form-group">
                            <label for="myfile">File Upload</label>
                            <input type="file" class="form-control" name="myfile" id="myfile" required="true">
                        </div>	  
                        <input type="submit" class="btn btn-default" value="Upload" />
                        <input type="button" class="btn btn-default" value="Cancle" onclick="cancleUpload();"/>
                    </fieldset>
                </div>
            </div>
        </div>
    </form>
    <hr>
    <div id="progress-group">
    </div>
    <div id="progress-insert">
    </div>
    <div id="insert-data">
        <div id="insert-button" class="span2 pull-right"></div>
    </div>
    
    <div id='error' class='error_data'>
        
    </div>

    <div id="invalid" class="invalid-data">
    </div>

    <div id="student" class="student">
    </div>
</div>