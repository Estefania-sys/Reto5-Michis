document.addEventListener("DOMContentLoaded", () => {
    // ----------------------------------------------------
    // LÓGICA DE TRADUCCIÓN GLOBAL
    // ----------------------------------------------------
    const currentLang = localStorage.getItem("preferred-lang") || "es";
    setLanguage(currentLang);
    
    const langButtons = document.querySelectorAll(".btn-lang");
    langButtons.forEach(button => {
        button.addEventListener("click", () => {
            const selectedLang = button.getAttribute("data-lang");
            localStorage.setItem("preferred-lang", selectedLang);
            setLanguage(selectedLang);
        });
    });

    // ----------------------------------------------------
    // LÓGICA DEL CARRUSEL DE IMÁGENES (Soporta .carousel y .card-carousel)
    // ----------------------------------------------------
    const carousels = document.querySelectorAll('.carousel, .card-carousel');
    
    carousels.forEach(carousel => {
        const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
        const prevButton = carousel.querySelector('.carousel-prev');
        const nextButton = carousel.querySelector('.carousel-next');
        let activeIndex = 0;

        const showSlide = index => {
            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('active', slideIndex === index);
            });
        };

        if (slides.length <= 1) {
            if (prevButton) prevButton.style.display = 'none';
            if (nextButton) nextButton.style.display = 'none';
            return;
        }

        if (prevButton) {
            prevButton.addEventListener('click', event => {
                event.stopPropagation();
                activeIndex = (activeIndex - 1 + slides.length) % slides.length;
                showSlide(activeIndex);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', event => {
                event.stopPropagation();
                activeIndex = (activeIndex + 1) % slides.length;
                showSlide(activeIndex);
            });
        }
    });
});

// Función para actualizar los textos, placeholders e innerHTML según el idioma seleccionado.
function setLanguage(lang) {
    document.documentElement.lang = lang;
    const elements = document.querySelectorAll(".traductor");
    
    elements.forEach(element => {
        const tagName = element.tagName;

        // 1. Si el elemento es un INPUT o un TEXTAREA, traducimos su atributo 'placeholder'
        if (tagName === "INPUT" || tagName === "TEXTAREA") {
            const newPlaceholder = element.getAttribute(`data-${lang}-placeholder`);
            if (newPlaceholder !== null) {
                element.setAttribute("placeholder", newPlaceholder);
            }
        } 
        // 2. Si es cualquier otro elemento (H1, H2, P, A, etc.), traducimos usando innerHTML
        else {
            const newText = element.getAttribute(`data-${lang}`);
            if (newText !== null) {
                element.innerHTML = newText;
            }
        }
    });
}