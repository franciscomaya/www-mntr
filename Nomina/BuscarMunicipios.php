<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($Dep){
	$cons="Select codmpo,municipio from Central.Municipios,Central.Departamentos where Departamentos.Departamento='$Dep'
	and Departamentos.Codigo=Municipios.Departamento order by codmpo";
	//echo $cons;
	$res=ExQuery($cons);
?>
<script language='JavaScript'>
parent.document.FORMA.Mpo.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
		parent.document.FORMA.Mpo.options[<?echo $i?>].value="<? echo $fila[1]?>";
		parent.document.FORMA.Mpo.options[<?echo $i?>].text="<? echo $fila[1]?>";
		<? }?>
</script>
<?}?>