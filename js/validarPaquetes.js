/**
 * Autor: Jose Manuel Nieto G�mez
 * Fecha de Creaci�n: 18 de Junio de 2014
 * Objetivo: Validaci�n de paquetes de contenidos HTML5 bajo est�ndares y paquetes de iconograf�a.
 **/

/**
 * Lista donde se definen los archivos/carpetas que debe contener el empaquetado
 * La lista es llenada por el metodo inicializaEstandaresIconografia() o inicializaEstandaresContenidos() dependiendo el caso
 * @type array (Arreglo de objetos de la funcion itemLista)
 */
var listaRequerida = new Array();

/**
 * Funcion que recibe solicitud para validar empaquetamiento
 * @param {type} tipoEmpaquetamiento
 * @param {type} listaArchivos
 * @returns {Array} 0:cumpleEstandares, 1:errores(array), 2:warnings(array)
 */
function validarEmpaquetamiento(tipoEmpaquetamiento, listaArchivos) {
    console.log("Validar empaquetamiento");
    console.log("listaArchivos:" + listaArchivos);

    //Variable que dictamina si el empaquetamiento cumple los estandares
    var cumpleEstandares = true;
    //Errores
    var errores = new Array();
    //Warnings: Archivos no obligatorios que no estan
    var warnings = new Array();

    //Verifica el tipo de empaquetamiento para llenar lista requerida
    if (tipoEmpaquetamiento == "iconografia") {
        listaRequerida = inicializaEstandaresIconografia();
    } else if (tipoEmpaquetamiento == "contenidos") {
        listaRequerida = inicializaEstandaresContenidos();
    }

    //Llenar lista de archivos recibida en arreglo de objetos
    console.log("---Recorriendo lista de archivos...");
    var itemsEncontrados = 0;
    var itemsNoRequeridos = 0;
    var totalArchivos = listaArchivos.length + listaRequerida.length;
    console.log("Total de ciclos: " + totalArchivos);
    var j = 1;


    listaArchivos.forEach(function(entry) {
        console.log("--Guardando archivo.")
        console.log("-Nombre:" + entry.filename);
        console.log("-Directoryio:" + entry.directory);

        var itemLista = buscarItem(entry.filename, entry.directory);

        if (itemLista) {
            console.log("*Item de la lista requerida encontrado");
            itemsEncontrados++;
            itemLista.existe = true;
        } else {
            itemsNoRequeridos++;
            console.log("*Item no requerido");
        }

        var progreso = Math.floor(j * 100 / totalArchivos);
        console.log("PROGRESO:" + progreso + "%");
//        document.getElementById("file-list").innerHTML = progreso + "%";
        document.getElementById("validandoZip").style.width = progreso + "%";
        $("#validandoZip").css("width", progreso + "%");
        $("#mensaje_progreso_validacion").html("Validando empaquetado... (" + progreso + "%)");
        j++;
    });

    //Verificar que est�n todos los archivos requeridos marcados como existentes
    for (i = 0; i < listaRequerida.length; i++) {
        if (!listaRequerida[i].existe) {
            if (listaRequerida[i].obligatorio) {
                //Error
                if (listaRequerida[i].carpeta) {
                    errores.push("No se encontró la carpeta: " + listaRequerida[i].nombre);
                } else {
                    errores.push("No se encontró la el archivo: " + listaRequerida[i].nombre);
                }
                console.log("Error: No se encontro la carpeta/archivo: " + listaRequerida[i].nombre);
            } else {
                //Warning
                if (listaRequerida[i].carpeta) {
                    warnings.push("No se encontró la carpeta: " + listaRequerida[i].nombre);
                } else {
                    warnings.push("No se encontró la el archivo: " + listaRequerida[i].nombre);
                }
                console.log("Warning: No se encontro la carpeta/archivo: " + listaRequerida[i].nombre);
            }
        }

        var progreso = Math.floor(j * 100 / totalArchivos);
        console.log("PROGRESO:" + progreso + "%");
//        document.getElementById("file-list").innerHTML = progreso + "%";
        document.getElementById("validandoZip").style.width = progreso + "%";
        $("#validandoZip").css("width", progreso + "%");
        $("#mensaje_progreso_validacion").html("Validando empaquetado... (" + progreso + "%)");
        j++;
    }

    //Hubo errores
    if (errores.length > 0) {
        cumpleEstandares = false;
        console.log("El contenido no cumple con los estandares")
    } else {
        console.log("El contenido cumple con los estandares")
    }

    return new Array(cumpleEstandares, errores, warnings);
}

