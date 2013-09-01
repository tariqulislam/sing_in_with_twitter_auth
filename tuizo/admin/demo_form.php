<?php
include ('../config/config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin panel: ... create </title>

        <link href="css/main.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />
        <script src="js/jquery-1.4.4.js" type="text/javascript"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload, editor -->
        <script type="text/javascript" src="js/spinner/ui.spinner.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>  
        <script type="text/javascript" src="js/fileManager/elfinder.min.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="js/wysiwyg/jquery.wysiwyg.js"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="js/wysiwyg/wysiwyg.image.js"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="js/wysiwyg/wysiwyg.link.js"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="js/wysiwyg/wysiwyg.table.js"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="js/dataTables/jquery.dataTables.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="js/dataTables/colResizable.min.js"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="js/forms/forms.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="js/forms/autogrowtextarea.js"></script>
        <!--Effect on left error menu, top message menu, File upload -->
        <script type="text/javascript" src="js/forms/autotab.js"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="js/forms/jquery.validationEngine.js"></script>
        <!--Effect on left error menu, top message menu-->
        <script type="text/javascript" src="js/colorPicker/colorpicker.js"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="js/uploader/plupload.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="js/uploader/plupload.html5.js"></script>
        <!--Effect on file upload-->
        <script type="text/javascript" src="js/uploader/plupload.html4.js"></script>
        <!--No effect-->
        <script type="text/javascript" src="js/uploader/jquery.plupload.queue.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="js/ui/jquery.tipsy.js"></script>
        <!--Effect on left error menu, top message menu,  -->
        <script type="text/javascript" src="js/jBreadCrumb.1.1.js"></script>
        <!--Effect on left error menu, File upload -->
        <script type="text/javascript" src="js/cal.min.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="js/jquery.collapsible.min.js"></script>
        <!--Effect on left error menu, File upload -->
        <script type="text/javascript" src="js/jquery.ToTop.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="js/jquery.listnav.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="js/jquery.sourcerer.js"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="js/custom.js"></script>
        <!--Effect on left error menu, top message menu, body-->

    </head>

    <body>

        <?php include 'top_navigation.php'; ?>

        <?php include 'module_link.php'; ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include 'left_navigation.php'; ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Dashboard</h5></div>

                <!-- Notification messages -->
                <?php include 'message.php'; ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Content</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Text fields</h5></div>
                                        <div class="rowElem noborder"><label>Usual input text:</label><div class="formRight"><input type="text" name="inputtext"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Input password:</label><div class="formRight"><input type="password" /></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Input with placeholder:</label><div class="formRight"><input type="text" name="inputtext" placeholder="enter your placeholder text here"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Input with autofocus:</label><div class="formRight"><input type="text" name="inputtext" class="autoF"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Read only field:</label><div class="formRight"><input type="text" readonly="readonly" value="Read only text goes here" name="inputtext"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Input with tooltip:</label><div class="formRight"><input type="text" name="inputtext" class="rightDir" title="Cool, huh?" /></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Disabled input field:</label><div class="formRight"><input type="text" disabled="disabled" value="Disabled field" name="inputtext"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>With predefined value:</label><div class="formRight"><input type="text" value="http://" name="inputtext"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>With max length:</label><div class="formRight"><input type="text" maxlength="20" placeholder="max 20 characters here" name="inputtext"/></div><div class="fix"></div></div>

                                        <div class="rowElem"><label>Usual textarea:</label><div class="formRight"><textarea rows="8" cols="" name="textarea"></textarea></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Autogrowing textarea:</label><div class="formRight"><textarea rows="8" cols="" class="auto" name="textarea"></textarea></div><div class="fix"></div></div>
                                        <input type="submit" value="Submit form" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>
                                </fieldset>




                                <!-- Dropdowns and selects -->
                                <fieldset>
                                    <div class="widget">    
                                        <div class="head"><h5 class="iCoverflow">Drowpdowns and selects</h5></div>

                                        <div class="rowElem noborder">
                                            <label>Select without scroll :</label>
                                            <div class="formRight">                        
                                                <select name="select">
                                                    <option value="">1</option>
                                                    <option value="opt1">2</option>
                                                </select>
                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <div class="rowElem">
                                            <label>Select with scrolling :</label>
                                            <div class="formRight">
                                                <select name="select2" >
                                                    <option value="opt1">Usual select with scrolling</option>
                                                    <option value="opt2">Option 2</option>
                                                    <option value="opt3">Option 3</option>
                                                    <option value="opt4">Option 4</option>
                                                    <option value="opt5">Option 5</option>
                                                    <option value="opt6">Option 6</option>
                                                    <option value="opt7">Option 7</option>
                                                    <option value="opt8">Option 8</option>
                                                </select>
                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <div class="rowElem">
                                            <label>Usual multiple select :</label>
                                            <div class="formRight">

                                                <select multiple="multiple" class="multiple" title="Click to Select a City">
                                                    <option selected="selected">Amsterdam</option>      
                                                    <option selected="selected">Atlanta</option>
                                                    <option>Baltimore</option>
                                                    <option>Boston</option>
                                                    <option>Buenos Aires</option>
                                                    <option>Calgary</option>
                                                    <option selected="selected">Chicago</option>
                                                    <option>Denver</option>
                                                    <option>Dubai</option>
                                                    <option>Frankfurt</option>
                                                    <option>Hong Kong</option>
                                                    <option>Honolulu</option>
                                                    <option>Houston</option>
                                                    <option>Kuala Lumpur</option>
                                                    <option>London</option>
                                                    <option>Los Angeles</option>
                                                    <option>Melbourne</option>
                                                    <option>Mexico City</option>
                                                    <option>Miami</option>
                                                    <option>Minneapolis</option>
                                                    <option>Montreal</option>
                                                    <option>New York City</option>
                                                    <option>Paris</option>
                                                    <option>Philadelphia</option>
                                                    <option>Rotterdam</option>
                                                    <option>San Diego</option>
                                                    <option>San Francisco</option>
                                                    <option>Sao Paulo</option>
                                                    <option>Seattle</option>
                                                    <option>Seoul</option>
                                                    <option>Shanghai</option>
                                                    <option>Singapore</option>
                                                    <option>Sydney</option>
                                                    <option>Tokyo</option>
                                                    <option>Toronto</option>
                                                    <option>Vancouver</option>
                                                </select>

                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <div class="rowElem"><label>Simple numbers input:</label><div class="formRight"><input type="text" id="s1" value="10" /></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Decimal:</label><div class="formRight"><input type="text" id="s2" value="10.25" /></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Currency:</label><div class="formRight"><input type="text" id="s3" /></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Inline data:</label>
                                            <div class="formRight">
                                                <ul id="s4">
                                                    <li>item 1</li>
                                                    <li>item 2</li>
                                                    <li>item 3</li>
                                                    <li>item 4</li>
                                                    <li>item 5</li>
                                                    <li>item 6</li>
                                                    <li>item 7</li>
                                                    <li>item 8</li>
                                                    <li>item 9</li>
                                                    <li>item 10</li>
                                                </ul>
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                        <div class="rowElem"><label>Inline data (links):</label><div class="formRight"><div id="s5"></div></div><div class="fix"></div></div>
                                    </div>
                                </fieldset>

                                <!-- Checkboxes and radios -->
                                <fieldset>
                                    <div class="widget">    
                                        <div class="head"><h5 class="iRecord">Checkbox and radio</h5></div>
                                        <div class="rowElem noborder">
                                            <label>Checkbox: </label>
                                            <div class="formRight">
                                                <input type="checkbox" id="check1" name="chbox" checked="checked" /><label>Checkbox checked</label>
                                                <input type="checkbox" id="check2" name="chbox" /><label>Checkbox</label>
                                                <input type="checkbox" id="check3" disabled="disabled" name="chbox" /><label class="itemDisabled">Disabled</label>
                                                <input type="checkbox" id="check4" disabled="disabled" checked="checked" name="chbox" /><label class="itemDisabled">Disabled checked</label>
                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <div class="rowElem">
                                            <label>Radio :</label> 
                                            <div class="formRight">
                                                <input type="radio" name="question1" checked="checked" /><label>Selected radio</label>
                                                <input type="radio" name="question1" /><label>Normal state</label>
                                                <input type="radio" name="question" disabled="disabled" /><label class="itemDisabled">Disabled</label>
                                                <input type="radio" name="question" disabled="disabled" checked="checked" /><label class="itemDisabled">Disabled checked</label>
                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                    </div>
                                </fieldset>

                                <!-- File upload -->
                                <fieldset>
                                    <div class="widget">    
                                        <div class="head"><h5 class="iUpload">File upload</h5></div>
                                        <div id="uploader">You browser doesn't have HTML 4 support.</div>                    
                                    </div>
                                </fieldset>

                                <!-- WYSIWYG editor -->
                                <fieldset>
                                    <div class="widget">    
                                        <div class="head"><h5 class="iPencil">WYSIWYG editor</h5></div>
                                        <textarea class="wysiwyg" rows="5" cols=""></textarea>                
                                    </div>
                                </fieldset>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>

        <?php include 'footer.php'; ?>
