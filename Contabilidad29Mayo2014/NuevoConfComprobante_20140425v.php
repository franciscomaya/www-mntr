<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Guardar)
	{
		if($Retencion=="on"){$VrRetencion=1;}else{$VrRetencion=0;}
		if($CuentaCero=="on"){$VrCuentaCero=1;}else{$VrCuentaCero=0;}
		if($Cierre=="on"){$VrCierre=1;}else{$VrCierre=0;}
		if($Acarreo=="on"){$VrAcarreo=1;}else{$VrAcarreo=0;}
                if($Depreciacion=="on"){$VrDepreciacion=1;}else{$VrDepreciacion=0;}
		if($CompPresupuestal){$CompPresupuestal="'$CompPresupuestal'";}else{$CompPresupuestal="NULL";}
		if($CompPresupuestalAdc){$CompPresupuestalAdc="'$CompPresupuestalAdc'";}else{$CompPresupuestalAdc="NULL";}
		if($Editar==0)
		{
			$cons="Insert into Contabilidad.Comprobantes(Comprobante,TipoComprobant,Retencion,NumeroInicial,Compania,CruceCtaCero,
                            Formato,CompPresupuesto,CompPresupuestoAdc,Cierre,Acarreo,Depreciacion)
			values ('".trim($Comprobante)."','$TipoComprobante','$VrRetencion','$NoInicial','$Compania[0]','$VrCuentaCero',
                            '$Formato',$CompPresupuestal,$CompPresupuestalAdc,'$VrCierre','$VrAcarreo',$VrDepreciacion)";
			//echo $cons;
			//exit;
			
		}
		if($Editar==1)
		{
			$cons="Update Contabilidad.Comprobantes set TipoComprobant='$TipoComprobante',Retencion='$VrRetencion',NumeroInicial='$NoInicial',
			CruceCtaCero='$VrCuentaCero',Formato='$Formato',
			CompPresupuesto=$CompPresupuestal,CompPresupuestoAdc=$CompPresupuestalAdc,Cierre='$VrCierre', Acarreo='$VrAcarreo', Depreciacion=$VrDepreciacion
			where Comprobante='$Comprobante' and Compania='$Compania[0]'";		
		}
		$res=ExQuery($cons);
		echo ExError($res);
		?>
		<script language="javascript">
			location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
		<?
	}
	
	if($Editar)
	{
		$cons="Select * from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$Comprobante=$fila['comprobante'];
		$TipoComprobante=$fila['tipocomprobant'];$NoInicial=$fila['numeroinicial'];$Formato=$fila['formato'];
		$CompPresupuestal=$fila['comppresupuesto'];$CompPresupuestalAdc=$fila['comppresupuestoadc'];
		$Retencion=$fila['retencion'];$CuentaCero=$fila['crucectacero'];$Cierre=$fila['cierre'];$Acarreo=$fila['acarreo'];
                $Depreciacion = $fila['depreciacion'];
		
	}

?>
	<script language="javascript" src="/Funciones.js"></script>
	<script language="javascript">
	function Validar()
	{
		if (document.FORMA.Comprobante.value == ""){alert("Ingrese un nombre de comprobante");return false;}
		else{if (document.FORMA.TipoComprobante.value == ""){alert("Escoja un Tipo de comprobante");return false;}
		     else{if (document.FORMA.NoInicial.value == ""){alert("Ingrese un Numero Inicial");return false;}
				  else {if (document.FORMA.Formato.value == ""){alert("Ingrese el Formato");return false;}
      					else {return true}}}}
	}
	</script>


<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">

<tr><td bgcolor="#e5e5e5">Nombre Comprobante</td><td colspan="3"><input style="width:300px;" type="text" name="Comprobante" value="<?echo $Comprobante?>"/></td>
<tr><td bgcolor="#e5e5e5">Tipo Comprobante</td>
<td colspan="3">
<select name="TipoComprobante">
<option></option>
<?
	$cons="Select Tipo from Contabilidad.TiposComprobante";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($TipoComprobante==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	if(!$NoInicial){$NoInicial="000001";}
?>
</select>
</td>
<tr><td bgcolor="#e5e5e5">Retencion</td>
<?if($Retencion=='1'){?><td><input type="checkbox" checked="yes" name="Retencion"/><?}
else{?><td><input type="checkbox" name="Retencion"/></td><?}?>
<td bgcolor="#e5e5e5">Cuenta Cero</td>
<?if($CuentaCero==1){?><td><input type="checkbox" checked="yes" name="CuentaCero"/><?}
else{?><td><input type="checkbox" name="CuentaCero"/></td></tr><?}?>

<tr><td bgcolor="#e5e5e5">Cierre Fiscal</td>
<?if($Cierre==1){?><td><input type="checkbox" checked="yes" name="Cierre"/><?}
else{?><td><input type="checkbox" name="Cierre"/></td><?}?>

<td bgcolor="#e5e5e5">Acarreo</td>
<?if($Acarreo==1){?><td><input type="checkbox" checked="yes" name="Acarreo"/><?}
else{?><td><input type="checkbox" name="Acarreo"/></td></tr><?}?>


<tr><td bgcolor="#e5e5e5">Numero Inicial</td>
<td><input type="text" style="width:100px;" maxlength="6" name="NoInicial" value="<?echo $NoInicial?>"/></td>
<td bgcolor="#e5e5e5">Depreciaci&oacute;n</td>
<td><input type="checkbox"
           <? if($Depreciacion==1){echo " checked ";}?>
           name="Depreciacion" title="Comprobante para depreciacion de infraestructura"/></td>


<tr><td bgcolor="#e5e5e5">Formato</td>
<td colspan="3">
<select name="Formato">
<?
	$RutaRoot=$_SERVER['DOCUMENT_ROOT'];
    $midir=opendir("$RutaRoot/Informes/Contabilidad/Formatos/");
	while($files=readdir($midir))
    {
		$ext=substr($files,-3);
		if (!is_dir($files))
		{
			$files="Formatos/".$files;
		}
		if($files!="." && $files!=".." && $ext=="php"){
		if($files==$Formato){echo "<option selected value='$files'>$files</option>";}
		else{echo "<option value='$files'>$files</option>";}}
      }

?>
</select>
<tr><td bgcolor="#e5e5e5">Comprobante Presupuestal</td>
<td colspan="3">
<select name="CompPresupuestal">
<option></option>
	<?
	$cons="Select Comprobante from Presupuesto.Comprobantes where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if(strtolower($CompPresupuestal)==strtolower($fila[0])){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	?>
</select>

</td>

<tr><td bgcolor="#e5e5e5">Comprobante Presupuestal Adicional</td>
<td colspan="3">
<select name="CompPresupuestalAdc">
<option></option>
	<?
	$cons="Select Comprobante from Presupuesto.Comprobantes where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if(strtolower($CompPresupuestalAdc)==strtolower($fila[0])){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	?>
</select>

</td>
<input type="hidden" name="Editar" value="<?echo $Editar?>"/>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="submit" value="Guardar" name="Guardar"/>
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&';"/>
</form>