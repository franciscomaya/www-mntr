﻿	<?php
	$DatNameSID="SYS".rand(0,9999999);session_name("$DatNameSID");
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	
	session_register("usuario");
	session_register("Unidad");
	session_register("Compania");
	session_register("NoSistema");
	session_register("Sistema");	
	$ND=getdate();
	
	function definirCompania(){
		$consComp = "SELECT * FROM Central.compania";
		$resComp = ExQuery($consComp);
		$filaComp = ExFetchArray($resComp);
		$company = $filaComp['nombre'] ; 
		return $company;
	}
	
	
	
	

	//REDONDEO CIFRAS ALMACÉN
	$consred="UPDATE Consumo.TarifasxProducto SET ValorVenta = ceiling(ValorVenta)";
	$resred=ExQuery($consred);
	//ESTANCIA

	$nbd=getdate();
	if($nbd[mon]<10){$C1="0";}else{$C1="";}
	if($nbd[mday]<10){$C2="0";}else{$C2="";}
	//echo"$nbd[year]-$C1$nbd[mon]-$C2$nbd[mday] </br></br>";

	$cons="SELECT salud.servicios.cedula,salud.servicios.fechaing, pabellon,salud.servicios.numservicio FROM salud.servicios INNER JOIN salud.pacientesxpabellones ON salud.servicios.numservicio=salud.pacientesxpabellones.numservicio WHERE fechaing >= '1990-01-01' and fechaing <= '$nbd[year]-$C1$nbd[mon]-$C2$nbd[mday]' and salud.servicios.estado='AC' and salud.pacientesxpabellones.estado='AC' AND salud.servicios.tiposervicio='Hospitalizacion' order by fechaing desc";
	//echo "$cons </br></br>";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$aaaa3=substr("$fila[1]", -19,4);
		$mm3=substr("$fila[1]", -14,2);
		if($mm3<10){$mm3=str_replace("0","","$mm3");}
		$dd3=substr("$fila[1]", -11,2);
		if($dd3<10){$dd3=str_replace("0","","$dd3");}
		//echo "$aaaa3,$mm3,$dd3  </br></br>";
		$ano1 = $aaaa3; 
		$mes1 = $mm3; 
		$dia1 = $dd3; 
		$ano2 = "$nbd[year]"; 
		$mes2 = "$C1$nbd[mon]"; 
		$dia2 = "$C2$nbd[mday]"; 
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
		$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
		$segundos_diferencia = $timestamp1 - $timestamp2; 
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		$dias_diferencia = abs($dias_diferencia); 
		$dias_diferencia = floor($dias_diferencia); 
		$cons1="UPDATE salud.servicios SET diasestancia=$dias_diferencia WHERE salud.servicios.cedula='$fila[0]' and salud.servicios.fechaing='$fila[1]'";
		$res1=ExQuery($cons1);
		//echo "$res1";
	}
	//--->

	$Sistema="";
	//$contenido="123456\n001\n0";
	$DatosLic=explode("\n",$contenido);
	$Entidad="Clinica San Juan de Dios- Manizales";
	$NoId=strtoupper($DatosLic[1]);
	$NoLic=strtoupper(($DatosLic[2]));
	$NoSistema=$DatosLic[3];
	if(!$NoSistema){$NoSistema=0;}
	$Sistema[1]="Mentor Software";
	$usuario = array();
	$usuario[0]="";
	$usuario[1]="";
	session_register("AnioTrabajo");
	session_register("MesTrabajo");
	session_register("DiaTrabajo");
	$AnioTrabajo=$ND[year];$MesTrabajo=$ND[mon];$DiaTrabajo=$ND[mday];
	$cons="select mensaje,duracion from central.msjinstitucional";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	
	
	
	if(!$Refrescado&&$fila[0]){
		$NewCadena=str_replace("\r\n","<br>",$fila[0]);
		//echo $NewCadena;?>
		<html>
			<head>
				<meta HTTP-EQUIV="Refresh" CONTENT="<? echo $fila[1]?>; URL=clave.php?DatNameSID=<? echo $DatNameSID?>&Refrescado=1"></meta>
				<link rel="stylesheet" type="text/css" href="General/Estilos/estilos.css">
					<?php echo $codificacionMentor; ?>
						<?php echo $autorMentor; ?>
						<?php echo $titleMentor; ?>
						<?php echo $iconMentor; ?>
						<?php echo $shortcutIconMentor; ?>
			</head>

		<body>
			<center><br><br><br><br><br><br><font size=8 color='BLUE' style="text-align:center"><i><? echo $NewCadena?></i></font></center> 
		</body><?
	}
	else{	
		?>
		<html>
			<head>				
				<link rel="stylesheet" type="text/css" href="General/Estilos/estilos.css">
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				
			</head>
			
			<body>
						<span style="position: absolute; left:0px;">
							<?	
							if($NoSistema==1){
								echo "<img src='/Imgs/EntradaOH.jpg'>";
							}
							else{
								echo "<img src='/Imgs/EntradaOH.jpg'>";
							}
							?>
						</span>
						
						<?
						if($NoSistema==0){
							?>
							<div style="width:470px;border:0px;left:310px;width:470px;POSITION: absolute; TOP: 185px;">
							<center>
							<?	
						}
						else{
							?>
							<div style="width:470px;border:2px groove">
								<center>
								<?	
						}?>
						
						<script language="JavaScript">
							function Validar(){
								//if(document.FORMA.SelCompania.value==""){alert("Debe seleccionar una compañia");return false;}
								if(document.FORMA.Usuario.value==""){alert("Debe escribir un usuario");return false;}
								if(document.FORMA.Clave.value==""){alert("Debe escribir una contraseña");return false;}
							}
						</script>
					
						<form name="FORMA" id="FORMA" method="post" onSubmit="return Validar();">
							<br>
							<table style='font : normal normal 13px Tahoma;' border="0">
						
								<!--
								<tr>
										<td>Compa&ntilde;ia</td>
								<td colspan="2">
								<select tabindex="1" name="SelCompania" style="width:220px;"  tabindex="0">
								<option>
								<?php
									$cons="Select Nombre from Central.Compania Order By Nombre";
									$res=ExQuery($cons);echo ExError();			
									while($fila=ExFetch($res))
									{
										if(ExNumRows($res)==1&&empty($sel)){$sel="selected";$SelCompania=$fila[0];}
										if($fila[0]==$SelCompania){echo "<option selected value='$fila[0]' $sel>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
								?>
								</select>
								</td>
								</tr>-->		
						
							
								<tr>
									<td>&nbsp;</td>
									<td>
										<select name="Cargo" style = "width: 200px; font-size: 11px;" tabindex="1" onChange = "javascript: document.getElementById('FORMA').submit();">
												<option style="color: #999999;">SELECCIONAR CARGO</option>
												<?php
													$consCargo = "SELECT * FROM Salud.Cargos ORDER BY Cargos ASC";
													$resCargo = ExQuery($consCargo);
													while($fila=ExFetchArray($resCargo)){
														if($fila['cargos']==$_POST["Cargo"]) {
															echo "<option  value='$fila[cargos]' style='color:#333333;' selected >$fila[cargos]</option>";
														} else {
															echo "<option  value='$fila[cargos]' style='color:#333333;' >$fila[cargos]</option>";
														}	
													}
												?>
										</select>
									</td>
								</tr>
							
								<?php 
									$company = definirCompania();
								?>
							
								<input type="hidden" name="SelCompania" value="<?php echo $company;?>">
								<tr>
									<td></td>
									<td>
										<select name="Usuario" style = "width: 200px; font-size: 11px;" tabindex="2">
											<option style="color: #999999;">SELECCIONAR USUARIO</option>
											<?php
												$cons="Select Usuarios.nombre , Usuarios.usuario from Central.Usuarios, Salud.Medicos WHERE Usuarios.usuario = Medicos.usuario AND Medicos.cargo = '$Cargo' AND UPPER(Medicos.EstadoMed) = 'ACTIVO'  ORDER BY Usuarios.usuario ASC";
												$res=ExQuery($cons);
												while($fila=ExFetch($res))
												{
													echo "<option  value='$fila[1]' style='color:#333333;'>$fila[0]</option>";
												}	
											?>
										</select>
								
									</td>
									<!--<td rowspan="3"><a href="/Calidad/Index.php"><img src="/Imgs/Calidad/iso1.gif" width="39" border="0"  tabindex="45"></a></td>-->
							
								</tr>
								
								<?php
								if($Usuario) {
									//Verificar si ya se selecciono un usuario...
									$Clave=md5($Clave);
									$cons = "SELECT * FROM Central.Usuarios Where Usuario='$Usuario' And Clave='$Clave'";
								
									$resultado = ExQuery($cons,$conex);echo ExError();
									if(ExNumRows($resultado)>0)	{
										$fila=ExFetch($resultado);
										
										//echo $fila[0].$fila[1].$fila[2];
										
										$cons4="Select Cargo,RM,Cargo from Salud.Medicos where Usuario='$fila[0]'";
										$res4=ExQuery($cons4);
										$fila4=ExFetch($res4);
								
										$Compania[0]=$SelCompania;
										$usuario[0]=$fila[1];
										$usuario[1]=$fila[0];
										$usuario[2]=$fila[2];
										$usuario[3]=$fila4[0];
										$usuario[4]=$fila4[1];
										$usuario[5]=$fila4[2];
								
										$cons4="Select CambioClave from Central.Compania where Nombre='$Compania[0]'";
										$res4=ExQuery($cons4);
										$fila4=ExFetch($res4);
										$Periodicidad=$fila4[0];
								
										$cons2="Update Central.Usuarios set FechaUltimoAcceso='$ND[year]-$ND[mon]-$ND[mday]' where Usuario='$usuario[1]'";
										$res2=ExQuery($cons2);
								
										//Se va a la siguiente pagina... ?>
										<script language="Javascript">
											parent.location.href="Principal.php?DatNameSID=<? echo $DatNameSID?>";
										</script>
										<?	
									}
									else{
										$Msj="<em> <font color='#ff0000'>Autenticacion Incorrecta</font></em>";
									}
								}?>
								
									<tr valign="top">
										<td></td>
										<td>
											<!--[if !(IE)]><!--> <input type="password" maxlength="15" name="Clave" id="Clave" style="width:200px; font-size: 11px;" tabindex="3" placeholder="CONTRASEÑA"  onclick="replaceT(this)"> <!--<![endif]-->
											<!--[if (IE)]> <input type="text" maxlength="15" name="Clave" id="Clave" style="width:200px;" tabindex="3" placeholder="CONTRASEÑA" onclick="replaceT(this)"> <![endif]-->
										</td>
									</tr>
							</table>
							
							<br>
							
							<input type="submit" name="entrar" value="Continuar" class="Boton" tabindex=3>
							<!--<input type="button" name="salir" value="Cancelar" class="Boton" onClick="parent.location.href='/'"><BR><BR>-->
						</div>
						
							<div style="width:470px;"><em>
					</form>
					<script src="js/placeholders.min.js"></script>
						<script lang="javascript">
								 function replaceT(obj) {
									 var newO = document.createElement('input');
									 newO.setAttribute('type', 'password');
									 newO.setAttribute('name', obj.getAttribute('name'));
									 newO.style.setAttribute('width', '200px');
									 obj.parentNode.replaceChild(newO, obj);
									 newO.focus();
								 }
						</script>
			</body>
		</html>
		<? 
	}
	?>
	</html>

   