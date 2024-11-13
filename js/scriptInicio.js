const handReaction = document.querySelectorAll(".reaccion");

handReaction.forEach(hand => {

  //A cada boton de accion hay que ponerle un numero
  const dataId = hand.getAttribute('data-id');

  actualizarLikes(dataId).then(likesText => {
    const likesElement = hand.nextElementSibling.querySelector('.likes'); // Asumiendo que el elemento de likes sigue inmediatamente después del div de reacción
    likesElement.textContent = likesText; // Actualizar el texto del elemento de likes
  }).catch(error => console.error('No se pudieron actualizar los likes')); 


  hand.addEventListener("click", function () {

    const config = {
      method: 'POST', // El método HTTP a utilizar
      headers: {
        'Content-Type': 'application/json', // Especifica el tipo de contenido que se envía
      },
      body: JSON.stringify({ id: dataId }), // Convierte el id en formato JSON y lo incluye en el cuerpo de la solicitud
    };

    // Realiza la solicitud fetch
    fetch('./php/likes.php', config)
      .then((response) => {
        // Verifica si la respuesta es exitosa (código de estado 200-299)
        if (!response.ok) {
          throw new Error('Error al consultar los likes');
        }
        // Convierte la respuesta en JSON
        return response.json();
      })
      .then((data) => {
        // Maneja la respuesta del servidor (datos recibidos)
        actualizarLikes(dataId).then(likesText => {
          const likesElement = hand.nextElementSibling.querySelector('.likes'); // Asumiendo que el elemento de likes sigue inmediatamente después del div de reacción
          likesElement.textContent = likesText; // Actualizar el texto del elemento de likes
        }).catch(error => console.error('No se pudieron actualizar los likes: ', error));
        // Aquí puedes realizar más acciones según la respuesta del servidor
      })
      .catch((error) => {
        // Maneja los errores de la solicitud
        console.error('Error:', error);
      });
  });
});

async function actualizarLikes(dataId) {
  return fetch(`./php/num_likes.php?id=${dataId}`)
    .then(response => response.text())
    .catch(error => console.error('Error al consultar los likes:', error));
}

function toggleDropdown() {
  document.getElementById("dropdownMenu").classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.matches('.user-icon img')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
              openDropdown.classList.remove('show');
          }
      }
  }
}
function toggleCommentForm(button) {
  // Obtener el contenedor de la publicación
  const postContainer = button.closest('.publicacion');
  // Encontrar el formulario de comentarios dentro de este contenedor
  const commentForm = postContainer.querySelector('.comment-form');
  
  // Alternar la clase hidden para mostrar/ocultar el formulario
  commentForm.classList.toggle('hidden');
}

function toggleReplyForm(button) {
  // Obtener el contenedor del comentario más cercano
  const commentContainer = button.closest('.comentario');
  // Encontrar el formulario de respuesta dentro de este contenedor
  const replyForm = commentContainer.querySelector('.reply-form');

  // Alternar la clase hidden para mostrar/ocultar el formulario
  replyForm.classList.toggle('hidden');
}
function toggleReplies(button) {
  const repliesContainer = button.previousElementSibling; // El contenedor de respuestas está justo antes del botón
  repliesContainer.classList.toggle('hidden');

  // Cambiar el texto del botón según el estado de visibilidad
  if (repliesContainer.classList.contains('hidden')) {
      button.textContent = 'Mostrar respuestas';
  } else {
      button.textContent = 'Ocultar respuestas';
  }
}
function likePost(fotoId) {
  fetch('./php/like_post.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ foto_id: fotoId })
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          // Actualiza el contador de "me gusta" en la interfaz
          document.getElementById(`likes-count-${fotoId}`).innerText = `❤️ ${data.likes_count} Me gusta`;
      } else {
          alert('Error al dar "me gusta".');
      }
  })
  .catch(error => console.error('Error:', error));
}
