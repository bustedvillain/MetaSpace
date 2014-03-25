
<!-- Ver Modal -->
<div id="verEditarPerfilModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Mi perfil  </h3> 
        
        <?php if(!esAdministrador() && !esGestorContenido()
                )
                echo '<p class="text-center"><button  type="button" id="btnActivarEdicion" class="btn">Activar Edici&oacute;n</button></p>';
            ?>
        
    </div>
    <div class="modal-body">



        <form class="form-horizontal" id="frmPerfil">

            <div id="avisoCambios1" class="alert alert-block" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>&iexcl;Advertencia!</h4>
                Para que la informaci&oacute;n sea consistente, no olvides replicar los cambios que realizas en &eacute;ste sistema, en Moodle.
            </div>
            <div class="control-group">
                <input type="hidden" name ="idDatosPersonales" id="vpIdDatosPersonales"/>
                <label class="control-label" for="nombre">Nombre:</label>
                <div class="controls">
                    <input type="text" name="nombre" id="vpNombre" disabled="true">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="apellidoPaterno">Apellido Paterno:</label>
                <div class="controls">
                    <input type="text" name="apellidoPaterno" id="vpApellidoPaterno" disabled="true">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="apellidoMaterno">Apellido Materno:</label>
                <div class="controls">
                    <input type="text" name="apellidoMaterno" id="vpApellidoMaterno" disabled="true">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="fechaNacimiento">Fecha Nacimiento:</label>
                <div class="controls">
            
                     <input type="text" name="fechaNacimiento" class="span2" id="vpFechaNacimiento" disabled="true">
   
                    
                </div>
            </div>
<!--            <div class="control-group">
                <label class="control-label" for="curp">CURP:</label>
                <div class="controls">
                    <input type="text" name="curp" id="vpCurp" disabled="true">
                </div>
            </div>-->
            <div class="control-group">
                <label class="control-label" for="codigoPostal">C&oacute;digo Postal:</label>
                <div class="controls">
                    <input type="number" maxlength="5" name="codigoPostal" id="vpCodigoPostal" disabled="true">
                </div>
            </div>
<!--            <div class="control-group">
                <label class="control-label" for="calle">Calle:</label>
                <div class="controls">
                    <input type="text" name="calle" id="vpCalle" disabled="true">
                </div>
            </div>-->
<!--            <div class="control-group">
                <label class="control-label" for="noExterior">N&uacute;mero exterior:</label>
                <div class="controls">
                    <input type="number" name="noExterior" id="vpNoCasaExt" disabled="true">
                </div>
            </div>-->
<!--            <div class="control-group">
                <label class="control-label" for="noInterior">N&uacute;mero interior:</label>
                <div class="controls">
                    <input type="number" name="noInterior" id="vpNoCasaInt" disabled="true">
                </div>
            </div>-->
<!--            <div class="control-group">
                <label class="control-label" for="coloniaLocalidad">Colonia Localidad:</label>
                <div class="controls">
                    <input type="text" name="coloniaLocalidad" id="vpColoniaLocalidad" disabled="true">
                </div>
            </div>-->
<!--            <div class="control-group">
                <label class="control-label" for="delegacionMunicipio">Delegaci&oacute;n/Municipio:</label>
                <div class="controls">
                    <input type="text" name="delegacionMunicipio" id="vpDelegacionMunicipio" disabled="true">
                </div>
            </div>-->
            <div class="control-group">
                <label class="control-label" for="entidadFederativa">Entidad Federativa:</label>
                <div class="controls">
                    <select name="entidadFederativa" id="vpEntidadFederativa" disabled="true">
                        <?php comboEntidades(); ?>
                    </select>
                </div>
            </div>
<!--            <div class="control-group">
                <label class="control-label" for="nacionalidad">Nacionalidad:</label>
                <div class="controls">
                    <select name="nacionalidad" id="vpNacionalidad" disabled="true">
                        <?php comboNacionalidades(); ?>
                    </select>
                </div>
            </div>-->
<!--            <div class="control-group">
                <label class="control-label" for="zonaHoraria">Zona Horaria:</label>
                <div class="controls">
                    <select name="zonaHoraria" id="vpZonaHoraria" disabled="true">
                        <option value="1">GMT -6</option>
                        <option value="2">GMT -7</option>
                        <option value="3">GMT -8</option> 
                    </select>
                </div>
            </div>-->
            <div class="control-group">
                <label class="control-label" for="telefonoFijo">Tel&eacute;fono Fijo:</label>
                <div class="controls">
                    <input type="text" name="telefonoFijo" id="vpTelefonoFijo" disabled="true">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="telefonoMovil">Tel&eacute;fono M&oacute;vil:</label>
                <div class="controls">
                    <input type="text" name="telefonoMovil" id="vpTelefonoMovil" disabled="true">
                </div>
            </div>

            <div id="avisoCambios2" class="alert alert-error" style="display:none;">
                Si modificas la informaci&oacute;n siguiente, debes hacer los mismos cambios en Moodle o de lo contrario se tendrá incongruencias. Si no colocas una contrase&ntilde;a, seguir&aacute; vigente tu contrase&ntilde;a anterior.
            </div>

            <div class="control-group">
                <label class="control-label" for="correo">Email:</label>
                <div class="controls">
                    <input type="text" name="correo"  id="vpCorreo" disabled="true">
                </div>
            </div>
            
            <?php if(!esAdministrador())
                {?>
                        <div class="control-group">
                        <label class="control-label" for="contrasena">Contrase&ntilde;a</label>
                        <div class="controls">
                            <input type="password" name="contrasena" id="vpContrasena" disabled="true">
                        </div>
                    </div>
            
            <div class="control-group">
                <label class="control-label" for="contrasena2" id="labelContrasena2" style="display:none;">Repetir Contrase&ntilde;a</label>
                <div class="controls">
                    <input type="password" name="contrasena2" id="contrasena2" disabled="true" style="display:none;">
                </div>
            </div>
               <?php 
                }
                
                ?>
            

            <div class="modal-footer">
                <!--data-dismiss="modal" aria-hidden="true"--> 
                <button id="cmdGuardarPerfil" class="btn btn-danger" type="button" onclick="enviarDatosPerfilPropio();" style="display:none;">Guardar</button>
                <button id="cmdCerrarPerfil" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            </div>
        </form>
    </div>

</div>

