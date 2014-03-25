                <form id="reportesFORM"action="guardaReporte.php" id="" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                    <input type="hidden" name="idCursoAbierto" value="<?php echo $idCursoAbierto; ?>">
                    <input type="hidden" name="idGrupo" value="<?php echo $idGrupo; ?>">
                    <input type="hidden" name="tipo_reporte" value="<?php echo $_GET['tipoReporte']; ?>">
                    
                    <?php if ($nombreRep != "seguimiento" && $nombreRep != 'gestion' && $nombreRep != "Evaluaci&oacute;n final" ) 
                        {
                        ?>
                        <div id="dExcel1">
                            <h4>Sube aqu&iacute; tu reporte de:  <?php echo determinaNombreReporte($_GET['tipoReporte']) ?></h4>
                            <!--<input type="file" id="excel" name="excel" required>-->
                            <div class="custom-input-file">
                                <input id="excel" class="input-file" type="file" name="excel" size="1"  required></input>
                                <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                                <input id="url-archivo" type="text"></input>
                            </div>
                        </div>
                        <?php
                        }
                        else if($nombreRep == 'gestion' || $nombreRep == 'seguimiento' )
                        {?>
                             <div class='input-append'>
                            <label>Fecha Inicio (dd/mm/yyyy):</label>
                            <input type="text" name="fechaInicio" id="dpd1" required pattern="\d{1,2}/\d{1,2}/\d{4}"> 
                             </div>
                             <div class='input-append'>
                            <label>Fecha Fin (dd/mm/yyyy):</label>
                            <input type="text" name="fechaFin" id="dpd2" required pattern="\d{1,2}/\d{1,2}/\d{4}">
                            <input type="hidden" name="ignorarFILE"  value="si">
                            </div>
                    <?php
                        }                        
                    ?>
                    
                    
                    <?php if ($nombreRep == "Evaluaci&oacute;n final") {
                        ?>
                         <div id="dExcel1">
                            <h4>Sube aqu&iacute; tu reporte de: Evaluaci&oacute;n  Final </h4>
                            <!--<input type="file" id="excel" name="excel" required>-->
                            <div class="custom-input-file">
                                <input id="excel" class="input-file" type="file" name="excel" size="1"  required></input>
                                <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                                <input id="url-archivo" type="text"></input>
                            </div>
                        </div>
                        <div id="dExcel2">
                            <h4>Sube aqu&iacute; tu reporte de: Evaluaci&oacute;n Diagnostica</h4>
                            <!--<input type="file" id="excel2" name="excel2" required>-->
                            <div class="custom-input-file">
                                <input id="excel2" class="input-file" type="file" name="excel2" size="1" required></input>
                                <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                                <input id="url-archivo2" type="text"></input>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($nombreRep == "desempeño") {
                        ?>
                        <div id="dUnidades">
                            <h4>Elige la unidad a la que corresponde el reporte</h4>
                            <?php selectUnidadesDeCurso($idCurso); ?>
                        </div>
                    <?php } ?>
                    <h4>Presiona subir</h4>
                    <button id="formReportes"class="btn btn-primary" type="submit">Generar</button>
                </form>