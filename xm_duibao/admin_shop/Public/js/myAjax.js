/**
 * Created by admin on 2015/9/21.
 */

/**
 *  Ajax通用提交表单
 *  @var  form表单的id属性值  form_id
 *  @var  提交地址 subbmit_url
 */

function post_form(form_id,subbmit_url){
    if(form_id == '' && subbmit_url == ''){
        alert('缺少必要参数');
        return false;
    }
    //  序列化表单值
    var data = $('#'+form_id).serialize();

    $.post(subbmit_url,data,function(result){
        var obj = $.parseJSON(result);
        if(obj.status == 0){
            alert(obj.msg);
            return false;
        }
        if(obj.status == 1){
            alert(obj.msg);
            if(obj.data.return_url){
                //  返回跳转链接
                location.href = obj.data.return_url;
            }else{
                //  刷新页面
                location.reload();
            }
            return;
        }
    })
}


/**
 * 删除
 * @returns {void}
 */
function del_fun(del_url)
{
    if(confirm("确定要删除吗?"))
        location.href = del_url;
}  


// 修改指定表的指定字段值
function changeTableVal(table,id_name,id_value,field,obj)
{
		var src = "";
		 if($(obj).attr('src').indexOf("cancel.png") > 0 )
		 {          
				src = 'http://127.0.0.1:8002/admin_shop-edit/Public/img/yes.png';
			   //src = '__PUBLIC__/img/yes.png';
				var value = 1;
				
		 }else{                    
				src = 'http://127.0.0.1:8002/admin_shop-edit/Public/img/cancel.png';
			   // src = '__PUBLIC__/img/cancel.png';
				var value = 2;
		 }                                                 
		$.ajax({
				url:"./index.php?m=Index&c=Shop&a=changeTableVal&table="+table+"&id_name="+id_name+"&id_value="+id_value+"&field="+field+'&value='+value,			
				success: function(data){	
					$(obj).attr('src',src);           
				}
		});		
}

// 修改指定表的排序字段updateSort('Shoparr','id','{$vo.id}','sort_order',this)
function updateSort(table,id_name,id_value,field,obj)
{		       
 		var value = $(obj).val();
		$.ajax({
				url:"/index.php?m=Admin&c=Index&a=changeTableVal&table="+table+"&id_name="+id_name+"&id_value="+id_value+"&field="+field+'&value='+value,			
				success: function(data){									
					layer.msg('更新成功', {icon: 1});   
				}
		});		
}
 