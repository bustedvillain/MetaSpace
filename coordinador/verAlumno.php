   <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Detalle del Alumno</h3>
                </div>
                <div class="modal-body">                    
                    <table class="table table-hover table-bordered">
                        <?php imprimirVerDatosPersonales(); ?>
                        <tr>
                            <td colspan="2"><b>DATOS ASOCIADOS</b></td>
                        </tr>
                        <tr>
                            <td>Matricula</td>
                            <td ><div id="ver_matricula"></div></td>
                        </tr>
                        <tr>
                            <td>Padre</td>
                            <td ><div id="ver_padre"></div></td>
                        </tr>
                        <tr>
                            <td>Profesor de Aula</td>
                            <td ><div id="ver_profesor_aula"></div></td>
                        </tr>
                        <tr>
                            <td>Nivel Educativo</td>
                            <td ><div id="ver_nivel_educativo"></div></td>
                        </tr>
                        <tr>
                            <td>Grado Escolar</td>
                            <td ><div id="ver_grado_escolar"></div></td>
                        </tr>
                        <tr>
                            <td>Instituci&oacute;n</td>
                            <td ><div id="ver_institucion"></div></td>
                        </tr>
                        <tr>
                            <td>Escuela</td>
                            <td ><div id="ver_escuela"></div></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>IMAGEN DE PERFIL</b></td>
                        </tr>
                        <tr>
                            <td>Imagen</td>
                            <td><div id="ver_imagen"></div></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>