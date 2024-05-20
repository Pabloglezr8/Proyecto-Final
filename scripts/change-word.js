document.addEventListener('DOMContentLoaded', () => {
    // Función de interpolación "ease-in-out"
    function easeInOutQuad(t) {
        return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
    }

    // Incremento de número con animación suave
    const counterElement = document.getElementById('counter');
    let currentNumber = 0;
    const limit = 10; // Límite hasta donde quieres incrementar
    const duration = 1500; // Duración total de la animación en milisegundos
    const startTime = performance.now();

    function animateCounter(currentTime) {
        const elapsedTime = currentTime - startTime;
        const progress = Math.min(elapsedTime / duration, 1);
        const easedProgress = easeInOutQuad(progress);
        currentNumber = Math.floor(easedProgress * limit);
        counterElement.textContent = currentNumber;

        if (progress < 1) {
            requestAnimationFrame(animateCounter);
        }
    }

    requestAnimationFrame(animateCounter);

    // Cambio de palabras
    const words = ["DE TRABAJO", "DE DEDICACIÓN", "DE COMPROMISO", "DE EXPERIENCIA", "DE COMPROMISO"];
    const changingWord = document.getElementById('change-word');
    let index = 0;

    function changeWord() {
        changingWord.style.opacity = 0; // Fade out

        setTimeout(() => {
            changingWord.textContent = words[index];
            changingWord.style.opacity = 1; // Fade in
            index = (index + 1) % words.length;
        }, 500); // Time matches the CSS transition duration
    }

    setInterval(changeWord, 3000); // Change word every 3 seconds
});