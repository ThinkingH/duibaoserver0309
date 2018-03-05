/*
上传图片预览
*/
 function preview(file){  
	 var prevDiv = document.getElementById('preview');  
	 if (file.files && file.files[0]){  
		 var reader = new FileReader();  
		 reader.onload = function(evt){  
		 prevDiv.innerHTML = '<img src="' + evt.target.result + '" width="200px"/>';  
	}    
	 reader.readAsDataURL(file.files[0]);  
	}  
	 else{  
	 prevDiv.innerHTML = '<div class="img" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';  
	 }  
	}  