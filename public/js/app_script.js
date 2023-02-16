
                            /* HOMEPAGE */

// init Isotope
var $grid = $('.collection-list').isotope({
    // options
});
  
// filter items on button click
$('.filter-button-group').on( 'click', 'button', function() {
    var filterValue = $(this).attr('data-filter');
    resetFilterBtns();
    $(this).addClass('active-filter-btn');
    $grid.isotope({ filter: filterValue });
});
  
var filterBtns = $('.filter-button-group').find('button');
    function resetFilterBtns(){
        filterBtns.each(function(){
        $(this).removeClass('active-filter-btn');
    });
}



                            /* SHOW (Producto) */

function change_image(image){

    var container = document.getElementById("main-image");

    container.src = image.src;
}

document.addEventListener("DOMContentLoaded", function(event) {
               
});



                            /* CARRITO */


$(document).ready(function(){
    
    if(window.location.href.indexOf("producto") != -1){
        console.log("Entra ruta producto");


                            /* Añadir a CARRITO */

        document.querySelector('#botonAñadirCarrito').addEventListener('click', () =>{

            let cantidad = document.querySelector('#quantityInput').value;

            /* LLAMADA A AÑADIR PRODUCTO A CARRITO (Como variable SESSION) */
            $.ajax({
                url:$("#path-to-controller-aniadir").data("href"),
                type: "POST",
                dataType: "json",
                data: {
                    "cantidad": cantidad
                },
                async: true,

                error: function() {
                    console.log("Error");
                },

                success: function (data)
                {        
                    console.log(data);        
                }
            });

        });
    
                            /* Añadir PREGUNTA */

    document.querySelector("#publicarPregunta").addEventListener('click', () => {
        console.log("PULSADO P");
        console.log($("#path-to-controller-publicarPregunta").data("href"));

        let textoPregunta = $('#textAreaPregunta').val();

        /* LLAMADA AJAX A PUBLICAR PREGUNTA (Introduce pregunta en BBDD) */ 
        $.ajax({
            url:$("#path-to-controller-publicarPregunta").data("href"),
            type: "POST",
            dataType: "json",
            data: {
                "textoPregunta": textoPregunta
            },
            async: true,

            error: function() {
                console.log("Error");
            },

            success: function (data)
            {        

                //Creamos toda la estructura DOM para una nueva pregunta
                let div = document.createElement('div');
                div.classList.add("card-body", "border-bottom");
                document.getElementById('contenedor-preguntas').insertBefore(div, document.getElementById('textAreaPublicar'));

                let div1 = document.createElement("div");
                div1.classList.add("d-flex", "flex-start", "align-items-center");
                div.appendChild(div1);
                    let imgUser11 = document.createElement('img');
                        imgUser11.classList.add("rounded-circle", "shadow-1-strong", "me-3");
                        imgUser11.setAttribute('src', '/images/'+data.img);
                        imgUser11.setAttribute('onerror', 'this.onerror = null; this.src="/images/default_Profile.svg"');
                        imgUser11.setAttribute('width', '60');
                        imgUser11.setAttribute('height', '60');
                        div1.appendChild(imgUser11);
                    let div12 = document.createElement("div");
                        div1.appendChild(div12);
                    let tituloNombre121 = document.createElement("fw-bold", "mb-1");
                        tituloNombre121.classList.add("d-flex", "flex-start", "align-items-center");
                        let texto_tituloNombre121 = document.createTextNode(data.user);
                        tituloNombre121.appendChild(texto_tituloNombre121);
                        div12.appendChild(tituloNombre121);
                    let parrafo122 = document.createElement("div");
                        parrafo122.classList.add("text-muted", "small", "mb-0");
                        let texto_parrafo122 = document.createTextNode(data.fecha.date.toLocaleString('en-GB', { timeZone: 'UTC' }));
                        parrafo122.appendChild(texto_parrafo122);
                        div12.appendChild(parrafo122);

                
                let parrafo2 = document.createElement("div");
                    parrafo2.classList.add("mt-3", "mb-4", "pb-2");
                    let texto_parrafo2 = document.createTextNode(data.texto);
                    parrafo2.appendChild(texto_parrafo2);
                    div.appendChild(parrafo2);

                let div3 = document.createElement("div");
                div3.classList.add("small", "d-flex", "justify-content-start");
                div.appendChild(div3);
                    let enlaceResponder31 = document.createElement("a");
                    enlaceResponder31.classList.add("d-flex", "align-items-center", "me-3");
                    enlaceResponder31.id = "pink";
                    div3.appendChild(enlaceResponder31);
                        let icono311 = document.createElement("i");
                        icono311.classList.add("fas", "fa-share", "me-2");
                        let parrafo312 = document.createElement("p");
                        parrafo312.classList.add("mb-0");
                        let texto_parrafo312 = document.createTextNode("Responde");
                        parrafo312.appendChild(texto_parrafo312);
                        enlaceResponder31.appendChild(icono311);
                        enlaceResponder31.appendChild(parrafo312);

                //Borramos el mensaje que avisa de que no hay preguntas si está
                let mensajeNoPreguntas = document.getElementById("noQuestions_message");
                if(mensajeNoPreguntas){
                    mensajeNoPreguntas.style.display = "none";
                }
                
            }
        });
    });

                            /* Añadir RESPUESTA */

        let pregunta;
        let selectorDivRespuesta;
        
        $('.boton_responder').click(event => {
            event.preventDefault();
            console.log("ENTRA Bóton Responder");
            console.log(event.target);
            console.log(event.target.id);
            selectorDivRespuesta = $('#textAreaPublicar_respuesta'+event.target.id);
            console.log(selectorDivRespuesta);
            console.log($('#textAreaPublicar_respuesta'+event.target.id));
            pregunta = event.target.closest('.card-body');
            $('#textAreaPublicar_respuesta'+event.target.id).show();

            /* EventListener & IF para esconder el text area de respuesta cuando se pulsa fuera de dicho elemento */
            $(document).mouseup(function(e) {
                let container = $(selectorDivRespuesta);

                // si el "target" del click no es el contenedor o su descendiente se esconde
                if (!container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                }
            });
        });

        
        $('.boton_publicarRespuesta').click( (event) => {
            console.log("PULSADO R");
            console.log(event.target.id);
            let selectorAreaRespuesta = $('#textAreaRespuesta'+event.target.id);
            console.log(selectorAreaRespuesta);
            let textoRespuesta = selectorAreaRespuesta.val();
            console.log(textoRespuesta);
            $(selectorDivRespuesta).hide();

            console.log($("#path-to-controller-publicarRespuesta"+event.target.id).data("href"));

            /* LLAMADA AJAX A PUBLICAR RESPUESTA (Introduce respuesta en BBDD) */  
            $.ajax({
                url:$("#path-to-controller-publicarRespuesta").data("href"),
                type: "POST",
                dataType: "json",
                data: {
                    "textoRespuesta": textoRespuesta
                },
                async: true,

                error: function() {
                    console.log("Error");
                },

                success: function (data)
                {        
                    //Creamos toda la estructura DOM para una nueva respuesta
                    let div = document.createElement('div');
                    div.classList.add("card-body", "border-bottom");
                    document.getElementById('contenedor-preguntas').insertBefore(div, pregunta.nextElementSibling);

                    let div1 = document.createElement("div");
                    div1.classList.add("d-flex", "flex-start", "align-items-center", "ps-5");
                    div.appendChild(div1);
                        let imgUser11 = document.createElement('img');
                            imgUser11.classList.add("rounded-circle", "shadow-1-strong", "me-3");
                            imgUser11.setAttribute('src', '/images/'+data.img);
                            imgUser11.setAttribute('onerror', 'this.onerror = null; this.src="/images/default_Profile.svg"');
                            imgUser11.setAttribute('width', '60');
                            imgUser11.setAttribute('height', '60');
                            div1.appendChild(imgUser11);
                        let div12 = document.createElement("div");
                            div1.appendChild(div12);
                        let tituloNombre121 = document.createElement("fw-bold", "mb-1");
                            tituloNombre121.classList.add("d-flex", "flex-start", "align-items-center");
                            let texto_tituloNombre121 = document.createTextNode(data.user);
                            tituloNombre121.appendChild(texto_tituloNombre121);
                            div12.appendChild(tituloNombre121);
                        let parrafo122 = document.createElement("div");
                            parrafo122.classList.add("text-muted", "small", "mb-0");
                            let texto_parrafo122 = document.createTextNode(data.fecha.date.toLocaleString('en-GB', { timeZone: 'UTC' }));
                            parrafo122.appendChild(texto_parrafo122);
                            div12.appendChild(parrafo122);

                    
                    let parrafo2 = document.createElement("div");
                        parrafo2.classList.add("mt-3", "mb-4", "pb-2", "ps-5");
                        let texto_parrafo2 = document.createTextNode(data.texto);
                        parrafo2.appendChild(texto_parrafo2);
                        div.appendChild(parrafo2);


                    //Borramos el mensaje que avisa de que no hay preguntas si está
                    let mensajeNoRespuestas = document.getElementById("noAnwsers_message");
                    if(mensajeNoRespuestas){
                        mensajeNoRespuestas.style.display = "none";
                    }
        
                }
            });

        });
    }
      
});