/**
 * Metodo que llena la lista de items requeridos para empaquetado de iconografia
 * @returns {Array|inicializaEstandaresIconografia.lista}
 */
function inicializaEstandaresIconografia() {
    var lista = new Array();

    //Carpeta frontweb
    lista.push(new ItemLista("frontweb/", true, false, true));

    //Archivos frontweb
    lista.push(new ItemLista("frontweb/bloque.gif", false, false, true));
    lista.push(new ItemLista("frontweb/btn_empezar.png", false, false, true));
    lista.push(new ItemLista("frontweb/colores.ini", false, false, true));
    lista.push(new ItemLista("frontweb/cuadro-a.gif", false, false, true));
    lista.push(new ItemLista("frontweb/cuadro-b.gif", false, false, true));
    lista.push(new ItemLista("frontweb/reintentar.jpg", false, false, true));
    lista.push(new ItemLista("frontweb/salir.jpg", false, false, true));
    lista.push(new ItemLista("frontweb/slide.gif", false, false, true));
    lista.push(new ItemLista("frontweb/baul.gif", false, false, true));

    //Carpeta mapa
    lista.push(new ItemLista("mapa/", true, false, true));

    //Archivos mapa
    lista.push(new ItemLista("mapa/1-a.png", false, false, true));
    lista.push(new ItemLista("mapa/1-b.png", false, false, true));
    lista.push(new ItemLista("mapa/2-a.png", false, false, false));
    lista.push(new ItemLista("mapa/2-b.png", false, false, false));
    lista.push(new ItemLista("mapa/3-a.png", false, false, false));
    lista.push(new ItemLista("mapa/3-b.png", false, false, false));
    lista.push(new ItemLista("mapa/4-a.png", false, false, false));
    lista.push(new ItemLista("mapa/4-b.png", false, false, false));
    lista.push(new ItemLista("mapa/5-a.png", false, false, false));
    lista.push(new ItemLista("mapa/5-b.png", false, false, false));
    lista.push(new ItemLista("mapa/6-a.png", false, false, false));
    lista.push(new ItemLista("mapa/6-b.png", false, false, false));
    lista.push(new ItemLista("mapa/fondoMC.png", false, false, true));

    //Carpeta premios
    lista.push(new ItemLista("premios/", true, false, true));

    //Archivos premios
    lista.push(new ItemLista("premios/0.png", false, false, true));
    lista.push(new ItemLista("premios/1.png", false, false, true));
    lista.push(new ItemLista("premios/2.png", false, false, true));
    lista.push(new ItemLista("premios/3.png", false, false, true));
    lista.push(new ItemLista("premios/4.png", false, false, true));

    //Carpeta templates
    lista.push(new ItemLista("templates/", true, false, true));

    //Carpeta templates/a (aplico)
    lista.push(new ItemLista("templates/a/", true, false, true));

    //Archivos templates/a (aplico)
    lista.push(new ItemLista("templates/a/ayuda-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/ayuda-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/ayuda-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/bibliografia-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/bibliografia-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/bibliografia-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonAnterior-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonAnterior-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonAnterior-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonPausa-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonPausa-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonPausa-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonPlay-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonPlay-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonPlay-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonReiniciar-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonReiniciar-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonReiniciar-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonSalir-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonSalir-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonSalir-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonSiguiente-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonSiguiente-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonSiguiente-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonVolumen-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonVolumen-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/botonVolumen-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/glosario-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/glosario-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/glosario-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/instrucciones-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/instrucciones-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/instrucciones-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/mapa-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/mapa-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/mapa-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/temario-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/temario-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/temario-c.png", false, false, true));
    lista.push(new ItemLista("templates/a/marco.png", false, false, true));
    lista.push(new ItemLista("templates/a/titulo.png", false, false, true));
    lista.push(new ItemLista("templates/a/tutor-a.png", false, false, true));
    lista.push(new ItemLista("templates/a/tutor-b.png", false, false, true));
    lista.push(new ItemLista("templates/a/tutor-c.png", false, false, true));

    //Carpeta templates/p (practico)
    lista.push(new ItemLista("templates/p/", true, false, true));

    //Archivos templates/p (practico)
    lista.push(new ItemLista("templates/p/ayuda-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/ayuda-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/ayuda-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/bibliografia-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/bibliografia-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/bibliografia-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonAnterior-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonAnterior-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonAnterior-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonPausa-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonPausa-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonPausa-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonPlay-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonPlay-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonPlay-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonReiniciar-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonReiniciar-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonReiniciar-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonSalir-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonSalir-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonSalir-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonSiguiente-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonSiguiente-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonSiguiente-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonVolumen-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonVolumen-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/botonVolumen-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/glosario-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/glosario-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/glosario-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/instrucciones-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/instrucciones-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/instrucciones-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/mapa-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/mapa-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/mapa-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/temario-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/temario-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/temario-c.png", false, false, true));
    lista.push(new ItemLista("templates/p/marco.png", false, false, true));
    lista.push(new ItemLista("templates/p/titulo.png", false, false, true));
    lista.push(new ItemLista("templates/p/tutor-a.png", false, false, true));
    lista.push(new ItemLista("templates/p/tutor-b.png", false, false, true));
    lista.push(new ItemLista("templates/p/tutor-c.png", false, false, true));

    //Carpeta templates/i (integro)
    lista.push(new ItemLista("templates/i/", true, false, true));

    //Archivos templates/i (integro)
    lista.push(new ItemLista("templates/i/ayuda-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/ayuda-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/ayuda-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/bibliografia-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/bibliografia-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/bibliografia-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonAnterior-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonAnterior-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonAnterior-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonPausa-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonPausa-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonPausa-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonPlay-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonPlay-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonPlay-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonReiniciar-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonReiniciar-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonReiniciar-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonSalir-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonSalir-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonSalir-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonSiguiente-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonSiguiente-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonSiguiente-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonVolumen-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonVolumen-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/botonVolumen-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/glosario-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/glosario-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/glosario-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/instrucciones-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/instrucciones-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/instrucciones-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/mapa-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/mapa-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/mapa-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/temario-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/temario-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/temario-c.png", false, false, true));
    lista.push(new ItemLista("templates/i/marco.png", false, false, true));
    lista.push(new ItemLista("templates/i/titulo.png", false, false, true));
    lista.push(new ItemLista("templates/i/tutor-a.png", false, false, true));
    lista.push(new ItemLista("templates/i/tutor-b.png", false, false, true));
    lista.push(new ItemLista("templates/i/tutor-c.png", false, false, true));

    //Carpeta archivos
    lista.push(new ItemLista("archivos/", true, false, false));

    //Archivos de la carpeta archivos
    lista.push(new ItemLista("archivos/programa.pdf", false, false, false));

    //Carpeta estimulos
    lista.push(new ItemLista("estimulos/", true, false, false));

    //Archivos estimulos
    lista.push(new ItemLista("estimulos/estimulo-1-1-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-1-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-2-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-2-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-3-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-3-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-4-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-1-4-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-1-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-1-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-2-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-2-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-3-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-3-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-4-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-2-4-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-1-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-1-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-2-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-2-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-3-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-3-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-4-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-3-4-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-1-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-1-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-2-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-2-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-3-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-3-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-4-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-4-4-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-1-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-1-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-2-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-2-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-3-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-3-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-4-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-5-4-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-1-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-1-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-2-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-2-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-3-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-3-b.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-4-a.png", false, false, false));
    lista.push(new ItemLista("estimulos/estimulo-6-4-b.png", false, false, false));

    return lista;
}

