<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
        //Ult. Mod. 26-03-2011
	$cons="Select distinct Comprobante,Numero,Fecha,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,
	VrCosto,Movimiento.VrIVA,(TotCosto+Movimiento.VrIVA),Movimiento.UsuarioCre,TotCosto,Movimiento.Estado,Detalle,IncluyeIVA,
	PorcIVA,CompContable,NumCompCont,vrfactura,NoDocAfectado
	from Consumo.Movimiento,Consumo.CodProductos 
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
	and CodProductos.Anio = $Anio";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$CompContable=$fila[19];$NumCompConta=$fila[20];$VrDescrito=$fila[21];
	$Fecha=$fila[0];$Usuario=$fila[13];$Detalle=$fila[16];
	if($fila[15]=="AN"){echo "<img width='100%' height='100%' style='position:absolute;'  src='/Imgs/Anulado.gif'>";}
	
	$cons2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono from Central.Terceros where Identificacion='$fila[8]' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	$fila2=ExFetch($res2);
	$NomTercero="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";$Direccion=$fila2[4];$Telefono=$fila2[5];
	
?>
<head>
	<title><?echo $Sistema[$NoSistema]?></title>
</head>

<body background="/Imgs/Fondo.jpg">
<center><font style="font : 15px Tahoma;font-weight:bold">
<? echo strtoupper($Compania[0])?><br /></font>
<font style="font : 12px Tahoma;">
<? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br />
</center></strong></font>
<br />
<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td bgcolor="#e5e5e5">Fecha y Hora </td><td><?echo $fila[2]?></td>
<td></td><td></td>
<td rowspan="4">
<table bordercolor='#e5e5e5' border="1" style='font : normal normal small-caps 14px Tahoma;' align="right">
<tr><td bgcolor="#e5e5e5" align="center"><font size="4"><strong><? echo $fila[0]?></font></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>
</td>
</tr>


<tr><td bgcolor="#e5e5e5">Proveedor</td><td><?echo $NomTercero?></td><td bgcolor="#e5e5e5">Identificacion</td><td><? echo $fila[8]?></td>
<tr><td bgcolor="#e5e5e5">Direccion</td><td><?echo $Direccion?></td>
<td bgcolor="#e5e5e5">Telefono</td><td><?echo $Telefono?></td>

<tr>
<td bgcolor="#e5e5e5">Detalle</td><td colspan="3"><? echo $Detalle?></td>
</tr>
<? if($Comprobante == "Entradas"){
?><tr>
<td bgcolor="#e5e5e5">No Factura</td><td colspan="3"><? echo "$NoFactura ($ ".number_format($VrDescrito,2).")"?></td>
</tr><?
}?>
</table>

