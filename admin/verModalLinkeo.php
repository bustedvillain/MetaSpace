
<!-- Ver Modal -->
<div id="verModalLinkeo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <!--//inicia control de cambios #5-->
        <h3 id="myModalLabel">Vincular series</h3>
        <!--//termina control de cambios #5-->
        <div id="divCargando">
            Cargando...
            <div class="progress progress-striped active" >
                <div class="bar" style="width: 100%;"></div>
            </div>
        </div>
        
    </div>
    <div class="modal-body">
        <form id="frmLinkeo">
 
        </form>
    </div>

    <div class="modal-footer">
        
        <div id="divCargandoo2" style="display:none">
            Cargando...
            <div class="progress progress-striped active" >
                <div class="bar" style="width: 100%;"></div>
            </div>
        </div>
        <button class="btn btn-danger"  aria-hidden="true" onclick="guardaLinkeo();">Guardar</button>
        <button class="btn btn-primary" id="btnGuardarLinkeo" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        
    </div>
</div>
