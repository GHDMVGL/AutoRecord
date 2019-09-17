<?php
require_once "../../redcap_connect.php";
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

?>

<form id="to_post" style="display:none" method="post" action='<?php echo APP_PATH_WEBROOT; ?>index.php?pid=<?php print($_GET['pid']); ?>&route=DataImportController:index&' enctype='multipart/form-data'>
    <input id='uploadedfile' name='uploadedfile' type="file" />
    <input name='format' type="text" value='rows' />
    <input name='date_format' type="text" value='DMY' />
    <input name='overwriteBehavior' type="text" value='normal' />
    <input id="submit" name="submit" value="Upload File" type="submit"/>

</form>

<input id='tmpfile' name='tmpfile' type="file" />
<input id='last_record' type="hidden" value="<?php
                                    print(intval(end(end(REDCap::getData($_GET['pid'], 'array', NULL, ['record_id'])))['record_id']))
                               ?>" />
<input type="submit" onclick='csv_to_js()' />
<script>
		function post(path, params) {
			let fileInput = document.getElementById('uploadedfile');
			const dT = new DataTransfer();
			dT.items.add(new File([params], '<?php print($_GET['report']) ?>.csv'));
			fileInput.files = dT.files;
			document.getElementById('submit').click()
    }
    function csv_to_js(){
        var reader = new FileReader();
        reader.onload = function(){
            file_csv = reader.result
            arr = file_csv.split('\n')
            var jsonObj = [];
            var headers = arr[0].split(',');
            for(var i = 1; i < arr.length; i++) {
              var data = arr[i].split(',');
              var obj = {};
              for(var j = 0; j < data.length; j++) {
                 obj[headers[j].trim()] = data[j].trim();
              }
              jsonObj.push(obj);
            }
            var last_record=$('#last_record').val()
            cannot_be_csved = jsonObj.some(function(i){
                if (i['record_id'] != ""){
                    if (i['record_id'] == undefined || isNaN(i['record_id'])){
                        alert('No valid record_id found')
                        return true;
                    }
                    last_record = 1 + parseInt(last_record)
                    i['record_id'] = last_record
                }
            })
            if (!cannot_be_csved){
                var csv = Object.keys(jsonObj[1]).join(',') + "\n"
                jsonObj.forEach(function(k){
                    csv += Object.values(k).join(',') +"\n"
                })
                url='<?php echo APP_PATH_WEBROOT; ?>index.php?pid=<?php print($_GET['pid']); ?>&route=DataImportController:index&'
                post(url, csv)
            }
        };
        reader.readAsText($('#tmpfile')[0].files[0])
    }
</script>

