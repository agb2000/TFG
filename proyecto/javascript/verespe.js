// Estamos realizando que cuando gane el focus el buscadpr
document.getElementById("buscador").addEventListener("focus", function () {

    // Sacamos la cadena del buscador
    let nombre_buscar = document.getElementById("buscador").value;

    // Nos aparecera los conciertos que coincidan con la cadena a partir de tercer caracter
    if (nombre_buscar.length >= 3) {
        document.getElementById("listados").style.display = "inline-block"; // Mostraremos la caja
    } // End if
    else{
        document.getElementById("listados").style.display = "none";
    } // End else

    // Estamos realziando el autocompletado en el buscador para que nos salgan todos los espectaculos disponibles
    document.getElementById("buscador").oninput = function () {

        // Sacamos la cadena del buscador
        let nombre_buscar = document.getElementById("buscador").value;

        // Nos aparecera los conciertos que coincidan con la cadena a partir de tercer caracter
        if (nombre_buscar.length >= 3) {
            document.getElementById("listados").style.display = "inline-block"; // Mostraremos la caja

            // Realizamos la conexión ajax
            $.ajax({
                type: "POST", // Indicaremos el tipo de metodo
                url: "/espectaculos/AjaxEspectaculos", // Hacemos la conexion a la accion de Productos
                data: { nombre: nombre_buscar }, // Enviamos datos
                dataType: "json", // Como enviamos los datos

                // En caso de que se hecho la conexion correcta mostraremos los espectaculos en un parrafo
                success: function (response) {

                    // Obtenemos el parrafo donde insertaremos los datos
                    var selector_p = document.querySelectorAll("#listados p");

                    // Comprobamos de que correcto sea verdadero en caso contrario no mostraremos nada
                    if (response.correcto === true) {

                        // Comprobamos de que si el div listados tiene hijos
                        if (document.getElementById("listados").children.length > 0) {

                            // Recorremos los selectores p
                            for (let key of selector_p) {
                                document.getElementById("listados").removeChild(key);
                            } // End for

                        } // End if

                        // Recorremos los datos que recibimos del ajax
                        for (const key in response.datos) {
                            // Creamos el nodo
                            let parrafo = crearNodo("p", response.datos[key].titulo, null, null, null);

                            // Añadimos el evento hoover para añadir al input text
                            parrafo.onmouseover = function () {
                                document.getElementById("buscador").value = response.datos[key].titulo;
                            } // End del evento onmouseover

                            // Lo añadimos los parrafos de los titulos 
                            document.getElementById("listados").appendChild(parrafo);

                        } // End for
                    } // End if
                    else {
                        // Comprobamos 
                        if (document.getElementById("listados").children.length > 0) {

                            // Recorremos los selectores
                            for (let key of selector_p) {
                                document.getElementById("listados").removeChild(key);
                            } // End for

                        } // End if

                        // Creamos los nodos parrafos
                        let parrafo = crearNodo("p", response.datos, null, null, null);

                        // Añadimos el parrafo al div
                        document.getElementById("listados").appendChild(parrafo);
                    } // End else
                },
                // En caso de que no se haya hecho bien la conexion AJAX mostraremos los errores por consola
                error: function (error) {
                    console.error("Se ha producido un error");
                    console.error(error);
                } // End del error
            });
        } // End if
    } // End if del autocompletar
})

// En caso de que perdimos el foco deshabilitamos el nodo
document.getElementById("buscador").addEventListener("blur", function () {
    document.getElementById("listados").style.display = "none";
});

// Definimos el selector para obtener los asientos de la sala
var butacas = document.querySelectorAll(".asientos");
var array_butacas = [];

// Recorremos los selectores de los asientos de la sala
if (butacas.length >= 1) {
    // Definimos las variables de la url 
    var url = window.location.href;

    // Obtenemos la url
    url = parseInt(url.split("=")[1]);

    // Recorremos las filas y comprobamos de que la fila este comprada o no
    for (let index = 0; index < butacas.length; index++) {
        comprobacion_butacas(url, (index + 1));
    } // End for 1

} // End de la longuitud

// En caso de que exista el comprar_entradas
if (document.getElementById("comprar_entradas")) {

    document.getElementById("error").style.display = "none";

    // Obtenemos la url del navegador y dividimos la url en partes
    var url = window.location.href;
    url = parseInt(url.split("=")[1]);

    // Asignamos al boton de comprar entradas el evento onclick para comprar entradas
    document.getElementById("comprar_entradas").onclick = function () {

        // Realizamos la conexion ajax
        $.ajax({
            type: "POST", // Indicaremos el tipo de metodo
            url: "/entradas/Comprar_Entradas", // Hacemos la conexion a la accion de Comprar_Entradas
            data: { datos: array_butacas, id: url }, // Enviamos datos
            dataType: "JSON", // Como enviamos los datos

            // En caso de que se hecho la conexion correcta nos redireccionamos a la pagina resumen a traves del enlace que recibimos
            success: function (response) {
                if (response.correcto === true) {

                    // Guardamos los id en una cookie
                    document.cookie = "datos="+JSON.stringify(response.datos)+"; path=/";
                    // Llamamos a la funcion de enviarResumen para que nos muestre el resumen en la otra pagina
                    enviarResumen(response.datos);
                    // Redireccionamos 
                    window.location.href = response.url;
                } // End if
                else{

                    // Habilitamos el campo de errores
                    document.getElementById("error").style.display = "inline-block";

                    // Mostraremos los errores
                    for (let valor in response.datos) {
                        document.getElementById("error").innerHTML += response.datos[valor]+"<br>";
                    } // End del for in

                } // End else
            },
            // En caso de que no se haya hecho bien la conexion AJAX mostraremos los errores por consola
            error: function (error) {
                console.error("Se ha producido un error");
                console.error(error);
            } // End del error
        });
    } // End de la funcion onclick
} // End if

