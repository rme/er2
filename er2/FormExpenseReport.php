
          <table width="100%" height="32" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
            <tr>
              <td width="50%" rowspan="2" valign="center"><img src="/images/processmaker.logo.jpg"/></td>
              <td width="50%" height="16" align="right" valign="top">
                <div align="right" class="logout">
                  <small>
                                      <label class="textBlue">expense report <a href="../users/myInfo">(expense)</a> | </label>
                                        <a href="/sysHSUx/en/classic/login/login" class="tableOption">Logout</a>&nbsp;&nbsp;<br/>
                    <label class="textBlack"><b></b> Using workspace <b><u>HSUx</u></b> &nbsp; &nbsp; <br/>
                    July 31, 2012</label>&nbsp; &nbsp;
                                    </small>
                </div>
              </td>
            </tr>
            <tr>
              <td width="50%" height="16" valign="bottom" class="title">
                <div align="right"></div>
              </td>
            </tr>
          </table>
<?php
//print '<iframe id="iframe-24" frameborder="0" style="width: 300px; height: 217px;" src="../information_export/timepanel/picker.php" scrolling="no"></iframe>';

?>
<script type="text/javascript">
	function fn_setTime()
	{
		sValue = <? echo "'".$_POST['TIME_VAL']."'"?>; 
		return sValue;
	}

	function fn_time_select(Tvalue)
	{
		
		iNumberRow = <? echo "'".$_POST['OID']."'"?>; //sValue
		document.getElementById('form[SUB_FLIGHT_SCHEDULE]['+iNumberRow+'][DEPART_TIMES]').value = Tvalue;
		oBusquedaNandinaPanel.remove();
		
	}
	
</script>
