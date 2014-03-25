//var idIndiceNuevo = -1;
var idAlumno = 0;
var idUnidad = 0;
var idCurso = 0;
var idRelCursoGrupo = 0;
var idElementoAer = 0;
var arrCont;

var nom_dir_template = "5to6to";
//var template_path = "../storage/templates/"+nom_dir_template;
var template_path = "/softmeta/storage/templates/"+1;
//var template_path = "../softmeta/storage/templates/"+1;
var tipo_elemento = 'a';
var traceLevel=4;





var volumen = 0;
var maxCantArr=0;

//variables de carga
var indiceActual = 1;
//var plantilla_path = "http://localhost/eblue/softmeta/plantilla";
var plantilla_path = "http://192.168.57.158/eblue/softmeta/plantilla";
//var unidades_path="http://localhost/eblue/softmeta/storage/unidades";
//var storage_path = "http://192.168.57.158/eblue/storage";
var storage_path = "http://localhost/eblue/softmeta/storage";
var unidades_path="http://192.168.57.158/eblue/softmeta/storage/unidades";
//var serie_path = unidades_path + "/1" + "/s01";
var serie = "s01"
var serie_path = unidades_path + "/"+ idUnidad + "/" + serie;
var unidades_path2="../../storage/unidades";
//var serie_path2 = unidades_path2 + "/1" + "/s01";
var serie_path2 = unidades_path2 + "/"+ idUnidad + "/s01";

var idAlumno=0;
var alumno = "no";

var mapaCurso_path = "http://192.168.57.41/storage/cursos";
var nombreMapaCurso = "1";
var rutaCompMapaCurso = mapaCurso_path;
//var rutaCompMapaCurso = mapaCurso_path + "/" + nombreMapaCurso;

var statusReproduccion = 0;

var tiempoT = 0;
    var banderaTiempo = 1;
    