<br /><br />
<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>OC</td><td>Codigo</td><td>Nombre</td><td align="right">Cantidad</td><td>Vr Unidad</td><td>Vr IVA</td><td>Total</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if(!$fila[11] || $fila[11] == 0)
		{
			if($fila[17]==1)
			{
                            //$fila[11]: TotalIva      $fila[14]:SUBTOTAL    $fila[18]:porcIVA
                            $fila[11] = $fila[14] - ($fila[14]/(($fila[18]/100)+1));
                            $fila[14] = $fila[14] - $fila[11];
                            $fila[12] = $fila[14] + $fila[11];
			}
		}
				
		
		echo "<tr><td>$fila[22]</td><td>$fila[3]</td><td>$fila[5] $fila[6] $fila[7]</td><td align='right'>$fila[4]</td>
                <td align='right'>".number_format($fila[14]/$fila[4],2).
                "</td><td align='right'>".number_format($fila[11]/$fila[4],2)."</td><td align='right'>".number_format($fila[14]+$fila[11],2)."</td></tr>";
		$SubTotal=$SubTotal+$fila[14];
		$IVA=$IVA+$fila[11];
		$Total=$Total+$fila[12];
	}

	$cons82="Select CompRemision,NoCompRemision  from Consumo.EntradasxRemisiones 
	where Compania='$Compania[0]' and CompEntrada='$Comprobante' and NoCompEntrada='$Numero' and Compania='$Compania[0]'";
	$res82=ExQuery($cons82);echo ExError();
	if(ExNumRows($res82)>0)
	{
		while($fila82=ExFetch($res82))
		{
		echo "<tr><td colspan=6 align='center' bgcolor='#e5e5e5'><strong>$fila82[0] $fila82[1]</td></tr>";?>
			<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Codigo</td><td>Nombre</td><td align="right">Cantidad</td><td>Vr Unidad</td><td>Vr IVA</td><td>Total</td></tr>
<?			$cons90="Select Comprobante,Numero,Fecha,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,Cedula,Movimiento.AutoId,VrCosto,Movimiento.VrIVA,(TotCosto+Movimiento.VrIVA),
			Movimiento.UsuarioCre,TotCosto,IncluyeIVA,PorcIVA
			from Consumo.Movimiento,Consumo.CodProductos 
			where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
			and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$fila82[0]' and Numero='$fila82[1]'
			and CodProductos.Anio=$Anio and Movimiento.Anio=$Anio";
			$res90=ExQuery($cons90);echo ExError();
			while($fila90=ExFetch($res90))
			{
				echo "<tr><td>$fila90[3]</td><td>$fila90[5] $fila90[6] $fila90[7]</td><td align='right'>$fila90[4]</td><td align='right'>".number_format($fila90[10],2)."</td><td align='right'>".number_format($fila90[11],2)."</td><td align='right'>".number_format($fila90[12],2)."</td></tr>";	
				$SubTotal=$SubTotal+$fila90[14];
				$IVA=$IVA+$fila90[11];
				$Total=$Total+$fila90[12];
			} 
		}
	}
	
	
	echo "<tr><td colspan=4></td><td bgcolor='#e5e5e5'><strong>SUBTOTAL</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($SubTotal,2)."</td></tr>";
	echo "<tr><td colspan=4></td><td bgcolor='#e5e5e5'><strong>IVA</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($IVA,2)."</td></tr>";
	echo "<tr><td colspan=4></td><td bgcolor='#e5e5e5'><strong>TOTAL</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($Total,2)."</td></tr>";
	$Letras=NumerosxLet($Total);
	
	$cons55="Select Mensaje1 from Consumo.Comprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante'";
	$res55=ExQuery($cons55);echo ExError();
	$fila55=ExFetch($res55);
?>
<tr><td colspan="7">SON: <font size="-2"><? echo strtoupper($Letras)?></td></tr>
<tr><td colspan="7">
<table bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor="#e5e5e5"><td colspan="7" align="center"><strong><? echo ("$CompContable <br>$NumCompConta")?></td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Concepto</td><td align="right">Debitos</td><td align="right">Creditos</td><td>Doc Soporte</td><td>Base</td></tr>
<?
	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Movimiento.Cuenta,Debe,Haber,CC,DocSoporte,'',Estado,UsuarioCre,nombre,BaseGravable
	from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta and Movimiento.Anio=PlanCuentas.Anio and PlanCuentas.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]' and 
	Comprobante='$CompContable' and Numero='$NumCompConta' and Estado='AC' Order By Debe Desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[6]</td><td>".substr($fila[14],0,45)."</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td>
			<td align='center'>$fila[10]</td><td align='right'>".number_format($fila[15],2)."</td>
		</tr>";
		$TotDebe=$TotDebe+$fila[7];$TotHaber=$TotHaber+$fila[8];
	}
	echo "<tr align='right' bgcolor='#e5e5e5' style='font-weight:bold'><td colspan=2>SUMAS</td><td>".number_format($TotDebe,2)."</td><td>".number_format($TotHaber,2)."</td></tr>";
?>



</table>
</table>

<br /><br /><br />
<table border="0" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td>_________________________________<br /><strong><?echo $Usuario?>
</table><br><font size="-1">
<?
	echo "<em>$fila55[0]</em>";
?></font>
<img src="/Imgs/Logo.jpg" style="width: 60px; height: 60px; position: absolute; top: 3px; left: 60px;" />
</body>