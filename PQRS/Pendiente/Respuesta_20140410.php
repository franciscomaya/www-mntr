<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    
    $conspqrs="select * from pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs where pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs and clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs and pqrs.id_pqrs=$pqrs";
    $respqrs=ExQuery($conspqrs);
    $filapqrs=ExFetchAssoc($respqrs);
    
    if($Guardar){
        $fecha_respuesta = date("Y-m-d H:i:s");
        $id_secuencia = array_shift(array_keys($Guardar));
        
        // Consulto la secuencia para comprobar si requiere de visto bueno y no se ha creado ningún visto bueno para esa respuesta
        $conssec="SELECT * FROM pqrs.secuencia, pqrs.respuesta 
            WHERE secuencia.id_secuencia=respuesta.id_secuencia 
                AND secuencia.id_secuencia=".$id_secuencia." 
                AND reqvobo_secuencia=1
                AND vobo_respuesta IS NULL";
        $ressec=ExQuery($conssec);
        $filasec=ExFetchAssoc($ressec);
        
        if(ExNumRows($ressec)>0){
            $cons="update pqrs.respuesta set vobo_respuesta='".$comentario_respuesta."', vobofecha_respuesta='".$fecha_respuesta."', vobousuario_respuesta='".$usuario[1]."', estado_respuesta=1 where respuesta.id_respuesta='".$id_respuesta."'";
            $res=ExQuery($cons);
        }
        else{
            $cons="insert into pqrs.respuesta(comentario_respuesta, fecha_respuesta, id_secuencia, id_pqrs) values ('".$comentario_respuesta."', '".$fecha_respuesta."', ".$id_secuencia.", ".$pqrs.")";
            $res=ExQuery($cons);
        }
    }
    /*if($editar){
        $cons="select * from pqrs.respuesta where id_respuesta=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $fecha_respuesta = date("Y-m-d H:i:s");
        $cons="update pqrs.respuesta set comentario_respuesta='$comentario_respuesta', id_estadorespuesta='$id_estadorespuesta', id_pqrs='$pqrs', fecha_respuesta='$fecha_respuesta' where id_respuesta=$id_respuesta";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }*/
    /*if($eliminar){
        $cons2="delete from pqrs.estadoxtipo where id_tipopqrs='".$eliminar."'";
        $res2=ExQuery($cons2);
        
        $cons="delete from pqrs.tipopqrs where id_tipopqrs=$eliminar";
        $res=ExQuery($cons);
    }*/
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <?php //echo "PQRS Actual".$pqrs; ?>
        <form name='FORMA' method="post" action="/PQRS/Respuesta.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">RESPUESTAS PQRS</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TIPO</td>
                            <td colspan=4 style="text-align: left;"><?php echo $filapqrs['nombre_tipopqrs']; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">ASUNTO</td>
                            <td colspan=4 style="text-align: left;"><?php echo $filapqrs['asunto_pqrs']; ?></td>
                        </tr>                        
                        <tr>
                            <td style="text-align: right;">COMENTARIO</td>
                            <td><textarea name="comentario_respuesta" id="comentario_respuesta" cols="60"><?php echo $fila['comentario_respuesta']; ?></textarea></td>
                            <!--<td><input type="text" name="comentario_respuesta" id="comentario_respuesta" value="<?php echo $fila['comentario_respuesta']; ?>" style="width:200px;"></td>-->
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_respuesta" id="id_respuesta" value="<?php echo $editar; ?>">
                                <?php
                                }
                                else{
                                    // Consulto las respuestas creadas para esta petición
                                    $cons2="SELECT * FROM pqrs.respuesta, pqrs.pqrs 
                                        WHERE pqrs.id_pqrs=respuesta.id_pqrs
                                            AND pqrs.id_pqrs=$pqrs
                                        ORDER BY respuesta.fecha_respuesta DESC
                                        LIMIT 1";
                                    $res2=ExQuery($cons2);
                                    $fila2=ExFetchAssoc($res2);
                                    //echo "<br>";
                                    
                                    if(ExNumRows($res2)){
                                        // Consulto la secuencia para comprobar si requiere de visto bueno y no se ha creado ningún visto bueno para esa respuesta
                                        $conssec="SELECT * FROM pqrs.secuencia, pqrs.respuesta 
                                            WHERE secuencia.id_secuencia=respuesta.id_secuencia 
                                                AND secuencia.id_secuencia=".$fila2['id_secuencia']." 
                                                AND reqvobo_secuencia=1
                                                AND vobo_respuesta IS NULL";
                                        $ressec=ExQuery($conssec);
                                        $filasec=ExFetchAssoc($ressec);
                                    }
                                    
                                    if(ExNumRows($ressec)>0){
                                        // Consultar el nivel jerárquico padre para validar si puede guardar el VoBo
                                        $cons3="SELECT padre_jerarquia FROM pqrs.jerarquia, central.usuarios 
                                            WHERE jerarquia.id_jerarquia=usuarios.id_jerarquia 
                                                AND usuarios.usuario='".$filapqrs['gestor_pqrs']."'";
                                        $res3=ExQuery($cons3);
                                        $fila3=ExFetchAssoc($res3);
                                        
                                        // Busca usuarios en la jerarquia consultada anteriormente
                                        $cons4="SELECT * FROM central.usuarios 
                                            WHERE usuarios.id_jerarquia=".$fila3['padre_jerarquia']."";
                                        $res4=ExQuery($cons4);
                                        
                                        // si encuentra 2 o más secuencias asignadas x usuario
                                        if(ExNumRows($res4)>0){
                                            // Si el usuario está en el listado anterior permita la edición
                                            while($fila4=ExFetchAssoc($res4)){
                                                if($usuario[1]==$fila4['usuario']){
                                                    ?>
                                                        <input type="hidden" id="id_respuesta" name="id_respuesta" value="<?php echo $fila2['id_respuesta']; ?>">
                                                        <input type="submit" name="Guardar[<?php echo $fila2['id_secuencia']; ?>]" id="Guardar[<?php echo $fila2['id_secuencia']; ?>]" value="Guardar">
                                                    <?php
                                                    break;
                                                }
                                            }
                                        }
                                        // Mostrar el boton de guardado a los usuarios de mayor jerarquía
                                    }
                                    else{
                                        // Si encuentra alguna respuesta busca la ultima secuencia
                                        if(ExNumRows($res2)>0){
                                            // Consulto las secuencias hijas en base a la secuencia actual
                                            $consbot = "SELECT secuencia.* FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.secuencia
                                                WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                                                    AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                    AND secuencia.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                    AND secuencia.padre_secuencia=".$fila2['id_secuencia']."
                                                    AND pqrs.id_pqrs=$pqrs";
                                        }else{
                                            // Consulto las secuencias hijas en base a la secuencia con padre=0
                                            $consbot = "SELECT secuencia.* FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.secuencia
                                                WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                                                    AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                    AND secuencia.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                    AND secuencia.padre_secuencia=0 
                                                    AND pqrs.id_pqrs=$pqrs";
                                            $resbot=ExQuery($consbot);
                                            $filabot=ExFetchAssoc($resbot);
                                            //echo "<br>";

                                            $consbot = "SELECT secuencia.* FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.secuencia
                                                WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                                                    AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                    AND secuencia.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                    AND secuencia.padre_secuencia=".$filabot['id_secuencia']." 
                                                    AND pqrs.id_pqrs=$pqrs";
                                        }
                                        $resbot=ExQuery($consbot);

                                        if(ExNumRows($res2)){
                                            // Si encuentra un usuario asignado a esa secuencia solo pueden hacer cambios esas personas
                                            $cons3="SELECT * FROM pqrs.secuencia, pqrs.secuenciaxusuario 
                                                WHERE secuencia.id_secuencia=secuenciaxusuario.id_secuencia
                                                    AND secuencia.id_secuencia=".$fila2['id_secuencia']."";
                                            $res3=ExQuery($cons3);
                                            //echo "<hr>";
                                        }
                                        
                                        // si encuentra 2 o más secuencias asignadas x usuario
                                        if(ExNumRows($res3)>0){
                                            // Si el usuario está en el listado anterior permita la edición
                                            while($fila3=ExFetchAssoc($res3)){
                                                if($usuario[1]==$fila3['id_usuario']){
                                                    if(ExNumRows($resbot)>=2){
                                                        while($filabot=ExFetchAssoc($resbot)){
                                                        ?>
                                                            <input type="submit" name="Guardar[<?php echo $filabot['id_secuencia']; ?>]" id="Guardar[<?php echo $filabot['id_secuencia']; ?>]" value="<?php echo $filabot['palabraclave_secuencia']; ?>">
                                                        <?php
                                                        }
                                                    }else{
                                                        $filabot=ExFetchAssoc($resbot);
                                                        if($fila2['id_secuencia']!=0){
                                                            ?>
                                                                <input type="submit" name="Guardar[<?php echo $filabot['id_secuencia']; ?>]" id="Guardar[<?php echo $filabot['id_secuencia']; ?>]" value="Guardar">
                                                            <?php
                                                        }
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                        else{
                                            // Si encuentra 2 o más secuencias hijas crea sus botones respectivos
                                            if(ExNumRows($resbot)>=2){
                                                while($filabot=ExFetchAssoc($resbot)){
                                                ?>
                                                    <input type="submit" name="Guardar[<?php echo $filabot['id_secuencia']; ?>]" id="Guardar[<?php echo $filabot['id_secuencia']; ?>]" value="<?php echo $filabot['palabraclave_secuencia']; ?>">
                                                <?php
                                                }
                                            }else{
                                                $filabot=ExFetchAssoc($resbot);
                                                if($fila2['id_secuencia']!=0){
                                                    ?>
                                                        <input type="submit" name="Guardar[<?php echo $filabot['id_secuencia']; ?>]" id="Guardar[<?php echo $filabot['id_secuencia']; ?>]" value="Guardar">
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                                <input type="hidden" name="pqrs" id="pqrs" value="<?php echo $pqrs; ?>">
                            </td>
                        </tr>
                </table>
        </form>
        
        <table class="imagetable">
            <tr>
                    <th>PASO</th><th>FECHA</th><th>COMENTARIO</th>
            </tr>
            <?php
                $cons="select * from pqrs.respuesta, pqrs.secuencia where respuesta.id_secuencia=secuencia.id_secuencia and respuesta.id_pqrs=".$pqrs." order by id_respuesta asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_secuencia']; ?></td>
                        <td><?php echo $fila['fecha_respuesta']; ?></td>
                        <td><?php echo $fila['comentario_respuesta']; ?></td>
                        <!--<td style="text-align: center;">
                            <a href="/PQRS/Respuesta.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_respuesta']; ?>&pqrs=<?php echo $pqrs; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                        </td>-->
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>
