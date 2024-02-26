$(function() {
    activeDraggingFunctions();

});

function activeDraggingFunctions(){
    $( ".dropp_able" ).sortable();
    $( ".dropp_able" ).disableSelection();

    $( ".draggable" ).draggable({
        start: function(event, ui) {
            $(".deleted_area,.droppableArea").show();
            ui.helper.addClass('dragging_active');
        },
        stop: function(event, ui) {
            ui.helper.removeClass('dragging_active');
            $(".deleted_area,.droppableArea").hide();
            publishFields();
			var flow_list = $("#flow_list").val();
            var matchingObject = hold_flows_data.find(function(obj) {
                return obj.id === flow_list;
            });
            console.log(matchingObject);
            if (matchingObject.condition) {
                var Element_filed = document.getElementsByClassName(randomID);
                if (Element_filed[0]) {
                if (matchingObject.condition == "click") {
                    // console.log(Element_filed);
                    Element_filed[0].classList.add("click_button");
                    // Element_filed[0].onclick = function(){
                    //     Perform_Action();
                    // }
                }else if(matchingObject.condition == "hover"){
                    // console.log(Element_filed);
                    Element_filed[0].classList.add("hover_button");
                    // Element_filed[0].hover = function(){
                    //     Perform_Action();
                    // }
                }
                }
            }
            
            if (flow_list && randomID) {
            $.ajax({
                type: 'POST',
                url: 'https://youribizerp.com/admin/acf_flows/create_flows_fields',
                data: JSON.stringify({
                    flow_id: flow_list,
                    ref_id: randomID,
                }),
                processData: false,
                contentType: false, 
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
            }
        },
        connectToSortable: ".dropp_able",
        // helper: "clone",
        revert: "invalid"
    });

    // zeeshan
  $( ".deleted_area" ).droppable({
        // classes: {
        //     "ui-droppable-hover": "ui-state-hover"
        // },
        drop: function(event, ui){
            // $( this ).addClass( "ui-state-highlight" );
            $(this).find('.draggable').remove();
            $(this).find('.dynamicFieldsPopulation').empty();
            $(ui.draggable).remove();
        }
    });
    // zeeshan

    $( ".dropp_able" ).droppable({
        classes: {
            "ui-droppable-hover": "ui-state-hover"
        },
        drop: function(event, ui){

            // $( this ).addClass( "ui-state-highlight" );
            $(".draggable").removeAttr("style");

            var checkTable = $(".draggable").hasClass("tableBox");
            var restrictDuplication = 0;
            var cellthVal = "";
            var celltdVal = "";
            if(checkTable == true){

                $('.custom_table tr').each(function(){
                    $(this).find('th').each(function(){
                        cellthVal = $(this).find('input').val();
                        $(this).html(cellthVal);
                    })
                    $(this).find('td').each(function(){
                        celltdVal = $(this).find('input').val();
                        $(this).html(celltdVal);
                        
                    })
                })

                // for (var i = 0, cell; cell = table.cells[i]; i++) {
                //     console.log(cell);
                // }
            }


        }
    });

    // Publish part functioning
    saveToPublished();
    // Publish part functioning

}

function textFieldSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('input').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('input').prop('required',false);
    }

}
function numberFieldSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('input').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('input').prop('required',false);
    }

}
function textboxFieldSetting(setting="", vall = "col-md-12"){
    // console.log(vall);
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('textarea').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('textarea').prop('required',false);
    }

}
function selectboxFieldSetting(setting="", vall = "col-md-12"){
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('select').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('select').prop('required',false);
    }

}
function checkboxFieldSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('input').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('input').prop('required',false);
    }

}
function radioFieldSetting(setting="", vall = "col-md-12"){
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('input').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('input').prop('required',false);
    }

}
function cb_multiFieldSetting(setting="", vall = "col-md-12"){
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('input').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('input').prop('required',false);
    }

}
function dateFieldSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('label').text(vall);

    }
    else if(setting == "required")
    {
        if ($('#required').is(":checked"))
            $("#dynamicFieldsPopulation").find('input').prop('required',true);
        else
            $("#dynamicFieldsPopulation").find('input').prop('required',false);
    }

}
function tableSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "heading")
    {
        $("#dynamicFieldsPopulation").find('h4').text(vall);

    }
    // else if(setting == "table_row")
    // {
    //     var tbodyy ="";
    //     $("#dynamicFieldsPopulation").find("table tbody").empty();
    //     for (var i = 1; i <= vall; i++) {
    //         console.log("===> "+i);
    //         var tbodyy = `
    //             <tr class="r`+i+`">
    //                 <td><input type="text" value="r`+i+`"></td>
    //                 <td><input type="text" value="r`+i+`"></td>
    //                 <td><input type="text" value="r`+i+`"></td>
    //             </tr>
    //         `;
    //         $("#dynamicFieldsPopulation").find("table tbody").append(tbodyy);
    //     }
    // }
    else if(setting == "table_rc"){
        var thead ="";
        var tbodyy ="";
        $("#dynamicFieldsPopulation").find("table thead").empty();
        $("#dynamicFieldsPopulation").find("table tbody").empty();
        var rows = $("#settingTab").find("#table_row").val();
        var cols = $("#settingTab").find("#table_col").val();
        for (var i = 1; i <= rows; i++) {

            if(i == 1)
                thead += '<tr class="r'+i+'">';

            tbodyy += '<tr class="r'+i+'">';
            for (var j = 1; j <= cols; j++) {
                if(i == 1)
                    thead += '<th><input type="text" value="" placeholder="TH'+j+'"></th>';

                tbodyy += '<td><input type="text" value="" placeholder="R'+i+'C'+j+'"></td>';
            }
            if(i == 1)
                thead += '</tr>';

            tbodyy += '</tr>';
        }
            $("#dynamicFieldsPopulation").find("table thead").html(thead);
            $("#dynamicFieldsPopulation").find("table tbody").html(tbodyy);

    }

}

