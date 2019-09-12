<?php
require_once "../../redcap_connect.php";
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

?>

<!--<form  method="post" action='<?php echo APP_PATH_WEBROOT; ?>index.php?pid=<?php print($_GET['pid']); ?>&route=DataImportController:index&' enctype='multipart/form-data'>
    <input id='uploadedfile' name='uploadedfile' type="file" />
    <input name='format' type="text" value='rows' />
    <input name='date_format' type="text" value='DMY' />
    <input name='overwriteBehavior' type="text" value='normal' />
    <input id="submit" name="submit" value="Upload File" type="submit"/>

</form>
-->
<input id='tmpfile' name='tmpfile' type="file" />
<input id='csrf' type="hidden" value="<?php print(end($_SESSION['redcap_csrf_token'])) ?>" />
<input id='last_record' type="hidden" value="<?php
                                    print(intval(end(end(REDCap::getData($_GET['pid'], 'array', NULL, ['record_id'])))['record_id']))
                               ?>" />
<input type="submit" onclick='csv_to_js()' />
<script>
		function post(path, params) {
			// Fonction pourfaire un post en JS sans JQuery
			// création d'un formulaire répliquant celui d'import dedonnées dans REDCAP
			// Creation du formulaire
			var form = document.createElement("form");
			form.setAttribute("method", "POST");
			form.setAttribute("action", path);
			form.setAttribute("enctype", 'multipart/form-data');
			// Ajout d'un champ File avec le nom du fichier (seuleument le nom, pas le contenu !)
			var file = document.createElement("input");
			file.setAttribute("type", "file");
			file.setAttribute("id", "uploadedfile");
			file.setAttribute("name", 'uploadedfile');
			file.setAttribute("filename", '<?php print($_GET['report']);?>.csv');
			form.appendChild(file);
			// Divers champs du formulaire d'import de REDCAP
			var format = document.createElement("input");
			format.setAttribute("type", "text");
			format.setAttribute("name", 'format');
			format.setAttribute("value", 'rows');
			form.appendChild(format);
			var date_format = document.createElement("input");
			date_format.setAttribute("type", "text");
			date_format.setAttribute("name", 'date_format');
			date_format.setAttribute("value", 'DMY');
			form.appendChild(date_format);
			var overwriteBehavior = document.createElement("input");
			overwriteBehavior.setAttribute("type", "text");
			overwriteBehavior.setAttribute("name", 'overwriteBehavior');
			overwriteBehavior.setAttribute("value", 'normal');
			form.appendChild(overwriteBehavior);
			var csrf = document.createElement("input");
			csrf.setAttribute("type", "text");
			csrf.setAttribute("name", 'redcap_csrf_token');
			csrf.setAttribute("value", $('#csrf').val());
			form.appendChild(csrf);
			// Bouton pour submit
			var sub = document.createElement("input");
			sub.setAttribute("type", "submit");
			sub.setAttribute("id", "submit");
			sub.setAttribute("name", 'submit');
			sub.setAttribute("value", 'Upload File');
			form.appendChild(sub);
			// Mise du contenu de params (le tableur) dans le champs File du formulaire
			document.body.appendChild(form);
			let fileInput = document.getElementById('uploadedfile');
			const dT = new DataTransfer();
			dT.items.add(new File([params], '<?php print($_GET['report']) ?>.csv'));
			fileInput.files = dT.files;
			// Envoi du formulaire
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

