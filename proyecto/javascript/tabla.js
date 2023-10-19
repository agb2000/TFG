// Definimos nuestras variables locales
var selector_tabla = document.querySelectorAll(".table");
var selector_ubi = document.querySelector("#barraubi a");

// Comprobamos de que haya una tabla en el apartado de administrador para poder añadirle al crud un THEAD
if (selector_tabla.length != 0) {
    // Insertamos antes del TBODY
    selector_tabla[1].appendChild(cabeceraTabla());
} // End if de la comprobacion

/**
 * Esta definiendo la funcion cabeceraTabla la cual se encarga de crear a través de 
 * nodo un thead e insertarlos en la tabla de los crud
 * @returns ---> THEAD con una frase de SISTEMA DE GESTION DE y un boton para AÑADIR
 */
function cabeceraTabla() {
    // Creamos los nodos correspondientes
    let thead = crearNodo("thead");
    let tr1 = crearNodo("tr");
    let th1 = crearNodo("th", "Sistema de Gestion de " + (selector_ubi.innerHTML));
    let th2 = crearNodo("th");

    // Seleccionamos las columnas de la tablas en la que nos encontramos
    let columnas = document.querySelectorAll(".table tbody tr th");

    // Añadimos algunas caracteristicas al nodo TH para hacer un colSpan
    th1.colSpan = columnas.length - 1;

    // Añadimos css al nodo thead
    tr1.appendChild(th1);
    tr1.appendChild(th2);

    // Añadimos el tr al thead
    thead.appendChild(tr1);

    // Devolvemos en el THEAD
    return thead;
} // End de la funcion cabeceraTabla

/**
 * Estamos definiendo la funcion Crear Nodo la cual se encarga de crear nodos a través del arbol DOM
 * @param {*} tipo ---> Elemento a crear
 * @param {*} texto ---> Texto que va contener ese elemento
 * @param {*} id ---> Podemos asignarle un id al elemento
 * @param {*} valor ---> Podemos asignarle un valor al elemento
 * @returns ---> Devolveremos el elemento creado
 */
function crearNodo(tipo, texto=null, id=null, valor=null) {
    // Definimos el tipo de elemento que vamos a crear 
    let type = document.createElement(tipo);

    // Comprobamos de que texto no sea nulo
    if (texto != null) {
        // Definimos el texto que va a contener
        let text = document.createTextNode(texto);

        // Le añadimos las caracteristicas
        type.appendChild(text);
    } // End de la validacion de texto

    // Comprobamos de que id no sea nulo
    if (id != null) {
        // Ahora definimos que el id que va a contener
        type.setAttribute("id", id);
    } // End de la validacion de id

    // Comprobamos de que valor no sea nulo
    if (valor != null) {
        // Ahora definimos el value que va a contener
        type.setAttribute("value", valor);
    } // End de la validacion de valor

    // Nos lo devuelve
    return type;
} // End de la función de crear un nodo