function button_textFieldSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('a').text(vall);

    }
    else if(setting == "link")
    {
        $("#dynamicFieldsPopulation").find('a').attr("href", vall);
    }
    else if(setting == "target")
    {
        $("#dynamicFieldsPopulation").find('a').attr("target", "_"+vall);
    }

}
function iconFieldSetting(setting="", vall = "col-md-12"){
    if (vall) {
        $("#flow_name").val(vall);
    }
    if(setting == "sizing")
    {
        if(vall == "col-md-12"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-6");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-12");
        }else if(vall == "col-md-6"){
            $("#dynamicFieldsPopulation").find(".draggable").removeClass("col-md-12");
            $("#dynamicFieldsPopulation").find(".draggable").addClass("col-md-6");
        }
    }
    else if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('a').find('i').text(vall);

    }
    else if(setting == "link")
    {
        $("#dynamicFieldsPopulation").find('a').attr("href", vall);
    }
    else if(setting == "target")
    {
        $("#dynamicFieldsPopulation").find('a').attr("target", "_"+vall);
    }

}
function fileviewer_imgSetting(setting="", vall = "col-md-12"){
    if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('a').find('i').text(vall);

    }
    else if(setting == "link")
    {
        $("#dynamicFieldsPopulation").find('a').attr("href", vall);
    }
    else if(setting == "target")
    {
        $("#dynamicFieldsPopulation").find('a').attr("target", "_"+vall);
    }
    else if(setting == "img")
    {
        var preview = $(".fileViewer_img").find('img');
        // var preview = document.querySelector('img');
        var file    = vall.files[0];
        var ext = vall.files[0]['name'].substring(vall.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
        var reader  = new FileReader();
        console.log(reader);

        reader.onloadend = function () {
            console.log(typeof(reader.result));
            preview.attr("src", reader.result);
            // preview.src = reader.result;
        }

        if (file && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {

            reader.readAsDataURL(file);
        } else {
            alert("file extention '"+ext+"' error. try to upload 'png, jpg, jpeg, png, gif' ");
            preview.src = "";
        }
        
    }
}
function fileviewer_pdfSetting(setting="", vall = "col-md-12"){
    if(setting == "label")
    {
        $("#dynamicFieldsPopulation").find('a').find('i').text(vall);

    }
    else if(setting == "link")
    {
        $("#dynamicFieldsPopulation").find('a').attr("href", vall);
    }
    else if(setting == "target")
    {
        $("#dynamicFieldsPopulation").find('a').attr("target", "_"+vall);
    }
    else if(setting == "img")
    {
        var preview = $(".fileViewer_pdf").find('embed');
        // var preview = document.querySelector('img');
        var file    = vall.files[0];
        var ext = vall.files[0]['name'].substring(vall.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
        var reader  = new FileReader();
        console.log(reader);

        reader.onloadend = function () {
            preview.attr("src", reader.result);
            // preview.src = reader.result;
        }

        if (file && (ext == "pdf" )) {
            reader.readAsDataURL(file);
        } else {
            alert("file extention '"+ext+"' error. try to upload 'pdf' ");
            preview.src = "";
        }
        
    }
}

   // ____  BAR CHART ___________
    function initializeChart(heading = "Organization", chartData = ""){
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(function() { drawBarChart(heading,chartData); });
    }

    function drawBarChart(heading,chartData) {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Users", { role: "style" } ],
        ["Organization1", 8.94, "#b87333"],
        ["Organization2", 10.49, "silver"],
        ["Organization3", 19.30, "gold"],
        ["Organization4", 21.45, "color: #e5e4e2"]
      ]);
      // var data = google.visualization.arrayToDataTable(
      //   chartData
      // );
      if(chartData != "")
        var data = chartData;

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: heading,
        width: 400,
        height: 300,
        bar: {groupWidth: "50%"},
        legend: { position: "none" },
        bars: 'vertical' 
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }
    
    function chartSetting(setting="", vall = "col-md-12")
    {
        if (vall) {
        $("#flow_name").val(vall);
        }
        if(setting == "heading")
        {
            $("#barchart_values").empty();
            initializeChart(vall);
        }
    }

    // ____  PROGRESS BAR CHART ___________
    
    function initializeProgressChart(heading = "Organization", chartData = ""){
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(function() { drawProgressChart(heading,chartData); });
    }

    function drawProgressChart(heading,chartData) {
      var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);
      if(chartData != "")
        var data = chartData;

      var view = new google.visualization.DataView(data);

      var options = {
        title: heading,
        width: 400,
        height: 300,
        pieHole: 0.4,
      };
      var chart = new google.visualization.PieChart(document.getElementById("progress_bar"));
      chart.draw(view, options);
  }

function progresschartSetting(setting="", vall = "col-md-12")
{
    if (vall) {
     $("#flow_name").val(vall);
    }
    if(setting == "heading")
    {
        $("#progress_bar").empty();
        initializeProgressChart(vall);
    }
}

// ____  GOOGLE MAP ___________

let map;

async function initAcfMap() {
  const position = { lat: -25.344, lng: 131.031 };
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  map = new Map(document.getElementById("ACF_map"), {
    zoom: 4,
    center: position,
    mapId: "DEMO_MAP_ID",
  });

  const marker = new AdvancedMarkerElement({
    map: map,
    position: position,
    title: "Uluru",
  });
}

function esignSetting(setting="", vall = "col-md-12"){
    if(setting == "heading")
    {
        $("#dynamicFieldsPopulation").find('h4').text(vall);

    }

}

