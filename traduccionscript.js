document.addEventListener("DOMContentLoaded", () => { 
    // Se queda 'escuchando' hasta que el documento HTML esté completamente cargado y listo para ser manipulado. 
    // Una vez que esto sucede, se ejecuta la función que contiene toda la lógica de traducción y carrusel.

    // ----------------------------------------------------
    // LÓGICA DE TRADUCCIÓN GLOBAL
    // ----------------------------------------------------
    const currentLang = localStorage.getItem("preferred-lang") || "es"; 
    // Intenta obtener el idioma preferido del usuario mediante localStorage. Si no hay ninguno, se aplica por defecto el español "es" y se guarda en currentLang.
    setLanguage(currentLang); // Llama a setLanguage, y le pasa el idioma actual para actualizar todos los textos.
    
    const langButtons = document.querySelectorAll(".btn-lang"); // Busca todos los elementos con clase ".btn-lang" y los guarda en una lista llamada langButtons.
    langButtons.forEach(button => { // Recorre todos los elementos encontrados de variable button
        button.addEventListener("click", () => { // Se queda 'escuchando', y cuando se hace click, ejecuta lo de abajo
            const selectedLang = button.getAttribute("data-lang"); // Va al botón y lee su atributo data-lang. Luego, guarda la información en selectedLang (es, o ca.)
            localStorage.setItem("preferred-lang", selectedLang); // Mediante localStorage, guarda o actualiza el idioma que el usuario selecciona.
            setLanguage(selectedLang); // Vuelve a llamar a setLanguage, pasándole el nuevo idioma seleccionado para actualizar todos los textos.
        });
    });

    // Función para actualizar los textos, placeholders e innerHTML según el idioma seleccionado.
function setLanguage(lang) {
    document.documentElement.lang = lang; // Actualiza el atributo lang de la etiqueta <html> del/los documento/s 
    const elements = document.querySelectorAll(".traductor"); // Busca todos los elementos con la clase "traductor"
    
    elements.forEach(element => { // Recorre todos los elementos encontrados de variable element
        const tagName = element.tagName; // Guarda el tipo de etiqueta que es

        // 1. Si el elemento es un INPUT o un TEXTAREA, traducimos su atributo 'placeholder'
        if (tagName === "INPUT" || tagName === "TEXTAREA") { // Si el elemento es una caja de texto, o un input
            const newPlaceholder = element.getAttribute(`data-${lang}-placeholder`); // Busca el atributo 'data-{lang}-placeholder' que corresponde al idiona
            if (newPlaceholder !== null) { // Si el atributo existe
                element.setAttribute("placeholder", newPlaceholder); // Actualiza el atributo 'placeholder' con el nuevo texto, ya traducido
            }
        } 
        // 2. Si es cualquier otro elemento (H1, H2, P, A, etc.), traducimos usando innerHTML
        else { // Si es cualquier otro elemento
            const newText = element.getAttribute(`data-${lang}`); // Busca el atributo con el texto del idioma 'data-{lang}' correspondiente al idioma
            if (newText !== null) { // Si existe el atributo
                element.innerHTML = newText; // Actualiza el contenido con el nuevo texto, ya traducidoExpl
            }
        }
    });
}   

    // ----------------------------------------------------
    // LÓGICA DEL CARRUSEL DE IMÁGENES (Soporta .carousel y .card-carousel)
    // ----------------------------------------------------
    const carousels = document.querySelectorAll('.carousel, .card-carousel'); // Busca todos los elementos de clase "carousel" o "card-carousel"
    
    carousels.forEach(carousel => { // Recorre todos los elementos encontrados de variable carousel
        const slides = Array.from(carousel.querySelectorAll('.carousel-slide')); // Busca todos los elementos de la clase 'carousel-slide' y lo transforma a array.
        const prevButton = carousel.querySelector('.carousel-prev'); // Busca el botón de clase 'carousel-prev' dentro del carrusel y lo guarda en prevButton
        const nextButton = carousel.querySelector('.carousel-next'); // Busca el botón de clase 'carousel-next' dentro del carrusel y lo guarda en nextButton
        let activeIndex = 0; // Comienza con la primera diapositiva activa, que enseña la primera foto, siendo esta 0

        const showSlide = index => { // 
            slides.forEach((slide, slideIndex) => { // Recorre todos los elementos encontrados en la variable slide, y con slideIndex, su posición en el array (0,1,2,3,4,5....)
                slide.classList.toggle('active', slideIndex === index); 
                // Como 'interruptor', si la condición es verdadera, se añade la clase 'active' al elemento, y si es falsa, le quita la clase active.
                // Sólo el slide que corresponda al índice activo se mostrará, el resto, no.
            });
        };

        if (slides.length <= 1) { // Si sólo hay una diapositiva, o no hay ninguna
            if (prevButton) prevButton.style.display = 'none'; // Si existe el prevButton, lo oculta
            if (nextButton) nextButton.style.display = 'none'; // Si existe el nextButton, lo oculta
            return; // Sale de la función
        }

        if (prevButton) { // Si existe el prevButton
            prevButton.addEventListener('click', event => { // Se queda 'escuchando'. Cuando se hace click, se ejecuta lo de abajo, guardando la información con la variable event.
                event.stopPropagation(); // Evita que el evento de click active por error otros eventos de elementos contenedores dentro del HTML
                activeIndex = (activeIndex - 1 + slides.length) % slides.length; 
                // Resta 1 al índice activo para ir a la diapositiva anterior
                // Le suma el número total de diapositivas para evitar números negativos
                // Calcula el residuo con el número total de diapositivas para asegurarse de que el índice se mantenga dentro del rango válido (0 a slides.length - 1)
                showSlide(activeIndex); // Llama a showSlide, pasándole el nuevo número de índice para cambiar la foto.
            });
        }

        if (nextButton) { // Si existe el nextButton
            nextButton.addEventListener('click', event => { // Se queda 'escuchando'. Cuando se hace click, se ejecuta lo de abajo, guardando la información con la variable event.
                event.stopPropagation(); // Evita que el evento de click active por error otros eventos de elementos contenedores dentro del HTML
                activeIndex = (activeIndex + 1) % slides.length; 
                // Suma 1 al índice activo para ir a la diapositiva siguiente
                // Calcula el residuo con el número total de diapositivas. Si estamos en la última diapositiva, volverá a 0, mostrando la primera diapositiva.
                showSlide(activeIndex); // Llama a showSlide, pasándole el nuevo número de índice para cambiar la foto.
            });
        }
    });
});
