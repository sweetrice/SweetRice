<?php
/**
 * Transfer website.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.4.2
 */
 defined('VALID_INCLUDE') or die();
?>
<div class="tip"><?php _e('Transfer your website data to new hosting by FTP or download data file then upload them to new hosting manually,you may modify inc/db.php to change database setting to new hosting');?></div>
<fieldset class="pack_section"><legend><?php _e('Pack website');?></legend>
	<?php if(!extension_loaded('zlib') && !extension_loaded('ZZIPlib')):
	_e('Server do not supports ZIP');
	else:?>
<input type="button" value="<?php _e('Pack website');?>" class="input_submit pack_btn"/>
<?php if(file_exists(ROOT_DIR.$archive_name)):
	echo _t('Latest pack at ').date(_t('M d Y H:i'),filemtime(ROOT_DIR.$archive_name)).' '._t('File size').' '.filesize2print(BASE_URL.$archive_name);?>
	<input type="button" class="btn_clean" value="<?php _e('Delete');?>">
<?php
	endif;
?>
	<?php endif;?>
</fieldset>
<div class="transfer_section">
<fieldset><legend><?php _e('Transfer Type');?></legend>
	<?php _e('Manually');?> <input type="radio" name="transfer_type" value="download" class="options"/> 
	<?php _e('Online');?> <input type="radio" name="transfer_type" value="online" class="options"/>
</fieldset>
<fieldset class="server_setting"><legend><?php _e('FTP Server');?></legend>
<input type="text" id="ftp_server" name="ftp_server" req="1"/>
</fieldset>
<fieldset class="server_setting"><legend><?php _e('FTP Port default : 21');?></legend>
<input type="text" id="ftp_port" name="ftp_port" value="21"/>
</fieldset>
<fieldset class="server_setting"><legend><?php _e('FTP User');?></legend>
<input type="text" id="ftp_user" name="ftp_user" req="1"/>
</fieldset>
<fieldset class="server_setting"><legend><?php _e('FTP Password');?></legend>
<input type="text" id="ftp_password" name="ftp_password"/>
</fieldset>
<fieldset class="server_setting"><legend><?php _e('FTP Home');?></legend>
<input type="text" id="ftp_home" name="ftp_home"/>
</fieldset>
	<input type="button" value="<?php _e('Done');?>" class="input_submit transfer_btn"/>
	<span id="tip"></span>
</div>
<script type="text/javascript">
<!--
	_.ready(function(){	
		<?php if(file_exists(ROOT_DIR.$archive_name)):?>
			_('.transfer_section').show();
		<?php endif;?>
		_('.btn_clean').click(function(){
			if (!confirm('<?php _e('Are you sure delete it?');?>')){
				return ;
			}
			_.ajax({
				'type':'post',
				'data':{'_tkv_':_('#_tkv_').attr('value')},
				'url':'./?type=data&mode=transfer&form_type=pack_delete',
				'success':function(result){
					if (result['status'] == 1)
					{
						location.reload();
					}
				}
			});
		});
		_('.pack_btn').bind('click',function(){
			var ajax_dlg = _.dialog({'content':'<img src="../images/loading.gif"> <?php _e('Packing website data maybe take long time,please wait for minutes.');?>'});
			_.ajax({
				'type':'post',
				'data':{'_tkv_':_('#_tkv_').attr('value')},
				'url':'./?type=data&mode=transfer&form_type=pack',
				'success':function(result){
					ajax_dlg.remove();
					if (result['status'] == 1)
					{
						_('.pack_section').fadeOut(500,function(){
							_(this).hide();
							_('.transfer_section').fadeIn();
						});
					}
				}
			});
		});
		_('.options').bind('click',function(){
			var tip = '';
			switch (_(this).val())
			{
				case 'download':
					tip = '<?php _e('Website data will be download when submit');?>';
					_('.transfer_section .server_setting').hide();
				break;
				case 'online':
					tip = '<?php _e('Please enter FTP option of new hosting,SweetRice will transfer data to the server');?>';
					_('.transfer_section .server_setting').show();
				break;
			}
			if (tip){
				_('#tip').html(tip);
			}
		});
		_('.transfer_btn').bind('click',function(){
			var transfer_type = _('.options').val();
			if (!transfer_type)
			{
				_.ajax_untip('<?php _e('Please choose transfer type');?>');
				return ;
			}
			var ajax_dlg = false;
			var valid_server_setting = true;
			if (transfer_type == 'online')
			{
				_('.transfer_section .server_setting input').each(function(){
					if (_(this).attr('req') == 1 && !_(this).val())
					{
						valid_server_setting = false;
					}
				});
				if (!valid_server_setting)
				{
					return ;
				}
				ajax_dlg = _.dialog({'content':'<img src="../images/loading.gif"> <?php _e('Transfer website data maybe take long time,please wait for minutes.');?>'});
			}
			_.ajax({
				'type':'post',
				'data':{'transfer_type':transfer_type,'ftp_server':_('#ftp_server').val(),'ftp_port':_('#ftp_port').val(),'ftp_user':_('#ftp_user').val(),'ftp_password':_('#ftp_password').val(),'ftp_home':_('#ftp_home').val(),'_tkv_':_('#_tkv_').attr('value')},
				'url':'./?type=data&mode=transfer',
				'success':function(result){
					if (ajax_dlg)
					{
						ajax_dlg.remove();
					}
					if (result['status'] == 1 )
					{
						switch (transfer_type)
						{
							case 'download':
								location.href = result['url'];
							break;
							case 'online':
								alert('<?php _e('Transfer completed,enjoy new hosting');?>');
							break;
						}	
					}else{
						_.ajax_untip(result['status_code']);
					}
				}
			});
		});
	});
//-->
</script>