function addMuliOption(){
    var optionHtml = "";
    optionHtml =`
    <div class=" dropdown-options" >
        <!-- LABEL -->
        <div class="col-md-10 ">
            <div class="form-group" app-field-wrapper="dropdown-label">
                <input type="text" class="form-control dropdown-label" value="" placeholder="Label" >
            </div>
        </div>
        <div class="col-md-2 ">
            <i class="fa fa-trash fa-lg dd_trash text-danger" onclick="dd_trash(this);"></i>
        </div>

        <!-- VALUE -->
        <div class="col-md-10 ">
            <div class="form-group" app-field-wrapper="dropdown-value">
                <input type="text" class="form-control dropdown-value" value="" placeholder="Value" >
            </div>
        </div>
    </div>
    `;

    $(".dropdown-options-main").append(optionHtml);

}
function addMuliOptionData(checkField = ""){
    if(checkField == "dropdown"){
        $("#dynamicFieldsPopulation").find('select').empty();
        $(".dropdown-options").each(function(){
            var label = $(this).find(".dropdown-label").val();
            var value = $(this).find(".dropdown-value").val();
            var ddHtml = "";
            ddHtml =`
            <>
            `;
            $("#dynamicFieldsPopulation").find('select').append( '<option value="'+value+'">'+label+'</option>' );
        });
    }

    if(checkField == "radio"){
        $("#dynamicFieldsPopulation").find('.draggable').empty();

        $(".dropdown-options").each(function(){
            var label = $(this).find(".dropdown-label").val();
            var value = $(this).find(".dropdown-value").val();
            var ddHtml = "";
            ddHtml =`
            <>
            `;
            var htmlRadio = `
            <div class="form-group" app-field-wrapper="proposal_radio_custom">
                <label for="proposal_radio_custom" class="control-label"> <small class="req text-danger">* </small>`+label+`</label>
                <input type="radio" id="proposal_radio_custom" name="proposal_radio_custom" value="`+value+`">
            </div>
            `;

            // var htmlRadio = '<option value="'+value+'">'+label+'</option>';
            $("#dynamicFieldsPopulation").find('.draggable').append(htmlRadio);
        });
    }
    if(checkField == "cb_multi"){
        $("#dynamicFieldsPopulation").find('.draggable').empty();

        $(".dropdown-options").each(function(){
            var label = $(this).find(".dropdown-label").val();
            var value = $(this).find(".dropdown-value").val();
            var ddHtml = "";
            ddHtml =`
            <>
            `;
            var htmlRadio = `
            <div class="form-group" app-field-wrapper="proposal_checkbox_custom">
                <label for="proposal_checkbox_custom" class="control-label"> <small class="req text-danger">* </small>`+label+`</label>
                <input type="checkbox" id="proposal_checkbox_custom" name="proposal_checkbox_custom" value="`+value+`">
            </div>
            `;

            // var htmlRadio = '<option value="'+value+'">'+label+'</option>';
            $("#dynamicFieldsPopulation").find('.draggable').append(htmlRadio);
        });
    }
    if(checkField == "barchart"){

        $("#barchart_values").empty();
        var data=[];
        var Header= ['Element', 'Density', { role: 'style' }];
        var label = "";
        var value = "";
        var color = "";
        data.push(Header);

        $(".dropdown-options").each(function(){
            label = $(this).find(".dropdown-label").val();
            value = $(this).find(".dropdown-value").val();
            color = "#b87333";

            var temp=[];
            temp.push(label);
            temp.push(parseInt(value));
            temp.push(color);

            data.push(temp);
        });
   
        var heading = $("#field_name").val();

        google.charts.load("current", {packages:["corechart"]});
        var data = google.visualization.arrayToDataTable(data);
        initializeChart(heading, data);
    }
    if(checkField == "progresschart"){
        
        $("#barchart_values").empty();

        // SAMPLE DATA_____________________
        // [
        //   ['Task', 'Hours per Day'],
        //   ['Work',     11],
        //   ['Eat',      2],
        // ]

        var data=[];
        var Header= ['Task', 'Hours per Day'];
        var label = "";
        var value = "";
        var color = "";
        data.push(Header);

        $(".dropdown-options").each(function(){
            label = $(this).find(".dropdown-label").val();
            value = $(this).find(".dropdown-value").val();

            var temp=[];
            temp.push(label);
            temp.push(parseInt(value));
            data.push(temp);
        });
   
        var heading = $("#field_name").val();

        google.charts.load("current", {packages:["corechart"]});
        var data = google.visualization.arrayToDataTable(data);
        initializeProgressChart(heading, data);
    }
    
 
}
function dd_trash(thss){
    $(thss).parent().parent().remove();
} 
function rad_trash(thss){
    $(thss).parent().parent().remove();
} 


