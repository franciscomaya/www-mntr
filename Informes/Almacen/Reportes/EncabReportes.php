<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$AnioAc=$ND[year];

	if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
	$AnioInc=$AnioAc-10;
	$AnioAf=$AnioAc+10;
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Ampliar()
	{
		parent.document.getElementById('Superior').rows="100%,*";
	}
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<table>
<tr><td>
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td>
<select name="Seleccion" onChange="location.href='EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Seleccion=' + this.value+'&Tipo=<? echo $Tipo?>'">
<option value=""></option>
<?
	$Clase=$Tipo;
        $cons="Select Nombre from Central.Reportes where Clase='$Tipo' and Modulo='Consumo' Order By Id";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		if($Seleccion==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select></td></td><td>
<table  border="1" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?
	if($Seleccion){
	$cons="Select Tipo,Archivo,id from Central.Reportes where Nombre='$Seleccion'  and Modulo='Consumo'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Tipo=$fila[0];
	$NomArchivo=$fila[1];
	$id=$fila[2];
	$cons2="Select sum(NoCaracteres) from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);
	$NoDigitos=$fila2[0];
	if(!$NoDigitos){$NoDigitos=0;}
	if($Tipo==1&&$id!=3)
	{
            echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
            echo "<tr bgcolor='#e5e5e5' align='center'><td><center>Periodo Inicial</td><td>Periodo Final</td><td>Almacen Ppal</td><td>PDF</td>";
            if($Seleccion=="Conteo Productos"){echo "<td>Saldos</td><td>Bodega</td><td>Estante</td><td>Nivel</td>";}
            if($Seleccion=="Libro de Medicamentos de control" || $Seleccion == "Formulas de Medicamentos de control")
            {
                echo "<td>Medicamento</td>";
            }
            echo "</tr>";
            ?>
		<tr>
                <td>
                    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
                    <select name="Anio"><?
                        for($i=$AnioInc;$i<$AnioAf;$i++)
                        if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
                        else{echo "<option value=$i>$i</option>";}
		?>  </select>
		<select name="MesIni">
		<? for($i=1;$i<=12;$i++)
		{
			if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?>
		</select>
		<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value='01'>

		</td>
		<td>
		<select name="MesFin">
		<? for($i=1;$i<=12;$i++)
		{
			if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?>
		</select>
		<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<? echo $ND[mday]?>'></td>
        <td>
        <select name="AlmacenPpal" <? if($Seleccion=="Conteo Productos")
            { ?> onchange="document.frames.Auxiliar.location.href='Auxiliar.php?DatNameSID=<? echo $DatNameSID?>&Tipo=ConteoProductos&AlmacenPpal='+this.value"<? }
            if($Seleccion=="Libro de Medicamentos de control" || $Seleccion == "Formulas de Medicamentos de control"){?>onchange="document.frames.Auxiliar.location.href='Auxiliar.php?DatNameSID=<? echo $DatNameSID?>&Tipo=LibMedsControl&AlmacenPpal='+this.value"<?}?> >
            <option></option>
<?                      if($Clase=="Farmacia"){$AdCons = "And SSFarmaceutico=1";}
                        $cons = "Select AlmacenesPpales.AlmacenPpal from Consumo.UsuariosxAlmacenes,Consumo.AlmacenesPpales
                        where Usuario='$usuario[1]' and AlmacenesPpales.Compania='$Compania[0]' and UsuariosxAlmacenes.Compania='$Compania[0]'
                        and UsuariosxAlmacenes.AlmacenPpal=AlmacenesPpales.AlmacenPpal $AdCons";
                        $res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($AlmacenPppal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
?>
           </select>
		   
        </td>
                <td><input type="checkbox" name="PDF" value="1"></td>
		<? if($Seleccion=="Conteo Productos"){ ?>
        		<td><input type="checkbox" name="Saldos" value="1"></td>
                        <td><select name="Bodegas"></select></td>
                        <td><select name="Estantes"></select></td>
                <? }
                   if($Seleccion=="Libro de Medicamentos de control" || $Seleccion == "Formulas de Medicamentos de control")
                   {
                       ?>
                        <td><select name="Medicamento"></select></td>
                       <?
                   }
                ?>
        </tr>
		<?
		
	}
	if($Tipo==2)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td colspan='2'>Corte</td><td>Almacen Ppal</td><td>PDF</td>";
		echo "</tr>";
		?>
		<tr>
			<td>
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
				<select name="Anio"><?
					for($i=$AnioInc;$i<$AnioAf;$i++)
					if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
					else{echo "<option value=$i>$i</option>";}
				?>  </select>
			</td>
			<td>
            <select name="MesFin">
            <? for($i=1;$i<=12;$i++)
            {
                if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
                else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
            }
            ?>
            </select>
            <? if($Seleccion!="Formula de Medicamentos de control consolidado")
            {
            ?>
            <input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<? echo $ND[mday]?>'></td>
            <?}?>
            <td>
            <select name="AlmacenPpal">
        	<?
                if($Clase){$AdCons = " And SSFarmaceutico=1";}
                $cons = "Select AlmacenesPpales.AlmacenPpal from Consumo.UsuariosxAlmacenes,Consumo.AlmacenesPpales
                where Usuario='$usuario[0]' and UsuariosxAlmacenes.Compania='$Compania[0]' and AlmacenesPpales.Compania='$Compania[0]'
                and UsuariosxAlmacenes.AlmacenPpal = AlmacenesPpales.AlmacenPpal $AdCons";
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    if($AlmacenPppal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }
        	?>
            </select>
            </td>
			<td><input type="checkbox" name="PDF" value="1"></td>
		</tr>
	<?
	}
        }
        
?>
</table>
</td>
<td><?if($Tipo==1&&$id!=3){?>
<table border="0" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr><td align="center">Filas</td></tr>
<tr><td><input type="Text" name="Encabezados" style="width:40px;" value="50"></td></tr>
</table><?}?>
</td>
<td><?if($Tipo==1&&$id!=3){?><input type="Submit" name="Ver" value="Ver" onClick="Ocultar();parent.document.getElementById('Superior').rows='83,*';"><?}
      if($Tipo==1&&$id==3){?><script>parent.Abajo.location.href='SalidasXCP.php?DatNameSID=<? echo $DatNameSID?>'</script><?}
	  else{?><script>parent.Abajo.location.href='SalidasXCP.php'</script><?}
?></td>
<?
	if($Seleccion=="Salidas Por CC Por Producto"){$Filtrar = 1;}
	if($Filtrar)
	{
	?><td><input type="button" name="Filtrar" value="Filtrar" onClick="Ampliar()"></td><?
	}
?>
</table>
<?
	if($Seleccion=="Salidas Por CC Por Producto")
	{
		?>
        <input type="hidden" name="AutoId" value="<? echo $AutoId?>"  />
        <table border="0" style='font : normal normal small-caps 11px Tahoma;' align="center">
        <tr bgcolor="#e5e5e5" style="font-weight:bold">
            <td>CENTRO DE COSTOS:</td>
            <td><input type="text" name="CC" onBlur="campoNumero(this)" value="<? echo $CC?>" size="60" style="text-align:right; width:150px"
            onfocus="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+Anio.value+'-'+MesIni.value+'-'+DiaIni.value+'&FechaFin='+Anio.value+'-'+MesFin.value+'-'+DiaFin.value+'&AutoId='+FORMA.AutoId.value+'&Anio='+Anio.value+'&Tipo=CC&CC='+this.value"
            onkeyup="Mostrar();xNumero(this);Nombre.value='';
            document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+Anio.value+'-'+MesIni.value+'-'+DiaIni.value+'&FechaFin='+Anio.value+'-'+MesFin.value+'-'+DiaFin.value+'&AutoId='+FORMA.AutoId.value+'&Anio='+Anio.value+'&Tipo=CC&CC='+this.value;
            if(this.value==''){FORMA.Cedula.value='';FORMA.submit();}" onKeyDown="xNumero(this)" />
            <input type="text" name="Nombre" readonly value="<? echo $Nombre?>" />
            </td>
            <td>PRODUCTO:</td>
            <td><input type="text" name="Producto" value="<? echo $Producto?>" size="60" 
            onfocus="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+Anio.value+'-'+MesIni.value+'-'+DiaIni.value+'&FechaFin='+Anio.value+'-'+MesFin.value+'-'+DiaFin.value+'&CC='+FORMA.CC.value+'&AlmacenPpal='+AlmacenPpal.value+'&Anio='+Anio.value+'&Tipo=Productos&Producto='+this.value"
            onkeyup="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+Anio.value+'-'+MesIni.value+'-'+DiaIni.value+'&FechaFin='+Anio.value+'-'+MesFin.value+'-'+DiaFin.value+'&CC='+FORMA.CC.value+'&AlmacenPpal='+AlmacenPpal.value+'&Anio='+Anio.value+'&Tipo=Productos&Producto='+this.value;
            if(this.value==''){FORMA.AutoId.value='';FORMA.submit();}"/></td>
        </tr>
        <tr bgcolor="#e5e5e5" style="font-weight:bold">
            <td>GRUPO</td>
            <td>
                <input type="text" name="Grupo" value="<? echo $Producto?>" size="60"
                onfocus="Mostrar();
                document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+Anio.value+'-'+MesIni.value+'-'+DiaIni.value+'&FechaFin='+Anio.value+'-'+MesFin.value+'-'+DiaFin.value+'&CC='+FORMA.CC.value+'&AlmacenPpal='+AlmacenPpal.value+'&Anio='+Anio.value+'&Tipo=Grupos&Grupo='+this.value"
                onkeyup="Mostrar();
                document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+Anio.value+'-'+MesIni.value+'-'+DiaIni.value+'&FechaFin='+Anio.value+'-'+MesFin.value+'-'+DiaFin.value+'&CC='+FORMA.CC.value+'&AlmacenPpal='+AlmacenPpal.value+'&Anio='+Anio.value+'&Tipo=Grupos&Grupo='+this.value;"/>
            </td>
        </tr>
    </table><?
	}
?>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:'none';" src="Busquedas.php" frameborder="1" scrolling="yes" style="border:#e5e5e5" height="400"></iframe>
<iframe id="Auxiliar" name="Auxiliar" style=" display: none" src="Auxiliar.php"></iframe>
</body>