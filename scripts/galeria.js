document.addEventListener("DOMContentLoaded", function() {
    const galleries = document.querySelectorAll('.gallery');
    const maxImages = 5;
  
    galleries.forEach(gallery => {
      const images = gallery.querySelectorAll('.gallery-img');
      let index = 0;
  
      function fadeIn() {
        images[index].style.opacity = '1';
        setTimeout(fadeOut, 4000); // Cambia la imagen después de 2 segundos
      }
  
      function fadeOut() {
        images[index].style.opacity = '0';
        index = (index + 1) % Math.min(images.length, maxImages);
        setTimeout(fadeIn, 1000); // Tiempo de espera antes de que aparezca la próxima imagen
      }
  
      fadeIn(); // Comienza el efecto de desvanecimiento
    });
  });
  

  function redirectPage(url) {
    var card = event.currentTarget;
    card.style.transform = 'scale(0)';
    setTimeout(() => {
      window.location.href = url;
    }, 300); // Este valor debe ser igual al tiempo de la transición en CSS
  }
  
  