<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include ("Funciones.php");
	if(!$Anio){$ND=getdate();$Anio = $ND[year];}
	if($Eliminar)
	{
		$cons = "Delete from Presupuesto.EstructuraPuc where Compania = '$Compania[0]' and Anio = '$Anio' and Nivel = '$Nivel'";
		$res = ExQuery($cons);
	}
	if($Nuevo){?><script language="javascript">location.href="NuevoConfEstructurapuc.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><? }
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
    <table border="0">
		<tr>
		  <td>A&ntilde;o</td>
		  <td><select name="Anio" onChange="FORMA.submit()" />
    	<?
    		$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' Order By Anio";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select></td>
		</tr>
	</table>
	<? 
	if($Anio)
	{ ?>
	<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
			<td>NoCaracteres</td><td>Detalle</td><td>Nivel</td><td colspan="2">&nbsp;</td>
		</tr>
		<?
		$cons = "Select NoCaracteres,Detalle,Nivel from Presupuesto.EstructuraPuc where compania='$Compania[0]' and Anio='$Anio' Order By Nivel";
		$res = ExQuery($cons);
		$NumFilas = ExNumRows($res);
		$Ult = 0;
		while ($fila = ExFetch($res))
		{
			$Ult++;
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
			echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td>";
			?><td width="16px">
               <a href="NuevoConfEstructurapuc.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Anio=<? echo $Anio?>&Nivel=<? echo $fila[2]?>">
               <img border=0 src="/Imgs/b_edit.png"></a></td><?
			if($Ult == $NumFilas)
			{
				?><td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
                {location.href='ConfEstructuraPUC.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Anio=<? echo $Anio?>&Nivel=<? echo $fila[2]?>';}">
				<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
			}
			else
			{
				echo "<td>&nbsp;</td>";
			}
			echo "</tr>";
		}
		?>
	</table>
	<input type="submit" value="Nuevo" name = "Nuevo"/>
	<? } ?>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>