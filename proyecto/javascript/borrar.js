// Definimos las variables locales 
var url = location.href;

// Dividimos la url en un array a traves del seperador /
var contenido_url = url.split("/");

// Comprobamos de que existe el id borrar
if (document.getElementById("borrar")){
    // En caso de que haya le asignamos un onclick para que pueda borrar en las paginas de ver/modificar de los elementos del crud
    document.getElementById("borrar").onclick = function () {
        let id = contenido_url[5].split("=");
        id[1] = parseInt(id[1]);
        borrar(id[1], contenido_url[3], "Borrar");
    } // End del onclick
} // End if de la validacion

/**
 * Definimos la funcion borrar la cual se encarga de preguntarnos en un modal si queremos borrar o no el elemento 
 * @param {*} Id ---> Le pasamos el id del elemento a borrar
 * @param {*} controlador ---> Le pasamos el controlador de donde venimos
 * @param {*} accion ---> Le pasamos el selector del boton de borrar para asigarle la url para borrar
 * @returns 
 */
function borrar(Id, controlador, accion) {
    // Preguntamos si queremos borrar el elemento o no
    if (confirm("¿Esta seguro de borrar el elemento?")) {
        location = "/"+controlador+"/"+accion+"/id="+Id;
    } // End if
    else {
        location = "/"+controlador;
        return false;
    } // End else
} // End de la funcion de borrar