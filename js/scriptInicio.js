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