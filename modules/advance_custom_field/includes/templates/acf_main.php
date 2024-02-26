<div class="col-md-6">

                    <!-- START SIDEBAR -->
                    <div id="mySidebar_cf" class="sidebar_cf ">

                        <a href="javascript:void(0)" id="closebtn"  class="closebtn" onclick="closeNav()">Save & Publish</a>

                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#mainTab">Elements</a></li>
                          <li><a data-toggle="tab" id="setting_a" href="#settingTab">Settings</a></li>
						  <li><a data-toggle="tab" id="flow_tab" href="#flowTab">Flows</a></li>
                          <!-- <li><a data-toggle="tab" href="#style">Style</a></li> -->
                        </ul>
                        <div id="dynamicFieldsPopulation"></div>

                 <!--        <div class="col-md-12 draggable">
                            <div class="form-group" app-field-wrapper="proposal_to">
                                <label for="proposal_to" class="control-label"> <small class="req text-danger">* </small>Field# large</label>
                                <input type="text" id="proposal_to" name="proposal_to" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-md-6 draggable">
                            <div class="form-group" app-field-wrapper="proposal_to">
                                <label for="proposal_to" class="control-label"> <small class="req text-danger">* </small>Field# Medium</label>
                                <input type="text" id="proposal_to" name="proposal_to" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6"></div> -->

                        <div class="col-md-12 hrlineMargin"><hr class="text-white"></div>

                        <div class="tab-content">

                            <!-- TAB 1 START -->
                            <div id="mainTab" class="col-md-12 tab-pane fade in active">
                                <div class="sidebar-responsive-panel">
                                   
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('text');">
                                        <div class="" > Text Field</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('number');">
                                        <div class=""> Number Field</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('textarea');">
                                        <div class=""> Text Area</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('dropdown');">
                                        <div class=""> DropDown</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('checkbox');">
                                        <div class=""> Checkbox</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('radio');">
                                        <div class=""> Radio button</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('checkbox_multi');">
                                        <div class=""> Multi Checkbox</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('date');">
                                        <div class=""> Date</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('file');">
                                        <div class=""> File Picker</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('table');">
                                        <div class=""> HTML Table</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('button');">
                                        <div class=""> Button / Text</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('icon');">
                                        <div class=""> Icons</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('fileviewer_img');">
                                        <div class=""> Image Viewer</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('fileviewer_pdf');">
                                        <div class=""> PDF Viewer</div>
                                    </div>
                                    <!-- <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('fileviewer_doc');">
                                        <div class=""> Word Viewer</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('fileviewer_xls');">
                                        <div class=""> Excel Viewer</div>
                                    </div> -->
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('chart');">
                                        <div class=""> Chart</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('progress_bar');">
                                        <div class=""> Progress bar</div>
                                    </div>
                                    <!-- <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('map');">
                                        <div class=""> Map</div>
                                    </div>
                                    <div class="inner-responsive-panel" onclick="dynamicFieldsPopulation('esign');">
                                        <div class=""> E-sign</div>
                                    </div> -->
                                    
                                </div>
                            </div>
                            <!-- TAB 1 END -->

                            <!-- TAB 2 START -->
                            <div id="settingTab" class="col-md-12 tab-pane fade in ">
                            </div>
                            <!-- TAB 2 END -->
							                            <!-- Flow TAB  START -->
                            <div id="flowTab" class="col-md-12 tab-pane fade in mtop10">
                            <select id="flow_list" class="form-control">
                                <option></option>
                            </select>
                            <h4>Create Flow</h4>
                            <form id="myForm">
                              <div class="row">
                                <div class="col-md-12">
                                    <label for="SetFlows" class="control-label"><b class="req text-danger"></b>Name</label>
                                    <input type="text" name="name" class="form-control" id="flow_name">
                                </div>
                            <div class="col-md-12 mtop15">
                                <button class="btn btn-primary mtop10" type="submit">Save</button>
                            </div>
                                </div>
                            </form>
                            <table border="1" id="responseTable" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            </table>
                            </div>
                            <!--Flow TAB  END -->
                            <!-- TAB 3 START -->
                            <div id="style" class="col-md-12 tab-pane fade in ">
                                <div class="sidebar-responsive-panel">
                                   
                                    <div class="inner-responsive-panel">
                                        <div class="text-white"> Background</div>
                                    </div>
                                    <div class="inner-responsive-panel">
                                        <div class="text-white"> Border</div>
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- TAB 3 END -->

                        </div>
                    <!-- END TAB CONTENT -->

                    </div>
                    <!-- END SIDEBAR -->

                    <div id="main_cf">
                      <button class="openbtn"  onclick="openNav()">☰ Add New Field</button>  
                    </div>
                </div>
<style>
#flow_tab{
  display: none;
}
#name-error{
  color: red;
}
#mySidebar_cf ul.nav.nav-tabs li a {
	color: #fff;
}

#mySidebar_cf ul.nav.nav-tabs li.active a,
#mySidebar_cf ul.nav.nav-tabs li a:hover {
	border-bottom: solid 1px #fff;
	border-radius: 0;
}

#flowTab h4 {
	color: #fff;
	margin-top: 40px;
	font-size: 1rem;
	font-weight: bold;
}

#flowTab table tr th,
#flowTab table tr td {
	color: #fff;
}

#flowTab table tr th a,
#flowTab table tr td a {
	padding: 0px;
  	color:#fff;
}
.edit-btn {
    background: white;
    text-align: center;
    padding: 19px;
    height: 28px;
    display: block;
    border-radius: 3px;
    line-height: 28px;
    font-size: 13px;
    color: #a528d5 !important;
    font-weight: bold;
  }

#flowTab table tr th {
	border-top: solid 1px #fff;
}
#mySidebar_cf .nav-tabs{
   background: transparent !important
 }
.sidebar_cf .closebtn{
   background:white;
  }
.proposal_button_custom {
	color:#fff;
 }
</style>