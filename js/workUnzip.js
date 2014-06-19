/**
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 18/06/2014
 * Objetivo: Leer contenidos de un paquete ZIP * 
 */


/**
 * Funcion que obtiene la lista de archivos de un zip
 * @param {type} fileInput
 * @returns {undefined}
 */
function getListFiles(fileInput) {
    // use a BlobReader to read the zip from a Blob object
    zip.createReader(new zip.BlobReader(fileInput.files[0]), function(reader) {

        // get all entries from the zip
        reader.getEntries(function(entries) {
            console.log("NEW GET ENTRIES");
            console.log("ENTRIES LEGTH:"+entries.length);
            if (entries.length) {
                listaCargaZIP(entries);                
            }else{
                console.log("NO HAY ENTRADAS");
            }
        });
    }, function(error) {
        // onerror callback
        console.log("ERROR:"+error);
    });
}

