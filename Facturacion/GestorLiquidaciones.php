<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($NoLiq){		
		$cons="update facturacion.liquidacion set estado='AN' where compania='$Compania[0]' and noliquidacion=$NoLiq";
		//echo $cons;
		$res=ExQuery($cons);		
		/*$cons="update salud.plantillaprocedimientos set noliquidacion=NULL
		where plantillaprocedimientos.compania='$Compania[0]' and cedula='$Paciente[1]' and plantillaprocedimientos.estado='AC' and noliquidacion=$NoLiq";
		$res=ExQuery($cons);
		$cons="select tblformat from historiaclinica.formatos where estado='AC' and compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){
			$cons5=$cons5." update  histoclinicafrms.$fila[0] set noliquidacion=0
			where $fila[0].compania='$Compania[0]' and $fila[0].cedula='$Paciente[1]' and $fila[0].cup is not null  and noliquidacion=$NoLiq ";					
			$res=ExQuery($cons5); echo ExError();
		}	
		$cons="update consumo.movimiento set noliquidacion=NULL where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'
		and noliquidacion=$NoLiq and tipocomprobante='Salidas' and almacenppal in(select almacenppal from consumo.almacenesppales where compania='$Compania[0]'
		and ssfarmaceutico=1)";
		$res=ExQuery($cons);*/
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar(){
		if(document.FORMA.FechaIni.value==""){alert("Debe seleccionar la fecha inicial!!");return false;}
		if(document.FORMA.FechaFin.value==""){alert("Debe seleccionar la fecha Final!!");return false;}
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){alert("La fecha inicial debe ser mayor o igual a la fecha final");return false;}		
		if(document.FORMA.Hasta.value!=""){
			if(document.FORMA.Desde.value!=""){
				if(parseInt(document.FORMA.Desde.value)>document.FORMA.Hasta.value){
					alert("El numero de factura inicial debe ser menor al numero final!!!");return false;
				}
			}
		}	
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td colspan="12">Gestion de Liquidaciones</td>
	</tr>
     <tr>    	
     	<?
        if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}else{$C1="";}			
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaFin?>"></td>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
   	 <?	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td colspan="7">
        	<select name="Entidad" onChange="document.FORMA.submit()"<><option></option>
      	<?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Entidad==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[1]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[1]?></option>
              <? }
			  }?>
             </select>
        </td>
  	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
  	<?	$cons="Select contrato from contratacionsalud.contratos
		where contratos.compania='$Compania[0]' and entidad='$Entidad'";
		//echo $cons;?>
        <td>
        	<select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Contrato==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
              <? }
			  }?>
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>
  	<?	$cons="Select numero from contratacionsalud.contratos
		where contratos.compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'";
		//echo $cons;?>
        <td>
        	<select name="NoContrato" onChange="document.FORMA.submit()"><option></option>
         <?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($NoContrato==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
              <? }
			  }?>
            </select>
        </td>   
    	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>    
		<td>
	         <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   			</select>
       	</td>
        <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo</td>    
        <td>
        	<select name="Tipo">
            	<option value="Todas" <? if($Tipo=="Todas"){?> selected<? }?>>Todas</option>
                <option value="Facturadas" <? if($Tipo=="Facturadas"){?> selected<? }?>>Facturadas</option>
                <option value="Sin Facturar" <? if($Tipo=="Sin Facturar"){?> selected<? }?>>Sin Facturar</option>
                <option value="Anuladas" <? if($Tipo=="Anuladas"){?> selected<? }?>>Anuladas</option>
                <option value="Activas" <? if($Tipo=="Activas"){?> selected<? }?>>Activas</option>
            </select>
        </td>
        <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Liquidaciones</td>    	
        <td>
        	<input type="text" name="Desde" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $Desde?>" style="width:80">
        </td>
        <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">A</td>    	
        <td>
        	<input type="text" name="Hasta" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $Hasta?>" style="width:80">
        </td>
	</tr>
    <tr>
    	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Ordenar Por</td>
        <td>
        	<select name="OrdenarPor">
            	<option value="NoLiq" <? if($OrdenarPor=="NoLiq"){?> selected<? }?>>No. Liquidacion</option>
            	<option value="Entidad" <? if($OrdenarPor=="Entidad"){?> selected<? }?>>Entidad</option>
                <option value="NomPac" <? if($OrdenarPor=="NomPac"){?> selected<? }?>>Nombre Paciente</option>
                <option value="IdPac" <? if($OrdenarPor=="IdPac"){?> selected<? }?>>Id Paciente</option>
                <option value="NoFac" <? if($OrdenarPor=="NoFac"){?> selected<? }?>>No. Factura</option>
            </select>
        </td>
        <td align="left" colspan="10">Ver Detalles <input type="checkbox" name="VerDet" <? if($VerDet){?> checked<? }?>></td>        
    </tr>
    <tr align="center">
    	<td colspan="12"><input type="submit" value="Ver" name="Ver"></td>
    </tr>
</table>
<input type="hidden" name="NoLiq">
</form>    
<? if($Ver){?>
<iframe frameborder="0" id="VerLiquidado" src="VerLiquidado.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ambito=<? echo $Ambito?>&Tipo=<? echo $Tipo?>&Desde=<? echo $Desde?>&Hasta=<? echo $Hasta?>&OrdenarPor=<? echo $OrdenarPor?>&VerDet=<? echo $VerDet?>" width="100%" height="85%">
</iframe>
<? }?>
</body>
</html>