function button_text(linkType="text"){
    if(linkType == "text"){
        $("#dynamicFieldsPopulation").find('.draggable a').removeClass("proposal_button_custom");
        $("#dynamicFieldsPopulation").find('.draggable a').addClass("proposal_button_text_custom");

    }
    else if(linkType == "button"){
        $("#dynamicFieldsPopulation").find('.draggable a').removeClass("proposal_button_text_custom");
        $("#dynamicFieldsPopulation").find('.draggable a').addClass("proposal_button_custom");
    }
}
function populateIcon(iconElement = ""){
        var iconLabel = "";
        $("#dynamicFieldsPopulation").find('a').html(iconElement);
        iconLabel = $("#settingTab").find('#iconLabel').val();
        $("#dynamicFieldsPopulation").find('a').find('i').text(iconLabel);
        $("#closeIconModal").trigger("click");
}
var field_type;
var randomID;
function dynamicFieldsPopulation(checkField = ""){
    field_type = checkField;
    $("#flow_tab").css("display", "block");
    // Get_acf_flows(field_type);
    var fieldHtml = "";
    var settingHtml = "";
    const currentDate = new Date().getTime();
    randomID = currentDate + Math.floor(Math.random() * 100000);
    if(checkField == "text"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_text_custom">
                <label for="proposal_text_custom" class="control-label"> <small class="req text-danger">* </small>Field# large</label>
                <input type="text" id="proposal_text_custom" name="proposal_text_custom" class="form-control" value="" ref-id="${randomID}">
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Text Field Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Field Name</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="textFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
            <div class="" onclick="">
                <div class="checkbox checkbox-primary" id="required_wrap">
                    <input type="checkbox" id="required" onclick="textFieldSetting('required')" ref-id="${randomID}">
                    <label for="required">Required</label>
                </div>
            </div>
            <div class="" onclick=""></div>
            <div class="inner-responsive-panel" onclick="textFieldSetting('sizing','col-md-12')" >
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="textFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div> 
        `;
    }
    if(checkField == "number"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_number_custom">
                <label for="proposal_number_custom" class="control-label"> <small class="req text-danger">* </small>Field# large</label>
                <input type="number" id="proposal_number_custom" name="proposal_number_custom" class="form-control" value="" ref-id="${randomID}">
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Number Field Setting</h3></div>
        <div class="sidebar-responsive-panel">

            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Field Name</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="numberFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
            <div class="" onclick="">
                <div class="checkbox checkbox-primary" id="required_wrap">
                    <input type="checkbox" id="required" onclick="numberFieldSetting('required')" ref-id="${randomID}">
                    <label for="required">Required</label>
                </div>
            </div>
            <div class="" onclick=""></div>
            <div class="inner-responsive-panel" onclick="numberFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="numberFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div>
        `; 
    }
    if(checkField == "textarea"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_textarea_custom">
                <label for="proposal_textarea_custom" class="control-label"> <small class="req text-danger">* </small>Field# large</label>
                <textarea id="proposal_textarea_custom" name="proposal_textarea_custom" class="form-control" name="w3review" rows="4" cols="50"></textarea>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Textbox Field Setting</h3></div>
        <div class="sidebar-responsive-panel">

            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Field Name</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="textboxFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
            <div class="" onclick="">
                <div class="checkbox checkbox-primary" id="required_wrap">
                    <input type="checkbox" id="required" onclick="textboxFieldSetting('required')" ref-id="${randomID}">
                    <label for="required">Required</label>
                </div>
            </div>
            <div class="" onclick=""></div>
            <div class="inner-responsive-panel" onclick="textboxFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="textboxFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div>
        `; 
    }
    if(checkField == "dropdown"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group">
                <label for="proposal_dropdown_custom" class="control-label">Large Dropdown</label>
                <select name="proposal_dropdown_custom" id="proposal_dropdown_custom" class="form-control proposal_dropdown_custom"  data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <option value="0" >Nothing selected</option>
                </select>
            </div>
        </div>
        `;
        settingHtml =`
        <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#dd_values_tab">Add Values</a></li>
              <li><a data-toggle="tab" id="" href="#dd_setting_tab">Settings</a></li>
        </ul>

        <div class="tab-content">
            
            <div class="sidebar-responsive-panel tab-pane fade in" id="dd_setting_tab">

                <div class="form-group" app-field-wrapper="name">
                    <label for="field_name" class="control-label"> 
                    <small class="req text-danger">* </small>Field Name</label>
                    <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="selectboxFieldSetting('label', this.value)" ref-id="${randomID}">
                </div>
                <div class="" onclick=""></div>
                <div class="" onclick="">
                    <div class="checkbox checkbox-primary" id="required_wrap">
                        <input type="checkbox" id="required" onclick="selectboxFieldSetting('required')" ref-id="${randomID}">
                        <label for="required">Required</label>
                    </div>
                </div>
                <div class="" onclick=""></div>
                <div class="inner-responsive-panel" onclick="selectboxFieldSetting('sizing','col-md-12')">
                    <div class="text-white" > Large</div>
                </div>
                <div class="inner-responsive-panel" onclick="selectboxFieldSetting('sizing','col-md-6')">
                    <div class="text-white" > Medium</div>
                </div>


            </div>
            <div class="tab-pane fade in active" id="dd_values_tab">
                <div class=" dropdown-options-main" >
                    <div class=" dropdown-options" >
                        <!-- LABEL -->
                        <div class="col-md-10 ">
                            <div class="form-group" app-field-wrapper="dropdown-label">
                                <input type="text" class="form-control dropdown-label" value="" placeholder="Label" ref-id="${randomID}" >
                            </div>
                        </div>
                        <div class="col-md-2 ">
                        <i class="fa fa-trash fa-lg dd_trash text-danger" onclick="dd_trash(this);"></i>
                        </div>

                        <!-- VALUE -->
                        <div class="col-md-10 ">
                            <div class="form-group" app-field-wrapper="dropdown-value">
                                <input type="text" class="form-control dropdown-value" value="" placeholder="Value" ref-id="${randomID}" >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <button class="dropdown-multi-options col-md-6" onclick="addMuliOption()"><i class="fa fa-plus" aria-hidden="true"></i>  Add Option </button>
                    <button class="dropdown-multi-options-save col-md-6" onclick="addMuliOptionData('dropdown')"><i class="fa fa-check" aria-hidden="true"></i>  Save Options </button>
                </div>
            </div>

        </div>
        `; 
    }
    if(checkField == "checkbox"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_checkbox_custom">
                <label for="proposal_checkbox_custom" class="control-label"> <small class="req text-danger">* </small>Field# large</label>
                <input type="checkbox" id="proposal_checkbox_custom" ref-id="${randomID}">
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Checkbox Setting</h3></div>
        <div class="sidebar-responsive-panel">

            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Label</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="checkboxFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
            <div class="" onclick="">
                <div class="checkbox checkbox-primary" id="required_wrap">
                    <input type="checkbox" id="required" onclick="checkboxFieldSetting('required')" ref-id="${randomID}">
                    <label for="required">Required</label>
                </div>
            </div>
            <div class="" onclick=""></div>
            <div class="inner-responsive-panel" onclick="checkboxFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="checkboxFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div>
        `; 
    }
    if(checkField == "radio"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_radio_custom">
                <label for="proposal_radio_custom" class="control-label"> <small class="req text-danger">* </small>Radio1</label>
                <input type="radio" id="proposal_radio_custom" name="proposal_radio_custom" value="Radio1" ref-id="${randomID}">
            </div>
            <div class="form-group" app-field-wrapper="proposal_radio_custom">
                <label for="proposal_radio_custom" class="control-label"> <small class="req text-danger">* </small>Radio2</label>
                <input type="radio" id="proposal_radio_custom" name="proposal_radio_custom" value="Radio2" ref-id="${randomID}">
            </div>
            <div class="form-group" app-field-wrapper="proposal_radio_custom">
                <label for="proposal_radio_custom" class="control-label"> <small class="req text-danger">* </small>Radio3</label>
                <input type="radio" id="proposal_radio_custom" name="proposal_radio_custom" value="Radio3" ref-id="${randomID}">
            </div>
        </div>
        `;
        settingHtml =`
        <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#rad_values_tab">Add Values</a></li>
              <li><a data-toggle="tab" id="" href="#rad_setting_tab">Settings</a></li>
        </ul>

        <div class="tab-content">
            
            <div class="sidebar-responsive-panel tab-pane fade in" id="rad_setting_tab">

                <div class="form-group" app-field-wrapper="name">
                    <label for="field_name" class="control-label"> 
                    <small class="req text-danger">* </small>Field Name</label>
                    <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="radioFieldSetting('label', this.value)" ref-id="${randomID}">
                </div>
                <div class="" onclick=""></div>
                <div class="" onclick="">
                    <div class="checkbox checkbox-primary" id="required_wrap">
                        <input type="checkbox" id="required" onclick="radioFieldSetting('required')" ref-id="${randomID}">
                        <label for="required">Required</label>
                    </div>
                </div>
                <div class="" onclick=""></div>
                <div class="inner-responsive-panel" onclick="radioFieldSetting('sizing','col-md-12')">
                    <div class="text-white" > Large</div>
                </div>
                <div class="inner-responsive-panel" onclick="radioFieldSetting('sizing','col-md-6')">
                    <div class="text-white" > Medium</div>
                </div>


            </div>
            <div class="tab-pane fade in active" id="rad_values_tab">
                <div class=" dropdown-options-main" >
                    <div class=" dropdown-options" >
                        <!-- LABEL -->
                        <div class="col-md-10 ">
                            <div class="form-group" app-field-wrapper="dropdown-label">
                                <input type="text" class="form-control dropdown-label" value="" placeholder="Label" ref-id="${randomID}">
                            </div>
                        </div>
                        <div class="col-md-2 ">
                            <i class="fa fa-trash fa-lg rad_trash text-danger" onclick="rad_trash(this);"></i>
                        </div>

                        <!-- VALUE -->
                        <div class="col-md-10 ">
                            <div class="form-group" app-field-wrapper="dropdown-value">
                                <input type="text" class="form-control dropdown-value" value="" placeholder="Value" ref-id="${randomID}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <button class="dropdown-multi-options col-md-6" onclick="addMuliOption()"><i class="fa fa-plus" aria-hidden="true"></i>  Add Option </button>
                    <button class="dropdown-multi-options-save col-md-6" onclick="addMuliOptionData('radio')"><i class="fa fa-check" aria-hidden="true"></i>  Save Options </button>
                </div>
            </div>

        </div>
        `; 
    }
    if(checkField == "checkbox_multi"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_radio_custom">
                <label for="proposal_radio_custom" class="control-label"> <small class="req text-danger">* </small>checkbox1</label>
                <input type="checkbox" id="proposal_checkbox_custom" name="proposal_checkbox_custom" value="checkbox1" ref-id="${randomID}">
            </div>
            <div class="form-group" app-field-wrapper="proposal_checkbox_custom">
                <label for="proposal_checkbox_custom" class="control-label"> <small class="req text-danger">* </small>checkbox2</label>
                <input type="checkbox" id="proposal_checkbox_custom" name="proposal_checkbox_custom" value="checkbox2" ref-id="${randomID}">
            </div>
            <div class="form-group" app-field-wrapper="proposal_checkbox_custom">
                <label for="proposal_checkbox_custom" class="control-label"> <small class="req text-danger">* </small>checkbox3</label>
                <input type="checkbox" id="proposal_checkbox_custom" name="proposal_checkbox_custom" value="checkbox3" ref-id="${randomID}">
            </div>
        </div>
        `;
        settingHtml =`
        <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#cb_values_tab">Add Checkbox</a></li>
              <li><a data-toggle="tab" id="" href="#cb_setting_tab">Settings</a></li>
        </ul>

        <div class="tab-content">
            
            <div class="sidebar-responsive-panel tab-pane fade in" id="cb_setting_tab">

                <div class="form-group" app-field-wrapper="name">
                    <label for="field_name" class="control-label"> 
                    <small class="req text-danger">* </small>Field Name</label>
                    <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="cb_multiFieldSetting('label', this.value)" ref-id="${randomID}">
                </div>
                <div class="" onclick=""></div>
                <div class="" onclick="">
                    <div class="checkbox checkbox-primary" id="required_wrap">
                        <input type="checkbox" id="required" onclick="cb_multiFieldSetting('required')" ref-id="${randomID}">
                        <label for="required">Required</label>
                    </div>
                </div>
                <div class="" onclick=""></div>
                <div class="inner-responsive-panel" onclick="cb_multiFieldSetting('sizing','col-md-12')">
                    <div class="text-white" > Large</div>
                </div>
                <div class="inner-responsive-panel" onclick="cb_multiFieldSetting('sizing','col-md-6')">
                    <div class="text-white" > Medium</div>
                </div>


            </div>
            <div class="tab-pane fade in active" id="cb_values_tab">
                <div class=" dropdown-options-main" >
                    <div class=" dropdown-options" >
                        <!-- LABEL -->
                        <div class="col-md-10 ">
                            <div class="form-group" app-field-wrapper="dropdown-label">
                                <input type="text" class="form-control dropdown-label" value="" placeholder="Label" ref-id="${randomID}">
                            </div>
                        </div>
                        <div class="col-md-2 ">
                            <i class="fa fa-trash fa-lg rad_trash text-danger" onclick="rad_trash(this);"></i>
                        </div>

                        <!-- VALUE -->
                        <div class="col-md-10 ">
                            <div class="form-group" app-field-wrapper="dropdown-value">
                                <input type="text" class="form-control dropdown-value" value="" placeholder="Value" ref-id="${randomID}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <button class="dropdown-multi-options col-md-6" onclick="addMuliOption()"><i class="fa fa-plus" aria-hidden="true"></i>  Add Option </button>
                    <button class="dropdown-multi-options-save col-md-6" onclick="addMuliOptionData('cb_multi')"><i class="fa fa-check" aria-hidden="true"></i>  Save Options </button>
                </div>
            </div>

        </div>
        `; 
    }
    if(checkField == "date"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="date" >
                <label for="date" class="control-label"> <small class="proposal_date_custom req text-danger">* </small>Date</label>
                <div class="input-group date">
                    <input type="text" id="date" name="proposal_date_custom" class="form-control datepicker" value="2023-08-09" autocomplete="off" ref-id="${randomID}">
                    <div class="input-group-addon">
                        <i class="fa-regular fa-calendar calendar-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Date Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Field Name</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="dateFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
            <div class="" onclick="">
                <div class="checkbox checkbox-primary" id="required_wrap">
                    <input type="checkbox" id="required" onclick="dateFieldSetting('required')" ref-id="${randomID}">
                    <label for="required">Required</label>
                </div>
            </div>
            <div class="" onclick=""></div>
            <div class="inner-responsive-panel" onclick="dateFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="dateFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div> 
        `;
    }
    if(checkField == "file"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_file_custom">
                <label for="proposal_file_custom" class="control-label"> <small class="req text-danger">* </small>Field# large</label>
                <input type="file" id="proposal_file_custom" ref-id="${randomID}">
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">File Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Field Name</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="dateFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
            <div class="" onclick="">
                <div class="checkbox checkbox-primary" id="required_wrap">
                    <input type="checkbox" id="required" onclick="dateFieldSetting('required')" ref-id="${randomID}">
                    <label for="required">Required</label>
                </div>
            </div>
            <div class="" onclick=""></div>
            <div class="inner-responsive-panel" onclick="dateFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="dateFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div> 
        `;
    }
    if(checkField == "table"){
        fieldHtml = `
        <div class="col-md-12 draggable tableBox">
            <h4 class="text-white"> Heading</h4>
            <table class="custom_table">
                <thead>
                    <tr>
                        <th><input type="text" value=""></th>
                        <th><input type="text" value=""></th>
                        <th><input type="text" value=""></th>
                    </tr>
                </thead>
                <tbody class="custom_table_body">
                    <tr class="r1">
                        <td><input type="text" value=""></td>
                        <td><input type="text" value=""></td>
                        <td><input type="text" value=""></td>
                    </tr>
                    <tr class="r2">
                        <td><input type="text" value=""></td>
                        <td><input type="text" value=""></td>
                        <td><input type="text" value=""></td>
                    </tr>
                </tbody>
            </table>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Table Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Heading</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="tableSetting('heading', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>

            <div class="inner-responsive-panel" >
                <div class="text-white" > 
                <p>Total Rows</p>
                <input type="number" id="table_row" class="form-control" value="3" onkeyup="tableSetting('table_rc', this.value)" ref-id="${randomID}"> </div>
            </div>
            <div class="inner-responsive-panel" >
                <div class="text-white" > 
                <p>Total Columns</p>
                <input type="number" id="table_col" class="form-control" value="3" aria-invalid="false" onkeyup="tableSetting('table_rc', this.value)" ref-id="${randomID}">
                 </div>
            </div>


            <div class="inner-responsive-panel" onclick="tableSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="tableSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div> 
        `;
    }
    if(checkField == "button"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_button_custom ">
               <a href="#" class="proposal_button_custom col-md-12 ${randomID}" ref-id="${randomID}">Call to action</a>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Button Setting</h3></div>
        <div class="sidebar-responsive-panel">

            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm col-md-12" style="border:1px solid;" onclick="button_text('button')">Button</button>
            </div>
            
            <div class="btn-group" style="margin-bottom: 20px;">
                <button type="button" class="btn btn-primary btn-sm col-md-12" style="border:1px solid;" onclick="button_text('text')">Text</button>
            </div>

            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Button/Link text</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="button_textFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>href </label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="button_textFieldSetting('link', this.value)" placeholder="https://www.example.com" ref-id="${randomID}">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="self" class=" target" aria-invalid="false" onclick="button_textFieldSetting('target', this.value)" name="target" value="self" ref-id="${randomID}">
                <label for="self"> Self </label>
            </div>
            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="new" class=" target" aria-invalid="false" onclick="button_textFieldSetting('target', this.value)"  name="target" value="blank" ref-id="${randomID}">
                <label for="new">New Page </label>
            </div>
           
            <div class="inner-responsive-panel" onclick="textFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="textFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div> 
        `;
    }

    if(checkField == "icon"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="form-group" app-field-wrapper="proposal_icon_custom ">
               <a href="" class="proposal_icon_custom col-md-12" target="_blank"><i class="">Icon</i></a>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Icon Setting</h3></div>
        <div class="sidebar-responsive-panel">

            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm col-md-12" style="border:1px solid;" data-toggle="modal" data-target="#iconModal">Select Icon</button>
            </div>
            
            <div class="btn-group" style="">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <label for="iconLabel" class="control-label"> 
                <small class="req text-danger">* </small>Icon text</label>
                <input type="text" id="iconLabel" class="form-control" value="" aria-invalid="false" onkeyup="iconFieldSetting('label', this.value)" ref-id="${randomID}">
            </div>
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Icon Link </label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="iconFieldSetting('link', this.value)" placeholder="https://www.example.com" ref-id="${randomID}">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="self" class=" target" aria-invalid="false" onclick="iconFieldSetting('target', this.value)" name="target" value="self" ref-id="${randomID}">
                <label for="self"> Self </label>
            </div>
            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="new" class=" target" aria-invalid="false" onclick="iconFieldSetting('target', this.value)"  name="target" value="blank" ref-id="${randomID}">
                <label for="new">New Page </label>
            </div>
           
            <div class="inner-responsive-panel" onclick="iconFieldSetting('sizing','col-md-12')">
                <div class="text-white" > Large</div>
            </div>
            <div class="inner-responsive-panel" onclick="iconFieldSetting('sizing','col-md-6')">
                <div class="text-white" > Medium</div>
            </div>
        </div> 
        `;
    }
    if(checkField == "fileviewer_img"){
        fieldHtml = `
        <div class="col-md-12 fileViewer_img draggable">
            <div class="form-group" app-field-wrapper="proposal_img_custom ">
               <a href="#" class="proposal_img_custom " target="_blank">
                <img src="https://imageio.forbes.com/specials-images/imageserve/5d35eacaf1176b0008974b54/2020-Chevrolet-Corvette-Stingray/0x0.jpg" class="" />

               </a>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Image Setting</h3></div>
        <div class="sidebar-responsive-panel">


            <div class="form-group" app-field-wrapper="name">
                <input type="file" id="" class="form-control" aria-invalid="false" onchange="fileviewer_imgSetting('img', this)" ref-id="${randomID}">
            </div>
            <div class="btn-group" style="">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>File Link </label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="fileviewer_imgSetting('link', this.value)" placeholder="https://www.example.com" ref-id="${randomID}">
            </div>
            <div class="btn-group" style="">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="self" class=" target" aria-invalid="false" onclick="fileviewer_imgSetting('target', this.value)" name="target" value="self" ref-id="${randomID}">
                <label for="self"> Self </label>
            </div>
            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="new" class=" target" aria-invalid="false" onclick="fileviewer_imgSetting('target', this.value)"  name="target" value="blank" ref-id="${randomID}">
                <label for="new">New Page </label>
            </div>
           
           
        </div> 
        `;
    }
    if(checkField == "fileviewer_pdf"){
        fieldHtml = `
        <div class="col-md-12 fileViewer_pdf draggable">
            <div class="form-group" app-field-wrapper="proposal_img_custom ">
               <a href="#" class="proposal_img_custom " target="_blank">
                    <embed src="https://www.africau.edu/images/default/sample.pdf" type="application/pdf"   height="400" width="300">
               </a>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">PDF Setting</h3></div>
        <div class="sidebar-responsive-panel">


            <div class="form-group" app-field-wrapper="name">
                <input type="file" id="" class="form-control" aria-invalid="false" onchange="fileviewer_pdfSetting('img', this)" ref-id="${randomID}">
            </div>
            <div class="btn-group" style="">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>File Link </label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="fileviewer_pdfSetting('link', this.value)" placeholder="https://www.example.com" ref-id="${randomID}">
            </div>
            <div class="btn-group" style="">
            </div>

            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="self" class=" target" aria-invalid="false" onclick="fileviewer_pdfSetting('target', this.value)" name="target" value="self" ref-id="${randomID}">
                <label for="self"> Self </label>
            </div>
            <div class="form-group" app-field-wrapper="name">
                <input type="radio" id="new" class=" target" aria-invalid="false" onclick="fileviewer_pdfSetting('target', this.value)"  name="target" value="blank" ref-id="${randomID}">
                <label for="new">New Page </label>
            </div>
           
           
        </div> 
        `;
    }
    // if(checkField == "fileviewer_doc"){
    //     fieldHtml = `
    //     <div class="col-md-12 fileviewer_doc draggable">
    //         <div class="form-group" app-field-wrapper="proposal_img_custom ">
    //            <a href="#" class="proposal_img_custom " target="_blank">
    //            <iframe class="doc" src="https://docs.google.com/gview?url=http://writing.engr.psu.edu/workbooks/formal_report_template.doc&embedded=true"></iframe>
    //            </a>
    //         </div>
    //     </div>
    //     `;
    //     settingHtml =`
    //     <div><h3 class="setting_heading">Doc Setting</h3></div>
    //     <div class="sidebar-responsive-panel">


    //         <div class="form-group" app-field-wrapper="name">
    //             <input type="file" id="" class="form-control" aria-invalid="false" onchange="fileviewer_docSetting('img', this)">
    //         </div>
    //         <div class="btn-group" style="">
    //         </div>

    //         <div class="form-group" app-field-wrapper="name">
    //             <label for="field_name" class="control-label"> 
    //             <small class="req text-danger">* </small>File Link </label>
    //             <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="fileviewer_docSetting('link', this.value)" placeholder="https://www.example.com">
    //         </div>
    //         <div class="btn-group" style="">
    //         </div>

    //         <div class="form-group" app-field-wrapper="name">
    //             <input type="radio" id="self" class=" target" aria-invalid="false" onclick="fileviewer_docSetting('target', this.value)" name="target" value="self">
    //             <label for="self"> Self </label>
    //         </div>
    //         <div class="form-group" app-field-wrapper="name">
    //             <input type="radio" id="new" class=" target" aria-invalid="false" onclick="fileviewer_docSetting('target', this.value)"  name="target" value="blank">
    //             <label for="new">New Page </label>
    //         </div>
           
           
    //     </div> 
    //     `;
    // }
    // if(checkField == "fileviewer_xls"){
    //     fieldHtml = `
    //     <div class="col-md-12 fileviewer_xls draggable">
    //         <div class="form-group" app-field-wrapper="proposal_img_custom ">
    //            <a href="#" class="proposal_img_custom " target="_blank">
    //            <iframe class="doc" src="https://docs.google.com/gview?url=http://writing.engr.psu.edu/workbooks/formal_report_template.doc&embedded=true"></iframe>
    //            </a>
    //         </div>
    //     </div>
    //     `;
    //     settingHtml =`
    //     <div><h3 class="setting_heading">File Setting</h3></div>
    //     <div class="sidebar-responsive-panel">


    //         <div class="form-group" app-field-wrapper="name">
    //             <input type="file" id="" class="form-control" aria-invalid="false" onchange="fileviewer_xlsSetting('img', this)">
    //         </div>
    //         <div class="btn-group" style="">
    //         </div>

    //         <div class="form-group" app-field-wrapper="name">
    //             <label for="field_name" class="control-label"> 
    //             <small class="req text-danger">* </small>File Link </label>
    //             <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="fileviewer_xlsSetting('link', this.value)" placeholder="https://www.example.com">
    //         </div>
    //         <div class="btn-group" style="">
    //         </div>

    //         <div class="form-group" app-field-wrapper="name">
    //             <input type="radio" id="self" class=" target" aria-invalid="false" onclick="fileviewer_xlsSetting('target', this.value)" name="target" value="self">
    //             <label for="self"> Self </label>
    //         </div>
    //         <div class="form-group" app-field-wrapper="name">
    //             <input type="radio" id="new" class=" target" aria-invalid="false" onclick="fileviewer_xlsSetting('target', this.value)"  name="target" value="blank">
    //             <label for="new">New Page </label>
    //         </div>
           
           
    //     </div> 
    //     `;
    // }
    if(checkField == "chart"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="card">
                <div class="card-body">
                    <div id="barchart_values" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Chart Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Chart Heading</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="chartSetting('heading', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
        </div>

        <div class=" dropdown-options-main" >
            <div class=" dropdown-options" >
                <!-- LABEL -->
                <div class="col-md-10 ">
                    <div class="form-group" app-field-wrapper="dropdown-label">
                        <input type="text" class="form-control dropdown-label" value="" placeholder="Label" ref-id="${randomID}">
                    </div>
                </div>
                <div class="col-md-2 ">
                    <i class="fa fa-trash fa-lg rad_trash text-danger" onclick="rad_trash(this);"></i>
                </div>

                <!-- VALUE -->
                <div class="col-md-10 ">
                    <div class="form-group" app-field-wrapper="dropdown-value">
                        <input type="text" class="form-control dropdown-value" value="" placeholder="Value" ref-id="${randomID}">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <button class="dropdown-multi-options col-md-6" onclick="addMuliOption()"><i class="fa fa-plus" aria-hidden="true"></i>  Add Option </button>
            <button class="dropdown-multi-options-save col-md-6" onclick="addMuliOptionData('barchart')"><i class="fa fa-check" aria-hidden="true"></i>  Save Options </button>
        </div> 
        `;
        initializeChart();
    }
    if(checkField == "progress_bar"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="card">
                <div class="card-body">
                    <div id="progress_bar" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Progress Bar Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Heading</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="progresschartSetting('heading', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
        </div>

        <div class=" dropdown-options-main" >
            <div class=" dropdown-options" >
                <!-- LABEL -->
                <div class="col-md-10 ">
                    <div class="form-group" app-field-wrapper="dropdown-label">
                        <input type="text" class="form-control dropdown-label" value="" placeholder="Label" ref-id="${randomID}" >
                    </div>
                </div>
                <div class="col-md-2 ">
                    <i class="fa fa-trash fa-lg rad_trash text-danger" onclick="rad_trash(this);"></i>
                </div>

                <!-- VALUE -->
                <div class="col-md-10 ">
                    <div class="form-group" app-field-wrapper="dropdown-value">
                        <input type="text" class="form-control dropdown-value" value="" placeholder="Value" ref-id="${randomID}" >
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <button class="dropdown-multi-options col-md-6" onclick="addMuliOption()"><i class="fa fa-plus" aria-hidden="true"></i>  Add Option </button>
            <button class="dropdown-multi-options-save col-md-6" onclick="addMuliOptionData('progresschart')"><i class="fa fa-check" aria-hidden="true"></i>  Save Options </button>
        </div> 
        `;
        initializeProgressChart();
    }
    if(checkField == "map"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="card">
                <div class="card-body">
                    <div id="ACF_map"></div>
                </div>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Map Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Heading</label>
                <input type="text" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="progresschartSetting('heading', this.value)" ref-id="${randomID}">
            </div>
            <div class="" onclick=""></div>
        </div>
 
        `;
        initAcfMap();
    }
    if(checkField == "esign"){
        fieldHtml = `
        <div class="col-md-12 draggable">
            <div class="card">
                <div class="card-body">
                    <h4>Signature</h4>
                    <canvas id="signatureCanvass" width="300" height="100"></canvas>
                    <input type="hidden" ref-id="${randomID}" name="signatureCanvass" />
                </div>
            </div>
        </div>
        `;
        settingHtml =`
        <div><h3 class="setting_heading">Map Setting</h3></div>
        <div class="sidebar-responsive-panel">
            <div class="form-group" app-field-wrapper="name">
                <label for="field_name" class="control-label"> 
                <small class="req text-danger">* </small>Heading</label>
                <input type="text" ref-id="${randomID}" id="field_name" class="form-control" value="" aria-invalid="false" onkeyup="esignSetting('heading', this.value)">
            </div>
            <div class="" onclick=""></div>
        </div>
 
        `;
        // initCotnrol();
        // init_Esignature();

        
      
    }

    $("#setting_a").trigger('click');
    $("#dynamicFieldsPopulation").html(fieldHtml);
    $("#settingTab").html(settingHtml);
    activeDraggingFunctions();


}


// DATABASE PART zeeshan
function saveToPublished(){
    $("input[name='proposal_text_custom']").keyup(function(){
        var vall = $(this).val();
        $(this).attr("value", vall);
        publishFields();

    });
    $("input[name='proposal_number_custom']").keyup(function(){
        var vall = $(this).val();
        $(this).attr("value", vall);
        publishFields();
    });
    $("textarea[name='proposal_textarea_custom']").keyup(function(){
        var vall = $(this).val();
        $(this).html(vall);
        publishFields();
    });
    $("select[name='proposal_dropdown_custom']").change(function(){
        var vall = $(this).val();
        console.log(vall);
        // $(this).prop('selectedIndex', vall);
        // $(this).val(vall).trigger("chosen:updated");
        $(this).val(vall).change();
        // $("select[name='proposal_dropdown_custom'] option[value="+vall+"]").prop("selected", true);
        $(this).find("option[value=13]").prop("selected", "selected");
        // $(this).html(vall);
        publishFields();
    });

}
function publishFields(){
    $(".draggable").removeAttr("style");
    var droppable_content = $(".dropp_able").html();
    droppable_content = droppable_content.replace('style=""', '');


    $("textarea[name='droppable_content']").html(droppable_content.trim());
}
// DATABASE PART zeeshan

function openNav() {
  document.getElementById("closebtn").style.display = "block";
  document.getElementById("mySidebar_cf").style.width = "480px";
  document.getElementById("mySidebar_cf").style.paddingRight = "30px";
  document.getElementById("mySidebar_cf").style.paddingLeft = "30px";
  var pageHeight = document.getElementById("wrapper").style.minHeight;
  document.getElementById("mySidebar_cf").style.height = pageHeight;
  document.getElementById("wrapper").style.marginRight = "480px";
  $(".btn-bottom-toolbar").hide();
  // document.getElementsByClassName("btn-bottom-toolbar").style.display = "none";

  
}

function closeNav() {
  document.getElementById("closebtn").style.display = "none";
  document.getElementById("mySidebar_cf").style.width = "0";
  document.getElementById("mySidebar_cf").style.paddingRight = "0px";
  document.getElementById("mySidebar_cf").style.paddingLeft = "0px";
  document.getElementById("wrapper").style.marginRight = "0";
  $(".btn-bottom-toolbar").show();
  $("#flow_tab").css("display", "none");

  publishFields();
}


// FOR SIGNATURE
    (function (ns) {
    "use strict";

    ns.SignatureControl = function (options) {
        var containerId = options && options.canvasId || "container",
            callback = options && options.callback || {},
            label = options && options.label || "Signature",
            cWidth = options && options.width || "300px",
            cHeight = options && options.height || "300px",
            btnClearId,
            btnAcceptId,
            canvas,
            ctx;

        function initCotnrol() {
            createControlElements();
            wireButtonEvents();
            canvas = document.getElementById("signatureCanvass");
            canvas.addEventListener("mousedown", pointerDown, false);
            canvas.addEventListener("mouseup", pointerUp, false);
            ctx = canvas.getContext("2d");            
        }

        function createControlElements() {            
            var signatureArea = document.createElement("div"),
                labelDiv = document.createElement("div"),
                canvasDiv = document.createElement("div"),
                canvasElement = document.createElement("canvas"),
                buttonsContainer = document.createElement("div"),
                buttonClear = document.createElement("button"),
                buttonAccept = document.createElement("button");

            labelDiv.className = "signatureLabel";
            labelDiv.textContent = label;

            canvasElement.id = "signatureCanvass";
            // canvasElement.clientWidth = cWidth;
            // canvasElement.clientHeight = cHeight;
            canvasElement.style.border = "solid 2px black";

            buttonClear.id = "btnClear";
            buttonClear.textContent = "Clear";

            buttonAccept.id = "btnAccept";
            buttonAccept.textContent = "Accept";

            canvasDiv.appendChild(canvasElement);
            buttonsContainer.appendChild(buttonClear);
            buttonsContainer.appendChild(buttonAccept);

            signatureArea.className = "signatureArea";
            signatureArea.appendChild(labelDiv);
            signatureArea.appendChild(canvasDiv);
            signatureArea.appendChild(buttonsContainer);

            document.getElementById(containerId).appendChild(signatureArea);
        }

        function pointerDown(evt) {
            ctx.beginPath();
            ctx.moveTo(evt.offsetX, evt.offsetY);
            canvas.addEventListener("mousemove", paint, false);
        }

        function pointerUp(evt) {
            canvas.removeEventListener("mousemove", paint);
            paint(evt);
        }

        function paint(evt) {
            ctx.lineTo(evt.offsetX, evt.offsetY);
            ctx.stroke();
        }

        function wireButtonEvents() {
            var btnClear = document.getElementById("btnClear"),
                btnAccept = document.getElementById("btnAccept");
            btnClear.addEventListener("click", function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }, false);
            btnAccept.addEventListener("click", function () {
                callback();
            }, false);
        }

        function getSignatureImage() {
            return ctx.getImageData(0, 0, canvas.width, canvas.height).data;
        }

        return {
            init: initCotnrol,
            getSignatureImage: getSignatureImage
        };
    }
})(this.ns = this.ns || {});

function Get_acf_flows(type) {
$.ajax({
    type: 'GET',
    url: 'https://youribizerp.com/admin/acf_flows/get_acf_flow',
    success: function(response) {
        populateTable(JSON.parse(response),type);
    },
    error: function(error) {
        console.log('Error:', error);
    }
});
}
var hold_flows_data = [];
function populateTable(data, type) {
    var tableBody = $('#responseTable tbody');
    var option = $('#flow_list');
    tableBody.empty();
	option.empty();
	if (type == undefined) {
        type = field_type;
    }
    data.forEach(function(item) {
		hold_flows_data.push(item);
        var row = '<tr>';
        row += '<td><a href="https://youribizerp.com/admin/proposals/proposal' + item.id + '">' + item.id + '</a></td>';
        row += '<td>' + item.name + '</td>';
        row += '<td><a class="edit-btn" href="https://youribizerp.com/admin/acf_flows/workflow_builder/'+item.id+'?type='+type+'">Edit</a></td>'; // Added quotes around type
        row += '</tr>';
        option.append("<option value=" + item.id + ">" + item.name + "</option>");
        tableBody.append(row);
    });
}

$('#myForm').validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    rules: {
        name: {
            required: true
        }
    },
    submitHandler: function(form) {
        var formData = $('#myForm').serialize();
        $.ajax({
            type: 'POST',
            url: 'https://youribizerp.com/admin/acf_flows/create_acf_flow',
            data: formData,
            success: function(response) {
                $('#myForm').trigger('reset');
                Get_acf_flows();
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    }
});

$('input[name="name"]').valid();

$(".click_button").click(function(){
    if (this.getAttribute("ref-id")) {
    var to_id = $('input[name="isedit"]').val();
    $.ajax({
        type: 'GET',
        data:{ref_id: this.getAttribute("ref-id"), to_id:to_id},
        url: 'https://youribizerp.com/admin/acf_flows/perform_action',
        success: function(response) {
            console.log(response);
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
    }
});
$(".hover_button").click(function(){
    alert("hover the event");
});