// Comprobamos de que exista el id resumen donde mostraremos un resumen de las entradas compradas
if (document.getElementById("resumen")){
    // Hacemos la conexion ajax para obtener las entradas compradas hasta el momento
    $.ajax({
        type: "GET",
        url: "/entradas/ObtenerDatos",
        data: "",
        dataType: "JSON",
        // En caso de que la conexion se haya hecho
        success: function (response) {
            // Mostraremos en un card un resumen de la entrada comprada
            for (const value in response.datos) {
                document.getElementById("resumen_info").innerHTML += "Has comprado las butacas numero "+(response.datos[value][0].num_asientos)+
                    " con un precio de "+(response.datos[value][0].precio_total)+"€ <br>";
            } // End del for ind

            // Cuando nos marchamos de la pagina resumen borramos la cookie
            window.onbeforeunload  = function () {
                document.cookie = "datos=''; max-age=0; path=/";
            } // End del evento onbeforeunload

            // Le asignamos el href al pdf Entrada para poder Descargar
            document.getElementById("pdf").setAttribute("href", "/pdf/VerEntradas?datos="+JSON.stringify(response.datos));
        }, // End del succes

        // En caso de que no se haya hecho bien la conexion AJAX mostraremos los errores por consola
        error: function (error) {
            console.error("Se ha producido un error");
            console.error(error);
        } // End del error
    });
} // End if de la validacion del resumen

/**
 * Estamos definiendo la funcion enviarResumen la cual se encarga de envair los id de entradas a la accion Mostrar Entradas
 * @param {*} array_datos --->Los id de la reserva
 */
function enviarResumen(array_datos) {
    // Hacemos la conexion asincrona para enviar los datos
    $.ajax({
        type: "POST",
        url: "/entradas/MostrarEntradas",
        data: { datos: array_datos},
        dataType: "JSON",
        success: function (response) {

        },
        error: function (error) {
            console.error("Se ha producido un error");
            console.error(error);
        } // End del error
    });
} // End de la funcion enviarResumen

/**
 * Estamos definiendo la accion comprobacion_butacas la cual se encarga de comprobar de que si la butaca esta comprada por alguien o no
 * @param {*} id ---> Pasamos el id de la sesion
 * @param {*} num_butaca ---> Numero de butaca
 */
function comprobacion_butacas(id, num_butaca) {
    $.ajax({
        type: "POST",
        url: "/entradas/ObtenerButacas",
        data: { id: id, num_butaca: num_butaca },
        dataType: "json",
        success: function (response) {
            // En caso de que haya butaca le asignamos un fondo rojo
            if (response.correcto === true) {
                butacas[num_butaca - 1].style.backgroundColor = "red";
            } // End if 
            else {
                // Definimos las variables locales
                let sw = true;
                
                // A la butaca le asignamos el evento onclick para que pueda añadir la butaca
                butacas[num_butaca - 1].addEventListener("click", function () {
                    // Si pulsamos una vez lo añadimos al array y cambio el sw a false
                    if (sw === true) {
                        array_butacas.push(num_butaca);
                        sw = false;
                    } // End if 
                    // En caso contrario lo que hacemos es quitarle esa butaca del array si pulsa otra vez
                    else {
                        array_butacas.splice(array_butacas.indexOf(num_butaca), 1);
                        sw = true;
                    } // End else

                    // Eliminamos el espacio del entradas por el tema de la cookie
                    if (document.getElementById("entradas").innerHTML != "") {
                        document.getElementById("entradas").innerHTML = "";
                    } // End if

                    // Recorremos el array de butacas y mostramos la informacion la butaca seleccionada
                    for (let iterator of array_butacas) {
                        document.getElementById("entradas").innerHTML += "Has seleccionado la butaca numero : " + iterator + "<br>";
                    } // End for
                }) // End de añadir el evento onclick
            } // End else
        } // End if de lo sucedido
    });
} // End de la funcion de comprobacion

/**
 * Estamos definiendo la funcion Crear Nodo la cual se encarga de crear nodos a través del arbol DOM
 * @param {*} tipo ---> Elemento a crear
 * @param {*} texto ---> Texto que va contener ese elemento
 * @param {*} id ---> Podemos asignarle un id al elemento
 * @param {*} clase ---> Podemos asignarle una clase al elemento
 * @param {*} valor ---> Podemos asignarle un valor al elemento
 * @returns ---> Devolveremos el elemento creado
 */
function crearNodo(tipo, texto=null, id=null, clase=null, valor=null) {
    // Definimos el tipo de elemento que vamos a crear 
    let type = document.createElement(tipo);

    // Comprobamos de que texto no sea nulo
    if (texto != null) {
        // Definimos el texto que va a contener
        let text = document.createTextNode(texto);

        // Le añadimos las caracteristicas
        type.appendChild(text);
    }

    // Comprobamos de que id no sea nulo
    if (id != null) {
        // Ahora definimos que el id que va a contener
        type.setAttribute("id", id);
    }

    // Comprobamos de que clase no sea nulo
    if (clase != null) {
        // Ahora definimos el value que va a contener
        type.setAttribute("class", clase);
    }

    // Comprobamos de que valor no sea nulo
    if (valor != null) {
        // Ahora definimos el value que va a contener
        type.setAttribute("value", valor);
    }

    // Nos lo devuelve
    return type;
} // End de la función de crear un nodo