/**
 * Funcion que inicializar la matriz a validar para contenidos HTML5
 * @returns {Array|inicializaEstandaresContenidos.lista}
 */
function inicializaEstandaresContenidos() {

    var lista = new Array();

    //Carpeta recursos
    lista.push(new ItemLista("recursos/", true, false, false));

    //Archivos frontweb
    lista.push(new ItemLista("recursos/ayuda.html", false, false, false));
    lista.push(new ItemLista("recursos/bibliografia.html", false, false, false));
    lista.push(new ItemLista("recursos/glosario.html", false, false, false));
    lista.push(new ItemLista("recursos/temario.html", false, false, false));

    //Carpeta Serie 01
    lista.push(new ItemLista("s01/", true, false, true));

    //Archivos del contenidos serie 01
    for (i = 1; i <= 3; i++) {
        //Carpetas contenidos de la serie 01
        lista.push(new ItemLista("s01/c0" + i + "/", true, false, true));
        lista.push(new ItemLista("s01/c0" + i + "/instrucciones/", true, false, false));
        lista.push(new ItemLista("s01/c0" + i + "/tutor/", true, false, false));

        //Archivo
        lista.push(new ItemLista("s01/c0" + i + "/index.html", false, false, true));
        lista.push(new ItemLista("s01/c0" + i + "/instrucciones/index.html", false, false, false));
        lista.push(new ItemLista("s01/c0" + i + "/instrucciones/audio_ins.mp3", false, false, false));
        lista.push(new ItemLista("s01/c0" + i + "/instrucciones/audio_ins.ogg", false, false, false));
        lista.push(new ItemLista("s01/c0" + i + "/instrucciones/audio_ins.wav", false, false, false));
        lista.push(new ItemLista("s01/c0" + i + "/tutor/index.html", false, false, false));

    }

    //El resto de las series y contenidos es opcional
    for (i = 2; i <= 6; i++) {
        //Carpeta Serie xx
        lista.push(new ItemLista("s0" + i + "/", true, false, false));

        //Archivos del contenido 01 serie xx
        for (j = 1; j <= 3; j++) {
            //Carpetas contenidos de la serie 01
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/", true, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/instrucciones/", true, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/tutor/", true, false, false));

            //Archivo
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/index.html", false, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/instrucciones/index.html", false, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/instrucciones/audio_ins.mp3", false, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/instrucciones/audio_ins.ogg", false, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/instrucciones/audio_ins.wav", false, false, false));
            lista.push(new ItemLista("s0" + i + "/c0" + j + "/tutor/index.html", false, false, false));
        }
    }

    return lista;


}

/**
 * Funcion que recibe nombre del archivo/carpeta, si es carpeta y lo busca en la lista requerida
 * Si lo encuentra retorna el objeto.
 * Si no lo encuentra retorna un valor nulo
 * @param {type} nombre
 * @param {type} carpeta
 * @returns {undefined|listaRequerida}
 */
function buscarItem(nombre, carpeta) {
    for (i = 0; i < listaRequerida.length; i++) {
        if (listaRequerida[i].nombre === nombre && listaRequerida[i].carpeta === carpeta) {
            return listaRequerida[i];
        }
    }
    return null;
}

/**
 * Objeto item base gen�rico
 * @param {type} nombre del archivo o carpeta
 * @param {type} carpeta - dicta si es carpeta = true, si es archivo = false
 * @param {type} existe - dicta si el archivo requerido se encuentra en el paquete
 * @param {type} obligatorio - dicta si el archivo es obligatorio = true, si no es obligatorio = false
 * @returns {itemLista}
 */
function ItemLista(nombre, carpeta, existe, obligatorio) {
    this.nombre = nombre;
    this.carpeta = carpeta;
    this.existe = existe;
    this.obligatorio = obligatorio;
}